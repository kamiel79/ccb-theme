/*******************************
 *	custom.js
 *
 *	Main jquery functions for Creative Choice Theme
 *	Functions: Translation, FormValidation, SearchAJAX, Masonry
 *	EventListeners: ToggleSidebar, TagCloudSlide, 
 *	FixMenu, ToTheTop, MetaColumn
 * 
 *	November 2018, May 2021
 *
 *	FormValidate()			#Form validation, binds to forms if they exist on the page
 *	_e()					#Translation function
 *	Masonryinint()
 *	ReloadMasonry()
 *	glue_menu_scroll()
 *	WidgetSlide()
 *	FixMetaColumn()
 *	togglesidebar()
 */

/* Define empty options array */
if (ccb_custom_options == 'undefined') ccb_custom_options = array();

const ADMINBARHEIGHT	= parseInt(ccb_custom_options['ADMINBARHEIGHT']);
const MENUHEIGHT 		= 46;
const SCROLLDELAY 		= 300;
const DELAY  			= 500;
const LARGESCREENWIDTH 	= 950;
const IS_MOBILE 		= window.matchMedia('(max-width: 599px)');
const REPAINTDELAY 		= 5000;		// For oEmbeds

/* Notify if development */
if (ccb_custom_options.DEVELOPMENT) {
	jQuery('.site-description').html("DEVELOPMENT SITE");
}


/* Form validation, binds to forms if they exist on the page  */
function FormValidate() {
	/* Validate comment form if it exists */
	if (typeof(jQuery(".comment-form").validate)=='function') {
	jQuery(".comment-form").validate({
	messages: {
	    author: {	
		minlength: jQuery.validator.format(_e("Type at least {0} characters")),
		required: _e("Please specify your name")
	    },
	    email: {
	      required: _e("Please provide an email address"),
	      email: _e("Your email address must be in the format of name@domain.com")
	    },
	    url: _e("Please enter a valid website"),
	    comment: {
		minlength:  _e("Please enter at least a few words."),
		required: _e("This field is required!")
	    }
	}
	});
	}
}

/********************
 *  Translation Function
 */
function _e(s) {
	if(typeof(ccb_translations) !== 'undefined') {
		if (ccb_translations[s] != null) return ccb_translations[s];
		else return s;
	}
	else 
		return s;
} 


/*******************************
 * When the DOM is ready... 
 ******************************/

jQuery(document).ready(function($) {
	 

	if (typeof FormValidate == 'function') FormValidate();

	$("img").off('error');						// Get rid of img error listeners
	
	/* Image links have no border */
	$('img').parent('a').addClass('noborder');	


	/*******************************
	 *  History for AJAX pageloads?
	 */
	$(function() {
		var History = window.History; // Note: We are using a capital H instead of a lower h
		if ( !History.enabled ) {
			return false;
		}
		History.Adapter.bind(window,'statechange',function(){ 
			var State = History.getState(); // Note: We are using History.getState() instead of event.state
		});
	});



	/* Load default image if image in entry-content doesn't load
	(function ccb_onerror_default_image(el) {
		if ($(el).length) {
			var the_post = $(el);
			var new_html = the_post.html();
			new_html.replace("<img", "<img onerror='this.onerror=null;this.src=\"" + ccb_custom_options.ccb_uri + "/img/placehodler.svg\"'");
			the_post.html(new_html);
		}
	})(".post .entry-content"); */



});		//document.ready