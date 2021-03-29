/* Searchajax.js
 * Simple AJAX search form
 * @since 1.0
 * @PHP injected object base_uri
 * @.search-form .search-field.val() -> #content
 */

jQuery(document).ready(function($) {

  $(document).on('submit', '.searchform', function( e ){
		
		// Stop the form from submitting
		e.preventDefault();
		
		// Get the search term
		var field = $(this).find("#s");
		var term = field.val();		
		var url = ccb_options.search_uri;

		// Get search period
		var datefrom = $(this).find(".from").val();
		var dateuntil = $(this).find(".until").val();
		var restorepadding = field.css("padding-left");
	
		// Make sure the user searched for something
		if ( term ){
			// Display throbber
			field.animate({"padding-left":"2em"}, 200, function(){
			field.addClass("ccb-search-throbber");
			field.css("background-image","url(" + ccb_options.throbber + ")");
			});
			
			// Hide full width image
			$(".site-content>img.fullwidth").fadeOut(500).slideUp(500);
			
			// History
			period=""; 
			if (datefrom!=undefined) period+="&from="+datefrom;
			if (dateuntil!=undefined) period+="&until="+dateuntil;
						
			$.get( url, 
			{ s: term, from : datefrom, until : dateuntil }, 
			function( data ){
				$('#primary').html( $(data).find('#main') );
			})
			.done(function() {
				
				History.pushState(null, _e("Creative Choice: Searching for ") + term, url+"?s="+term+period);

				// Blur input field
				field.blur();
				
				// Remove background image
				field.removeClass("ccb-search-throbber");
				
				field.css("background-image","none");
				field.css("padding-left", restorepadding);
				
				// Emty field
				field.val("");
	
				$container = jQuery ("#container");
				masonry_init();
				// Scroll to top
				$('html,body').animate({scrollTop: 0}, 300);
							
		});
		}
		return false;
	});
});