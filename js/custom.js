/*******************************
 *	custom.js
 *
 *	Main jquery functions for Creative Choice Theme
 *	Functions: Translation, FormValidation, SearchAJAX, Masonry
 *	EventListeners: ToggleSidebar, TagCloudSlide, 
 *	FixMenu, ToTheTop, MetaColumn
 * 
 *	November 2018
 *	@since 1.5
 */

const ADMINBARHEIGHT	= 32;
const SCROLLDELAY 		= 300;
const LARGESCREENWIDTH 	= 950;
const REPAINTDELAY 		= 5000;		// For oEmbeds

var home_url = ccb_custom_options.home_url;

/* Form validation  */
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


/********************
 *  Masonry
 *  see http://masonry.desandro.com/options.html
 */
var $container = jQuery("#container");
function masonry_init() {
	
	if(typeof($container.masonry) !== 'undefined') {	//masonry exists
		$container.imagesLoaded(function(){
			//$container.css("opacity", 0);
			$container.masonry({
				itemSelector : '.entry',		// Class of an entry (post)
				columnWidth	 : '.grid-sizer',	// Grid sizer DIV
				initLayout	 : false,			// We lay out after applying some other javascript
				stamp		 : '.stamp'			// For stamping fixed elements in the layout
			});
			$container.masonry("layout");		
		});		/* Imagesloaded */
		$container.on( 'layoutComplete', function( event, items ) {
			$container.animate({opacity: 1}, 'fast');
			$container.css("visibility", "visible");
		});
		
	}	/* masonry defined */
}

function reloadmasonry() {
	if (typeof($container.masonry) !== 'undefined') {
		$container.imagesLoaded(function(){
			$container.masonry("layout");
			//$container.masonry('reloadItems');
		});
	}
}

/* Rearrange masonry on window resize */
jQuery ( window ).resize(function() {
	if (typeof(reloadmasonry) == 'function') reloadmasonry();	
})



/*******************************
 * When the DOM is ready... 
 ******************************/

