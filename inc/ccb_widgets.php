<?php
/*
 *	Description: Some widgets for creative choice blog.
 *	Version: 1.0
 *	Author: Kamiel Choi
 *	Author URI: http://creativechoice.org
 * 	== Widgets ==
 *		ccb widget (template)
 *		one post widget
 *		category description widget
 *		glidejs widget
 *		social share widget
 *		extended search widget
 *  == Shortcodes ==
 *  	age_func
*/

/**
 * Template ccb_Widget widget.
 * Functionality: widget title
 */
class ccb_Widget extends WP_Widget {
	
	public function widget_title( $args, $instance ) {
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}
	}

	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'ccb' );
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'ccb' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php 
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}

} // class ccb_Widget

/** 
 * One Post widget 
 */
if (!class_exists("onepost_widget")) :
class onepost_widget extends WP_Widget {
	
	function __construct() {
		parent::__construct(
		// Base ID of your widget
		'onepost_widget', 

		// Widget name will appear in UI
		__('One Post Widget', 'ccb'), 

		// Widget description
		array( 'description' => __( 'One post widget', 'ccb' ), ) 
		);
	}

	public function widget( $args, $instance ) {
		global $ccb_cols;
		$title = apply_filters( 'widget_title', $instance['title'] );
		$query =  $instance['query'];
		$rows =1;
		$defaults = array(
			'type' => 'post',
			'posts_per_page' => 1,
			'post__not_in' => get_option('sticky_posts')
		);
		$queryarr = wp_parse_args( $query, $defaults );
		//$queryarr['post__not_in'] = get_option('sticky_posts');
		$num_posts = 1;
		$ccb_cols = 1;
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] .$title . $args['after_title'];
		?>
		<div id="container" class="grid2">
			<div class='grid-sizer col col1'></div>
				<?php
				$myPosts = new WP_Query( $queryarr );
				// The Loop
				$cnt = 0;
				while ( $myPosts->have_posts() & $cnt < $num_posts) : 
					$myPosts->the_post();
					$cnt++;
					include(locate_template('content-grid2.php'));	//make local variables available in template
				endwhile;
				// Reset Post Data
				wp_reset_postdata();
				?>
		</div>
		<?php
		echo $args['after_widget'];
	}	// Widget
			
	// Widget Backend 
	public function form( $instance ) {
	if ( isset( $instance[ 'title' ] ) ) {	$title = $instance[ 'title' ];	}
	else {		$title = __( 'New title', 'ccb' );	}
	
	if ( isset( $instance[ 'query' ] ) ) {$query = $instance[ 'query' ];}
	else {	$query = 'post_per_page=1&orderby=date&order=desc';	}
	
	if ( isset( $instance[ 'rows' ] ) ) {$rows = $instance[ 'rows' ];}
	else {	$rows = 1;	}
	// Widget admin form
	?>
	<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'ccb' ); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
	<label for="<?php echo $this->get_field_id( 'query' ); ?>"><?php _e( 'Query:', 'ccb' ); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id( 'query' ); ?>" name="<?php echo $this->get_field_name( 'query' ); ?>" type="text" value="<?php echo esc_attr( $query ); ?>" />
	<label for="<?php echo $this->get_field_id( 'rows' ); ?>"><?php _e( 'Number of rows:', 'ccb' ); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id( 'rows' ); ?>" name="<?php echo $this->get_field_name( 'rows' ); ?>" type="text" value="<?php echo esc_attr( $rows ); ?>" />

	</p>
	<?php 
	} 	 	  
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['query'] = ( ! empty( $new_instance['query'] ) ) ? strip_tags( $new_instance['query'] ) : '';
		$instance['rows'] = ( ! empty( $new_instance['rows'] ) ) ? strip_tags( $new_instance['rows'] ) : '';

		return $instance;
	}
} // Class onepost_widget ends here
endif;

if (!class_exists("category_description_widget")) :
class category_description_widget extends WP_Widget {
	function __construct() {
		parent::__construct(
		// Base ID of your widget
		'category_description_widget', 
		// Widget name will appear in UI
		__('Category Description Widget', 'ccb'), 
		// Widget description
		array( 'description' => __( 'Category Description Widget', 'ccb' ),'' ) 
		);
	}

