<?php
/* Multisite functions
 * Used instead of WP's switch_to_blog() function for performance. 
 * :) Code tested only on WP4 Mysql Apache multisite
 */
if (is_multisite()) {
class multisite {

public function mu_permalink ($postid, $blogid) {
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

public function mu_tags($postid,$blogid) {
/* returns array of tag names for $postid in $blogid)
 * @since 1.0
 * @param $postid, $blogid
 * @return $tags
*/
	global $wpdb;
	$tags = "";
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

public function mu_dropdown_categories($id, $sel) {
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
     foreach ( $b as $blogId -> $tableName ) {
		$query = "SELECT wp_terms.term_id, wp_terms.name,wp_terms.slug,wp_term_taxonomy.taxonomy FROM wp_terms INNER JOIN wp_term_taxonomy ON wp_terms.term_id = wp_term_taxonomy.term_id WHERE wp_term_taxonomy.taxonomy = 'category'";
		$opts = $wpdb->get_results($query);
		if (count($opts) == 0) return;
		foreach($opts as $o) {
			$categories[$o->slug] = $o->name;
		}
	 }	
	 array_unique ($categories);
	 ksort ($categories);
	 foreach($categories as $k->$c) {
			$res .="<option value='$k'";
			if ($k==$sel) $res.=" selected";
			$res .=">$k - $c</option>";
	 }
	endif;
	$res.="</SELECT>";
	return $res;
}

public function mu_blog_names() {
/* Show all blogs in the multisite 
 * @param -
 * @return blog names
 */
	global $wpdb;
	$blognames = array();
	//fetch name of main blog with id=0
	$blogname = $wpdb->get_row("SELECT option_value FROM wp_options WHERE option_name='blogname' ");
	$blognames[0] =  $blogname->option_value;
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

private function mu_blog_prefixes() {
/* return all blog prefixes of a multisite
 * @param -
 * @return array of prefixes
*/
  global $excluded_blogs;
  global $wpdb;
  global $table_prefix;
	$rows = $wpdb->get_results( "SELECT blog_id from $wpdb->blogs WHERE
    public = '1' AND archived = '0' AND spam = '0' AND deleted = '0';" );

  if ( $rows ) :
    $b = array();
    foreach ( $rows as $row ) :
		if (!in_array($row->blog_id, $excluded_blogs)) {
			$b[$row->blog_id] = $wpdb->get_blog_prefix( $row->blog_id );
		}
    endforeach;
	return $b;
  endif;	
}

public function get_mu_tables() {
/* return all blog tables of a multisite
*/
  global $excluded_blogs;
  if ($excluded_blogs==NULL) $excluded_blogs=array();
  global $wpdb;
  global $table_prefix;
	$rows = $wpdb->get_results( "SELECT blog_id from $wpdb->blogs WHERE
    public = '1' AND archived = '0' AND spam = '0' AND deleted = '0';" );
  $b = array();
  if ( $rows ) :
    foreach ( $rows as $row ) :
		if (!in_array($row->blog_id, $excluded_blogs)) {
			$b[$row->blog_id] = $wpdb->get_blog_prefix( $row->blog_id ) . 'posts';
		}
    endforeach;
  endif;
  return $b;
}

public function mu_date_oldest_post() {
/* 	returns date of oldest post on a multisite network
*	or else the current date as a string
*/
	global $wpdb;
	$b = get_mu_tables();
	$res="now";
	if ( count( $b ) > 0 ) :
      $query = ''; $i = 0;
      foreach ( $b as $blogId -> $tableName ) :
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


public function mu_posts($howmany, $paged, $when, $until, $search) {
/* 	returns array of posts from all blogs in $blogs, newer than $when
*	older than $until, containing $search
*/
  global $options;
  global $wp_query;
  global $excluded_blogs;
  global $wpdb;
  global $table_prefix;

  $b = $this->get_mu_tables();
    if ( count( $b ) > 0 ) :
      $query = ''; $i = 0;
	  print_r($b);
      foreach ( $b as $blogId -> $tableName ) :
        if ( $i > 0 ) :
        $query.= ' UNION ';
        endif;
        $query.= " (SELECT ID, post_date, $blogId as `blog_id` FROM $tableName WHERE post_status = 'publish' AND post_type = 'post' AND post_date >= '$when' AND post_date <= '$until'";
		if (isset($search)) $query.= " AND ( post_content COLLATE UTF8_GENERAL_CI LIKE \"%{$search}%\" OR post_title LIKE \"%{$search}%\" )";
		//
		$query.=")";
        $i++;
      endforeach;
      $query.= " ORDER BY post_date DESC ";
	  /* pagination */
	  $totalposts = $wpdb->get_results($query, OBJECT);
	  $ppp = intval( get_query_var( 'posts_per_page' ) );
	  $on_page = $paged; #intval( get_query_var( 'paged' ) ); 
	  if( $on_page == 0 ) $on_page = 1;  
	  $offset = ( $on_page - 1 ) * $ppp;
	  //set global $wp_query object for pagination
	   $wp_query->found_posts = count($totalposts);
	  $wp_query->max_num_pages = ceil($wp_query->found_posts / $ppp); 
	  $wp_query->request = $query . " LIMIT $ppp OFFSET $offset";
	  $rows = $wpdb->get_results( $wp_query->request, OBJECT);
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

public function mu_thumbnail ($postid, $blogid, $size) {
/*	returns thumbnail of $postid in $blogid, or else first occuring image
*
*/
	global $options;
	global $wpdb;
	global $thumbsize;
	$classes="wp-post-image";
	//thumbnail size
	$s=$thumbsize[$size];

	$ext = "-".$s."x".$s;
	$default="";
	$p = $wpdb->get_blog_prefix( $blogid );
	$q="
    SELECT p.guid
      FROM ".$p."postmeta AS pm
     INNER JOIN ".$p."posts AS p ON pm.meta_value=p.ID 
     WHERE pm.post_id = $postid
       AND pm.meta_key = '_thumbnail_id' 
     LIMIT 1";
	$thumb = $wpdb->get_row($q);
	if ($thumb->guid!="") {
		$pos 	= strlen($thumb->guid)-4;
		$src 	= substr($thumb->guid,0,$pos).substr($thumb->guid,$pos,4);
	}
	else {
		/* get first image from post */
		$post = get_blog_post($blogid,$postid);
		
		$src_pattern = '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i';
		$src_pattern = "/<img[^>]+src=(['\"])(.+?)\\1/ix";
		//store $default to $src for returning
		$src = $default;
		$matches = array();
		if(preg_match($src_pattern, $post->post_content, $matches)) {
			//found, so replace the $src value
			$found=false; $i=1;
			while ($i-1<=count($matches)&& !$found) {
				list($img_width, $img_height, $img_type, $img_attr) = @getimagesize(trim($matches[$i]));
				if ($img_width>$options['min_img_width'] &&
					$img_height>$options['min_img_height']) {
						$src = trim($matches[$i]);
						$found=true;
				}
				$i++;
			}
		}
		else {
			return $default;
		}
	}

	$arr = @getimagesize($src);		//does file exist?
	if (is_array($arr)) {
		/* Check if portrait of landscape */
		//list($img_width, $img_height, $img_type, $img_attr) = getimagesize($src);
		$classes.=($arr[0] < $arr[1])?" tall":" wide";
		if ($arr[0]>=$options['min_img_width'] &&
					$arr[1]>=$options['min_img_height']) {	
			$img="<img src='$src'";
			if ($arr[0] < $arr[1]) {	//tall
				$s2 = (int)(($arr[1]*100)/$arr[0]);
				$img.=" width='$s' style='height:{$s2}%'";
			}
			else {	//wide
				$s2 = (int)(($arr[0]*100)/$arr[1]);
				$mar = (int)(($s2-100)/2);	//margin to center image
				$img.=" height='$s' style='width:{$s2}%; margin-left:-{$mar}%'";
			}
			$img.=" class='$classes' />";
			return $img;
		}
	}
	//return standard image if nothing else found
	return $default;
}

}	//end class multisite
}	//end is_multisite
?>