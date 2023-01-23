<?php
/**
 * Multisite functions
 * Static class with functions
 * mu_permalink
 * mu_posts
 * mu_blog_names()
 * mu_blog_prefixes ()
 * mu_tags()
 * mu_dropdown_categories()
 * mu_paging_nav
 * mu_thumbnails
 *
 * 
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Creative Choice Blog
 * @since 1.0
 */

if (!class_exists("multisite")) {
class multisite {

function __construct ($blogs = array(0=>1)) {
	self::$mu_blogs = $blogs;
}
/* Blogs in the network to include */
private static $mu_blogs;

/* Search string to be available for pagination */
private static $search;

/* Variables for pagination */
private static $howmany;
private static $totalposts;
private static $found_posts;
private static $offset;

public static function mu_permalink ($postid, $blogid) {
/* returns the permalink of post $postid in $blogid 
 * since: 1.0
 * @param $postid, $blogid
 * @return permalink of blog post
*/
	global $wpdb;
	$p = $wpdb->get_blog_prefix( $blogid );
	$q = "SELECT guid
		  FROM " . $p . "posts
		  WHERE ID=" . $postid;
	$res = $wpdb->get_row($q);
	return $res->guid;
}

public static function mu_tags($postid,$blogid) {
/* returns array of tag names for $postid in $blogid)
 * @since 1.0
 * @param $postid, $blogid
 * @return $tags
*/
	global $wpdb;
	$tags = array();
	$pre = $wpdb->get_blog_prefix($blogid);
	$q	= "SELECT name FROM {$pre}terms 
	INNER JOIN {$pre}term_taxonomy 
	ON {$pre}term_taxonomy.term_id = {$pre}terms.term_id 
	INNER JOIN {$pre}term_relationships 
	ON {$pre}term_relationships.term_taxonomy_id = {$pre}term_taxonomy.term_taxonomy_id 
	WHERE taxonomy = 'post_tag' AND object_id = $postid";
	$tagslist = $wpdb->get_results($q);
	if (count($tagslist)>0) {
		foreach ($tagslist as $tag) {
			$tags[] = $tag->name;
		}
	}
	return $tags;
}

public static function ccb_mu_random_tag($postid,$blogid) {
/*  return random tag from a certain post */
	$tags = self::mu_tags($postid,$blogid);
	if ($tags) {
		$r = rand(0, count($tags)-1);
		return $tags[$r];
	}
}

function mu_dropdown_categories($id, $sel) {
/*	return dropdown list of all categories in the multisite
 *	@since 1.0
 *  @param $id, $sel
 *  @return HTML dropdown list.
 *  !uses slug as unique identifier
 */
	global $wpdb;
	$res = "<SELECT name='$id' id='$id'>";
	$categories=array();
	$b = mu_blog_prefixes();
	if ( count( $b ) > 0 ) :
      $query = ''; $i = 0;
     foreach ( $b as $blogId => $tableName ) {
		$query = "SELECT wp_terms.term_id, wp_terms.name,wp_terms.slug,wp_term_taxonomy.taxonomy FROM wp_terms INNER JOIN wp_term_taxonomy ON wp_terms.term_id = wp_term_taxonomy.term_id WHERE wp_term_taxonomy.taxonomy = 'category'";
		$opts = $wpdb->get_results($query);
		if (count($opts) == 0) return;
		foreach($opts as $o) {
			$categories[$o->slug] = $o->name;
		}
	 }	
	 array_unique ($categories);
	 ksort ($categories);
	 foreach($categories as $k=>$c) {
			$res .="<option value='$k'";
			if ($k==$sel) $res.=" selected";
			$res .=">$k - $c</option>";
	 }
	endif;
	$res.="</SELECT>";
	return $res;
}

static function mu_blog_names() {
/* Return a keyed Array of id, names of all blogs in the multisite 
 * @param -
 * @return blog names
 */
	global $wpdb;
	$blognames = array();
	//fetch name of main blog with id=0
	$blogname = $wpdb->get_row("SELECT option_value FROM wp_options WHERE option_name='blogname' ");
	$blognames[0] = $blogname->option_value; //Main Site
	$blogs = $wpdb->get_results( "SELECT blog_id from $wpdb->blogs WHERE
    public = '1' AND archived = '0' AND spam = '0' AND deleted = '0';" );
	foreach ( $blogs as $blog ) {
		$blogname = $wpdb->get_results("SELECT option_value FROM wp_".$blog->blog_id ."_options WHERE option_name='blogname' ");
		foreach( $blogname as $name ) { 
			$blognames[$blog->blog_id] = $name->option_value;
		}
	}
	return $blognames;
}

public static function mu_blog_prefixes() {
/* return all blog prefixes of a multisite
 * @param -
 * @return array of prefixes
*/
  $mu_blogs = self::$mu_blogs;

  global $wpdb;
  global $table_prefix;
	$rows = $wpdb->get_results( "SELECT blog_id from $wpdb->blogs WHERE
    public = '1' AND archived = '0' AND spam = '0' AND deleted = '0';" );

  if ( $rows ) :
    $b = array(0=>"wp_");
    foreach ( $rows as $row ) :
		if (in_array($row->blog_id, $mu_blogs)) {
			$b[$row->blog_id] = $wpdb->get_blog_prefix( $row->blog_id );
		}
    endforeach;
	
	return $b;
  endif;	
}

public static function get_mu_tables() {
/* Returns all blog tables of the blogs in $mu_blogs
 * @since 	1.0
 * @return	keyed array of table names for posts
 *  
 */
  if (self::$mu_blogs==NULL) return array();
  
  global $wpdb;

	$b = array();
	/* Is main blog included? */
	if (self::$mu_blogs[0]==0) $b[0] = 'wp_posts';
	foreach ( self::$mu_blogs as $bid => $included ) :
		if ($included)
			$b[$included] = $wpdb->get_blog_prefix( $included ) . 'posts';
	endforeach;
	return $b;
	
}

function mu_date_oldest_post() {
/* 	returns date of oldest post on a multisite network
*	or else the current date as a string
*/
	global $wpdb;
	$b = self::get_mu_tables();
	$res="now";
	if ( count( $b ) > 0 ) :
      $query = ''; $i = 0;
      foreach ( $b as $blogId => $tableName ) :
        if ( $i > 0 ) :
        $query.= ' UNION ';
        endif;
		$query .= "SELECT post_date FROM $tableName WHERE post_status = 'publish' AND post_type = 'post'";
		$i++;
	  endforeach;
	  $query .=" ORDER BY post_date ASC LIMIT 1";
	  $res = $wpdb->get_row( $query )->post_date;
	endif;  
	return $res;
}


static function mu_posts($howmany, $paged, $search="", $from="01-01-1900", $until="", $tag="") {
/* 	returns array of posts from all blogs in $blogs, newer than $from
*	older than $until, containing $search
*/
  global $options;
  global $wp_query;
  global $wpdb;
  global $table_prefix;
  /* convert date into timestamps MySQL understands */
  //$from = strtotime($from);
  //$until = strtotime($until);
  if ($until=="") $until = date("d-m-Y");
  self::$search = $search;	//save search for later use, eg in paging  
  $b = self::get_mu_tables();
  /* Get all blog prefixes if needed */
  if ($tag!="") $prefixes = self::mu_blog_prefixes();
  /* In case there are multisite tables to query */
  if ( count( $b ) > 0 ) :	//if at least 1 table

	$query = ''; 
	$i = 0;
	$offset = max($paged-1, 0) * $howmany;			//calculate offset
	      foreach ( $b as $blogId => $tableName ) :
		    if ( $i > 0 ) $query.= ' UNION ';
			$wp = $table_prefix.$blogId."_";
			$query.= " (SELECT ID, post_date, {$blogId} as `blog_id` 
			FROM $tableName WHERE post_status = 'publish' AND post_type = 'post'";
			if ($search!="") {
				/* limit results by search */
				$query .= " AND ( post_content LIKE \"%{$search}%\" OR post_title LIKE \"%{$search}%\" )";
			} // if $search
			if ($tag!="") {
				/* limit results by tag */
				$pre = $prefixes[$blogId];
				$query .= " AND ID IN (SELECT {$pre}posts.ID
					FROM $tableName, {$pre}term_relationships, {$pre}terms
					WHERE {$pre}posts.ID = {$pre}term_relationships.object_id
					AND {$pre}terms.term_id = {$pre}term_relationships.term_taxonomy_id
					AND {$pre}terms.name = '{$tag}')";			
				
			}
			/* limit results by post_date. note that the end date is not inclusive so we add 1 day */
			$query .= " AND post_date BETWEEN STR_TO_DATE('{$from}','%d-%m-%Y') AND ADDDATE(STR_TO_DATE('{$until}','%d-%m-%Y'),1)";

			$query.=")";
		$i++;
	      endforeach;
	  /* prepare query to count the total amount of posts (all pages */
	  $cquery =  str_replace("ID", "COUNT(*) as count", $query);

	  $query.= " ORDER BY post_date DESC LIMIT {$howmany} OFFSET " . $offset;

	  $rows = $wpdb->get_results($query, OBJECT);	  
	  /* Optimization: count total posts only if the retrieved page was full */
	  $num=0;
	  if (count($rows) == $howmany) {
		  $r = $wpdb->get_results($cquery);
		  foreach ($r as $x=>$c)
		  $num += $c->count;
		  self::$totalposts = $num;
	  }

	  /* Pagination. Todo exact pagination with COUNT query and use of this class instead of wp_query */
	  $wp_query->found_posts = count($rows);
	  $wp_query->howmany = $howmany;
	  self::$howmany =  $howmany;
	  self::$offset = $offset;
	  self::$found_posts = count($rows);
	  $wp_query->offset = $offset;

	  if ( $rows ) :
		$posts = array();
		$i=0;
		global $post;
		foreach ( $rows as $post ) :
			$posts[$i] = get_blog_post( $post->blog_id, $post->ID );
			$posts[$i]->blog_id = $post->blog_id;
			$i++;
		endforeach;
		$wp_query->posts = $posts;
        return $posts;
      endif;
	  
  endif;
}

public static function mu_thumbnail ($postid, $blogid, $size='thumbnail', $output='tag', $classes='wp-post-image') {
/*	returns thumbnail of $postid in $blogid, or else first occuring image, or image from Flickr, or fallback
*
*/
	switch_to_blog( $blogid );
	$thumb = ccb_thumbnail ($postid, $size, $output, $classes);
	restore_current_blog();
	return $thumb;
}


static function mu_paging_nav($p, $search="") {
/* Displays navigation to next/previous pages when applicable.
 * @args $p: current page
 */
	global $wp_query;
	global $wp_rewrite;
	global $from;
	global $until;
	if ( $wp_query->found_posts < $wp_query->howmany && $wp_query->offset==0) {
		return;
	}
	
	$search = self::$search;
	/* Determine name of current page */
	$current_url =  get_permalink( get_the_ID() );//home_url( '/' ) . CCB_MULTIPAGE;
	
	/* Output pretty link if used */
	//$mp = ($wp_rewrite->using_permalinks())?"mp/":"?mp=";
	$mp = "?paged=";	//solve permalink issue later
	//$and = ($wp_rewrite->using_permalinks())?"?":"&";
	$s = ($search!="")?"&s=$search":"";
	$s = ($from!="")?"$s&from=$from":$s;
	$s = ($until!="")?"$s&until=$until":$s;
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'ccb' ); ?></h1>
		<div class="nav-links">
		<?php
		if ($wp_query->found_posts ==0) _e("No results","ccb");
		
		/* Show link to older posts only if page is full, then check if totalposts greater*/
		if ( self::$found_posts == self::$howmany && self::$offset+self::$howmany < self::$totalposts ) : ?>
			<a href="<?php echo $current_url.$mp.($p+1) . $s;?>" id="previous-link" class="previous-link">
			<div class="nav-previous">
			<!-- "&s=$search&from=$t&until=$u" //-->
			
			<?php
			_e( '<span class="meta-nav"></span> Older posts', 'ccb' );
			?></div>
			</a>
			
		<?php endif;
		if ( $p > 1) : ?>
			<a href="<?php echo $current_url.$mp.($p-1) . $s; ?>" id="next-link" class="next-link">
			<div class="nav-next">
			<?php
			_e( 'Newer posts<span class="meta-nav"></span>', 'ccb' );
			;?></div>
			</a>
			
		<?php endif; ?>
		
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
<?php
}


}	// end of class multisite
}
?>