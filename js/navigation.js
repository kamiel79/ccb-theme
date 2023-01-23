/**
 * navigation.js
 * Handles navigation effects:
 * Sidebar slide
 * Menu glue to top
 * Metacolumn float
 * Progress bar
 * Handles toggling the navigation menu for small screens.
 */

if (ccb_custom_options == 'undefined') ccb_custom_options = array();

( function($) {
	if (IS_MOBILE.matches) {
		var container, button, menu;
	

		container = document.getElementById( 'site-navigation' );
		if ( ! container )
			return;
	    mob = document.getElementById( 'mobile-navigation' );
		button = mob.getElementsByTagName( 'button' )[0];
		if ( 'undefined' === typeof button )
			return;

		menu = container.getElementsByTagName( 'ul' )[0];

		// Hide menu toggle button if menu is empty and return early.
		if ( 'undefined' === typeof menu ) {
			button.style.display = 'none';
			return;
		}

		if ( -1 === menu.className.indexOf( 'nav-menu' ) )
			menu.className += ' nav-menu';

		button.onclick = function() {
			if ( -1 !== container.className.indexOf( 'toggled' ) ) {
				$(".nav-menu").animate({marginLeft:"-100%",opacity:1}, DELAY, 'swing',
					function() {container.className = container.className.replace( ' toggled', '' );})
				
			}
			else {
				container.className += ' toggled';
				jQuery(".nav-menu").animate({marginLeft:"0%",opacity:1}, DELAY, 'swing');
				$('html,body').animate({scrollTop: 0}, DELAY);
			}
		};

		/* Mobile Menu hide after item clicked 
		 * - ccb_headbar hides when clicked again
		 * - sidebar hides when clicked again
		 */

	
		/* Initially hide sidebar on mobile devices */
		$(".site").removeClass("withsidebar");
		$(".site .content-area").removeClass("showsidebar");
		$(".site #sidebar").removeClass("showsidebar");
	 
		$('.main-navigation button:not(.menu-toggle)').click(function (e) {
			$('.main-navigation.toggled').removeClass('toggled');
		});
		$('.menu-toggle').click(function (e) {
			$('.ccb_headbar .widget').slideUp(DELAY);
			/* Scroll to top if menu becomes visible */
			if (!$('.main-navigation').hasClass('toggled')) {
			//	$('html,body').animate({scrollTop: 0}, DELAY);
			}
		});
	}
			
} )(jQuery);	//mobile functions