	public function widget( $args, $instance ) {
		global $post;
		$cat = get_the_category($post->ID);
		if ($cat)
			$category_id = $cat[0]->term_id;
		else $category_id="";
		echo $args['before_widget'];
		echo $args['before_title'];
		echo get_cat_name($category_id);
		echo $args['after_title'];
		echo category_description($category_id );
		echo $args['after_widget'];
	}	// Widget
}	// end of category_desciption_widget
endif;



/*********************************** 
 * Extended Search Widget
 * @since 1.1
 **********************************/
if (!class_exists("extended_search_widget")) :
class extended_search_widget extends ccb_Widget {

	function __construct() {
		parent::__construct(
		'extended_search_widget_widget', 
		__('Extended Search Widget', 'ccb'), 
		array( 'description' => __( 'Search using a date period and ...', 'ccb' )) 
		);
	}

	public function widget( $args, $instance ) {
		?>
		<aside class="widget extended_search_widget">
		<?php parent::widget_title( $args, $instance ); ?>
		<?php $n = $this->number; /* Instance number */ ?>
		<div class="searchwrapper">
			<form role="search" method="get" id="searchform" class="searchform" action="<?php echo home_url('/'); ?>">
				<label class="screen-reader-text" for="s"><?php _e("Search for:","ccb") ?></label>
				<table><tr><td colspan="2">
				<input type="text" value="<?php echo trim( get_search_query() ); ?>" placeholder=<?php _e("Search term...", 'ccb'); ?> name="s" id="s" />
				</td></tr><tr><td><?php _e("From", "ccb"); 
				echo "</td><td><input class=\"mydate from\" id='mydate{$n}from' name='from' id='from' type='text' value=''></td></tr><tr><td>"; 
				_e("Until", "ccb"); 
				echo "</td><td><input class=\"mydate until\" id=\"mydate{$n}until\" name=\"until\" id=\"until\" type=\"text\" value=\"" . date('d-m-Y') . "\"></td></tr><tr><td>"; ?>
				<input type="submit" id="searchsubmit" value="<?php _e("Search","ccb") ?>" />
				</td></tr></table>
			</form>
		</div>
		</aside>
		<script language="javascript">
		/* Bind datepicker */
		jQuery(document).ready(function($) {
		if (typeof("datepicker")!=="undefined") {
			$('.searchwrapper [id^=mydate]').datepicker({
				dateFormat : 'dd-mm-yy',
				changeMonth: true,
				changeYear: true
			});
			$('#ui-datepicker-div').hide();
			$('.mydate').focus(function(){
				$('#ui-datepicker-div').show();
				});
			}
		});
		</script>


		<?php
		}
}
endif;

/********************************
 *   Register and load the widgets
 **********************************/
if (!function_exists('ccb_load_widgets')) :
	function ccb_load_widgets() {
		register_widget( 'onepost_widget' );
		register_widget( 'category_description_widget' );
		register_widget( 'extended_search_widget' );
	}
	add_action( 'widgets_init', 'ccb_load_widgets' );
endif;



/*********************************** 
 * Shortcodes
 * @since 1.0
 **********************************/
 
if (!function_exists('age_func')) :
	function age_func( $atts ) {
		function age($d, $m, $y) {
		$y = date("Y")-$y;
		$m = date("n")-$m;
		$d = date("d")-$d;
		if ($d<0) $m--;
		if ($m<0) {$y--;$m=12-$m;}
		return (array('y'=>$y,'m'=>$m));
		}
		$a = shortcode_atts( array('y' => 2013,'m' => 1,'d' => 4), $atts );	//default to Miru's birthday
		$age = age($a['d'],$a['m'] ,$a['y']);
		$s= $age['y'].__(" year", "ccb");
		if($age['y']>1) $s.="s";
		$s.= __(" and ", "ccb").$age['m'].__(" month", "ccb");
		if($age['m']>1) $s.=__("s", "ccb");
		return $s;
	}
	add_shortcode( 'ccb_age', 'age_func' );
endif;

?>