//jQuery(document).ready(function($) {
function infinitescroll_init() {
	var imgurl = home_url+'/wp-content/themes/ccb/img/o.gif';
	$container = jQuery('#container');
	console.log(" Found :"+jQuery('html').find('.nav-links').length);
	
	$container.infinitescroll({
		  navSelector  : '.nav-links',    // selector for the paged navigation 
		  nextSelector : '.nav-previous a',  // selector for the NEXT link (to page 2)
		  itemSelector : '.entry',     // selector for all items you'll retrieve
		  bufferPx: 50,
		  debug:false,
		  loading: {
			  msgText  : '',
			  finishedMsg: '',
			  img: imgurl //'http://i.imgur.com/6RMhx.gif'
			}
		  },
		  // trigger Masonry as a callback
		  function( newElements ) {
			// hide new items while they are loading
			var $newElems = jQuery( newElements ).css({ opacity: 0 });
			// ensure that images load before adding to masonry layout
			$newElems.imagesLoaded(function(){
			  // show elems now they're ready
			  $newElems.animate({ opacity: 1 });
			  $container.masonry();
			  $container.masonry( 'appended', $newElems, true ).fadeIn();
			});

			  }
		);console.log("infinite loaded!");

	}	// end function infinitescroll_init

//});