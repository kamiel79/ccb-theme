/* Isotope Init
 * Loads isotope using a grid sizer. Class names are documented in custom.css
 * @date	May 2015
 * @since	1.5 
 */
	
jQuery(document).ready(function($) {
	var $container = $('#container');
	if(typeof($container.isotope) !== 'undefined') {
		$container.imagesLoaded(function(){
			$container.isotope({
				itemSelector : '.entry',		// Class of an entry (post)
				columnWidth	 : '.grid-sizer',	// Grid sizer DIV
				isInitLayout : false,			// We lay out after applying some other javascript
				stamp		 : '.stamp'			// For stamping fixed elements in the layout
			});
			$container.isotope();
		});		/* Imagesloaded */
	
	}	/* isotope defined */
	
	/* filter items on button click */

	$('#filters').on( 'click', 'button', function(e) {
		var filterValue = $(this).attr('data-filter');
		$container.isotope({ filter: filterValue }).isotope( 'reloadItems' ).isotope();
		$container.masonry('reloadItems').masonry();
	});
	
});