jQuery(document).ready(function($) {

if (typeof masonry_init == 'function') masonry_init();

/* Load Flickr images via AJAX if IMG SRC elements contain hashtags. Only on first page. */

	jQuery("img[src*='#']").each(function(i) {
		tt = jQuery(this).attr('src');
		var el = jQuery(this);
		jQuery.get( "wp-content/themes/ccb/inc/flickrajax.php",{tag: tt})
			.done (function( data ) {
				el.attr({src:data});
				el.parent().css({"background-image":"url(" + data + ")"});
				el.css("display","block");
				if (typeof(reloadmasonry) == 'function') reloadmasonry();
			});
		});	//end each



FormValidate();	

$("body").css("visibility", "visible");	// Make it visible
$("img").off('error');				// Get rid of img error listeners
	

/* Define offset for scrolling from site navigation */
var headerHeight = $('#site-navigation').offset().top;

/* Adjust scroll for admin bar */
if ($("body").hasClass("admin-bar")) headerHeight = headerHeight - ADMINBARHEIGHT;
const offset = headerHeight/2;

/* Image links are not underlined */
$('img').parent('a').addClass('noborder');

/* Define empty options array if needed */
if (ccb_custom_options == 'undefined') ccb_custom_options = array();



/*******************************
 *	Tag Cloud Slide
 *
 *	Assumes ".toggletags{n} is first class of menu item, corresponding with nth widget
 */
$('[class^="toggletags"]').click(function() {
	var m = this.className.split(" ", 1)[0].slice(-1);
	$(".ccb_headbar .widget:nth-child("+m+")").siblings().slideUp(500);
	$(".ccb_headbar .widget:nth-child("+m+")").slideToggle(500);
 });
$(".ccb_headbar .widget").click(function() {
		$(this).slideUp(500);
});

/* Repaint Masonry for oEmbeds
setTimeout(function(){reloadmasonry()}, REPAINTDELAY);
*/


/* Smooth scroll to top and smooth scroll for hash-links (no domain check)
 * http://css-tricks.com/snippets/jquery/smooth-scrolling/
 */
$('.tothetop').click(function(e) { 
	e.preventDefault();
	$('html,body').animate({scrollTop: 0}, SCROLLDELAY);
	$(this).unbind("mouseenter mouseleave");
	return false;
});

/* Mobile Menu hide after item clicked 
 * - ccb_headbar hides when clicked again
 * - sidebar hides when clicked again
 */
$('.nav-menu a').click(function (e) {
	$('.main-navigation.toggled').removeClass('toggled');
});
$('.menu-toggle').click(function (e) {
	$('.ccb_headbar .widget').slideUp(500);
});


/*******************************
 *   Fix menu and show "top"-link after scrolling
 *
 * Todo: options / only on big screen / Testing 
 * http://stackoverflow.com/questions/13274592/leave-menu-bar-fixed-on-top-when-scrolled
 * ccb_options.ccb_fix_nav
 */

	var foo = false;
	$(window).scroll(function(e){
			$(".ccb_headbar .widget").slideUp();	/*Check for state; optimize */
			/* Calculate target position for headbar */
			var pos = $('#site-navigation').position().top+$('#site-navigation').outerHeight();
			$('.ccb_headbar').css('top',pos);
			/* React to scrolling */
			if ($(window).scrollTop() > headerHeight && foo != "bar") {
				$('#site-navigation, .ccb_headbar').addClass('fixed');
				$('.tothetop').fadeIn(500);
		
				foo = "bar";
			} 
			if ($(window).scrollTop() <= headerHeight) {
				$('#site-navigation, .ccb_headbar').removeClass('fixed');
				$('.tothetop').fadeOut(500);
				
				foo = false;
			}
	}); 	// fix navbar

/*******************************
 *	Fix Metacolumn
 *
 *	Fix metacolumn with scrolling until end of article
 */
	if ($("#metacolumn").length>0 && ccb_custom_options.ccb_metacolumnfloat) {
		var navtop = 10000;
		if ($(".nav-links").length>0) 	navtop = $(".post-navigation").offset().top;

		var metacolumnoffset = $("#metacolumn").offset().top;
		var h = $("#metacolumn").outerHeight(true);
		var ftop=150;
		var maxY = navtop-metacolumnoffset+ftop;
		var top = metacolumnoffset-ftop;
		var scrolled=false;
	
		$(window).scroll(function(e){
			/** Metacolumn float 
			 *  http://jsfiddle.net/bryanjamesross/VtPcm/
			 * **/

			if (window.innerWidth > LARGESCREENWIDTH && $("#metacolumn").length>0 && ccb_custom_options.ccb_metacolumnfloat) {
			
				var y = $(window).scrollTop();

				//console.log("y: ", y, "> ",top,"< maxY: ",maxY, "ftop:",ftop, scrolled, h);
				if (y<top||!scrolled) {
					//ftop = document.getElementById("metacolumn").getBoundingClientRect().top;
					metacolumnoffset = $("#metacolumn").offset().top;
					top = metacolumnoffset-ftop;
					navtop = $(".post-navigation").offset().top;
					scrolled=true;
				}
				maxY = navtop-ftop-h;
				if (y > top && maxY>ftop+h) {
				/* if scrolled below top AND 'fixable' (ie maxY > space needed) */
					if (y < maxY) {
							$('#metacolumn').css({position:'fixed', top:ftop+'px'});
						} else {
						$('#metacolumn').removeClass('fixed').css({
							position: 'absolute',
							top: (maxY+ftop) + 'px'
						});
						}
				} else {
					$('#metacolumn').css({position:'static'});
				}
			} //end metacolum float
		
		});
	}	// fix metacolumn


/*******************************
 *  Show Sidebar 
 *
 *	@forced if set true shows, false hides
 */
//Initial text of context menu element
const label = ".show_sidebar.refresh";
/*if ($(".site").hasClass("withsidebar")) {
	$(label).find("a").html(_e("No context"));
}
else {
	$(label).find("a").html(_e("Context"));
} */
function toggleSidebar(delay, forced) {
	
	if (delay === undefined) delay = 500;

	// Make sure the links within the sidebar are clickable so prevent the event from bubbling up the DOM
	$("#secondary a").click(function(e) {
		e.stopPropagation();
	});
	//$container=$("#container");		//Use fresh jquery-object eg after AJAX load
	if ($(".site").hasClass("withsidebar") || (forced !== undefined && !forced)) {
	//hide
		$("#secondary").animate({"margin-left":"100%"},delay);
		
		$(label).find("a").html(_e("Context"));
		// Use timeout to wait for animation
		setTimeout(function(){
			$(".site").removeClass("withsidebar");
			if (typeof($container.masonry) !== 'undefined')
			{
				$container.masonry();
				$container.masonry('layout');
				}
		}, delay);
	}	
	else {
		$("#secondary").animate({"margin-left":"0%"},delay);
		
		$(label).find("a").html(_e("No context"));
		$(".site").addClass("withsidebar");
		if (typeof($container.masonry) !== 'undefined') {
				$container.masonry();
				$container.masonry('layout');
			}
		
	}
	//Scroll to content
	var primarytop=$("#primary").offset().top;
	if ($(window).scrollTop() > primarytop) {
		$('html,body').animate({scrollTop: primarytop-headerHeight}, SCROLLDELAY); //
	}
} 	//toggle sidebar

$(".show_sidebar.refresh").click(function() {
	toggleSidebar()
	}
);		//show sidebar
	
/*******************************
 *  History for AJAX pageloads	
 */
$(function() {
	var History = window.History; // Note: We are using a capital H instead of a lower h
	if ( !History.enabled ) {
		 // History.js is disabled for this browser.
		 // This is because we can optionally choose to support HTML4 browsers or not.
		return false;
	}
	History.Adapter.bind(window,'statechange',function(){ 
		var State = History.getState(); // Note: We are using History.getState() instead of event.state
	});
});


});		//document.ready