jQuery(document).ready(function($) {
/* Define offset for scrolling from site navigation */
var navTop = $('#site-navigation').offset().top;




/*******************************
 *	Widget Slide
 *
 *	Assumes ".toggletags{n} is first class of menu item, corresponding with nth widget
 *  Set focus on search field upon clicking the magnifier. Assuming the searchform in the headbar  
 */
function WidgetSlide() {
	$('[class^="toggletags"]').click(function(event) {
		var m = this.className.split(" ", 1)[0].slice(-1);
		$(".ccb_headbar .widget:nth-child("+m+")").siblings().slideUp(DELAY);
		$(".ccb_headbar .widget:nth-child("+m+")").slideToggle(DELAY,function(){ 
			if ( event.target.className.includes("ccb_navsearch") ) { 
				$(".ccb_headbar .searchform #s").val("");
				$(".ccb_headbar .searchform #s").focus();
				
			}
		});
	 });
	// Hide the widget after clicking on a link or a button
	$(".ccb_headbar .widget a, .ccb_headbar .widget input[type='submit']").click(function() {
			$(".ccb_headbar").slideUp(DELAY);
	});
}
WidgetSlide();

/* Smooth scroll to top and smooth scroll for hash-links (no domain check)
 * http://css-tricks.com/snippets/jquery/smooth-scrolling/
 */
$('.tothetop').click(function(e) { 
	e.preventDefault();
	$('html,body').animate({scrollTop: 0}, SCROLLDELAY);
	$(this).unbind("mouseenter mouseleave");
	return false;
});


/*******************************
 *	 Fade main div on hovering a submenu. CSS animation with animation-delay
 */
$('#menu-main-menu .menu-item-has-children').mouseenter(
	function(e) {
		$('.site-main').animate({"opacity":"0.75"},DELAY/2);
	}).mouseleave (
	function(e) {
		$('.site-main').animate({"opacity":"1"},DELAY/2);
	}); 



/*******************************
 *  Toggle Sidebar 
 *
 *	@forced if set true shows, false hides
 */
//Initial text of context menu element
const label = ".show_sidebar.refresh";

function toggleSidebar(sidebar, delay=500, forced) {
	
	// Make sure the links within the sidebar are clickable so prevent the event from bubbling up the DOM
	$("#secondary a").click(function(e) {
		e.stopPropagation();
	});

	/* Hide text temporarily */
	//$(".site-main").animate({'opacity':'0'},delay/2).delay(delay).animate({'opacity':'1'},delay/2);

	/* Wait briefly before sliding */
	setTimeout(function() {
		
		/* Toggle sidebar */

		$(".site #sidebar").toggleClass("showsidebar");		/* Smooth transition only for class of the element itself */
		
		/* Toggle sidebar label and general tag */
		if (sidebar.hasClass("showsidebar")) {
			$(".site").addClass("withsidebar");
			$(".site .content-area").addClass("showsidebar");	
			$(label).find("a").html(_e("No context"));
		}
		else {
			$(".site").removeClass("withsidebar");
			$(".site .content-area").removeClass("showsidebar");	
			$(label).find("a").html(_e("Context"));
		}	

	}, delay/2);

} 	//toggle sidebar

$(".show_sidebar.refresh").click(function() {
	sidebar = $(".site #sidebar");
	toggleSidebar(sidebar);

});


/* Show @color2 progress bar on articles that are longer */
(function ccb_progress_bar(el) {
	if ( $(el).length ) {
		var article_height = Math.max(screen.height, $(el).outerHeight() + $ (el).offset().top);

		if (ccb_custom_options.CCB_PROGRESS_BAR && article_height > 2 * screen.height) {
			$(".main-navigation").append("<span class='progress-bar'></span>");
			var progress_bar 	= $(".progress-bar");
			var pos;
			var scrolltop 		= 0;
			$(window).scroll(function(e) {
				scrolltop = $(window).scrollTop();
				pos = (1- (scrolltop / article_height)) * 100;
				progress_bar.css({"margin-left": "-" + pos +  "%"});
			});
		}	//endif
	}
})('.single article');



/*******************************
 *	Fix Metacolumn
 *
 *	Fix metacolumn with scrolling until end of article
 *	Assumes the metacolumn is within a relative div, sibling of the scrolled content
 *	And there is a post-navigation bar below
 */
 if ($("#metacolumn").length>0 && ccb_custom_options.CCB_METACOLUMNFLOAT) {
  		var metacolumnoffset = $("#metacolumn").offset().top;
		var h = $("#metacolumn").outerHeight();
		var ftop = metacolumnoffset - $("#main").offset().top + ADMINBARHEIGHT + MENUHEIGHT;
		var postnavtop = $(".post-navigation").offset().top;
		var top = metacolumnoffset-150; //ftop;
		var relativeftop = 120;//metacolumnoffset - $("#main").offset().top;
		var above = $("#main").offset().top;
		var maxY =  postnavtop-h - 150;//ftop;
		var metacolumnfloats = false;
 }

function FM1(refresh=false) {
	if ($("#metacolumn").length>0 ) {

		/* Only on wide screens */
			if (window.innerWidth > LARGESCREENWIDTH) {
				if (refresh) {
					postnavtop = $(".post-navigation").offset().top;
					maxY =  postnavtop-h-150;
					//metacolumnoffset = $("#metacolumn").offset().top;
				}
				var y = $(window).scrollTop();
				if ((y > top && maxY>h)) { //
				/* if scrolled below top AND 'fixable' (ie maxY > space needed) */
					if (y < maxY) {
							$('#metacolumn').css({position:'fixed', top:relativeftop +'px'});
							metacolumnfloats = true;
						} 
					else {
						//if (metacolumnfloats || refresh) metacolumnoffset = $("#metacolumn").offset().top;
						metacolumnfloats = false;
						//Recalculate above at time of metacolumn unfix in case menus are folded 
						if (true||above == 0 || refresh) {
							above = $("#main").offset().top;
							//metacolumnoffset = $("#metacolumn").offset().top;
						}
						$('#metacolumn').css({
							position: 'absolute',
							top: maxY-above+relativeftop //metacolumnoffset - above //(maxY-above-ftop) + 'px'
						});
						//$('#metacolumn').animate({opacity: 0},DELAY);
					}

				} 
				else {
					$('#metacolumn').css({position:'static'});

				}
				
			} 
		}
} //FM1
	console.log(ccb_custom_options);
if ($("#metacolumn").length>0 && ccb_custom_options.CCB_METACOLUMNFLOAT 
	&& $("#metacolumn").outerHeight() < window.innerHeight) {

	$(window).scroll(function(e) {
		FM1(false);
	});
}	// fix metacolumn

/*******************************
 *   Glue menu to top and show "top"-link after scrolling
 *
 * 
 * http://stackoverflow.com/questions/13274592/leave-menu-bar-fixed-on-top-when-scrolled
 * ccb_options.ccb_fix_nav
 */
(function glue_menu_scroll() {
	var ontop = false;
	var prevscroll = 0;
	var menu_visible = true;
	var scrolltop = 0;
	if (ccb_custom_options.CCB_HIDE_NAVBAR_ON_SCROLL) {
		var curscroll = $(this).scrollTop();
		var navheight = $('.main-navigation').outerHeight() - ADMINBARHEIGHT;

	}
	$(window).scroll(function(){

			scrolltop = $(window).scrollTop();

			/* Calculate target position for headbar */
			//var pos = $('.main-navigation').position().top + $('.main-navigation').outerHeight() + $('.mobile-navigation').outerHeight();
			//$('.ccb_headbar').css('top',pos);
			/* React to scrolling by adding/removing the class fixed */
			if (scrolltop > navTop && !ontop) {
				$('.main-navigation, .ccb_headbar, .mobile-navigation').addClass('fixed')
				$('.tothetop').fadeIn(DELAY);
				$(".ccb_headbar .widget").slideUp();	/*Check for state; optimize */
				ontop = true;
			} 
			if (scrolltop <= navTop) {
				$('#site-navigation, .ccb_headbar, .mobile-navigation').removeClass('fixed');
				$('.tothetop').fadeOut(DELAY);
				
				ontop = false;
			}
			
			// Hide navigation on scrolling down
			if (scrolltop <= prevscroll && ontop && !menu_visible) {
				$('.main-navigation').animate({"top":ADMINBARHEIGHT + "px"}, DELAY/2);

				menu_visible = true;		
			}
			if (scrolltop > prevscroll && ontop && menu_visible) {
				$('.main-navigation').animate({"top":-navheight + "px"}, DELAY/2);
				menu_visible = false;
			}

			prevscroll = scrolltop;
			
	}); 	// fix navbar
	
}());	//execute this: glue_menu_scroll();	


$('.movingparts').on('transitionend',
	function(e) {
		FM1(true);
	});





});
