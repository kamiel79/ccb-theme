/* Infinite Scroll by Paul Irish
 * http://www.infinite-scroll.com/infinite-scroll-jquery-plugin/
 * https://github.com/paulirish/infinite-scroll/issues/247
 * Load only if defined and nextSelector exists  
 
if (false && ccb_options.ccb_infinitescroll && typeof($container.infinitescroll) !== 'undefined' && typeof($container.imagesLoaded) !== 'undefined' && jQuery('.nav-links a').length ) {
$container.imagesLoaded(function(){
	$container.infinitescroll({
	  navSelector  : '.paging-navigation .nav-links',    			// selector for the paged navigation 
	  nextSelector : '.nav-links a:first',		// selector for the NEXT link (to page 2)
	  itemSelector : '.entry',     				// selector for all items you'll retrieve
	  debug		   : true,
	  //bufferPX : 0,
	  pixelsFromNavToBottom:40,
	  //extraScrollPx: 40,
	  animate:true,
	  loading: {
		  finishedMsg: _e("No more pages to load."),
		  img: ccb_options.ccb_uri+'/img/o.gif',
		  msgText: "<div class=\"loading\">" + _e("Loading") + "...</div>",
		  speed: 'fast'
		}
	  },
	  
	  // trigger other scripts as a callback
	  function( newElements ) {
		// hide new items while they are loading
		var $newElems = $( newElements ).css({ opacity: 0 });
		// ensure new elements have bindings
		if (typeof($container.infinitescroll) !== 'undefined') {
			//if (typeof($container.masonrybind) !== 'undefined') $container.masonrybind();
			$container.bind("jQuery.fn.ccb_ajaxbind");	
			// ensure that images load before adding to masonry layout
			$newElems.imagesLoaded(function(){
			  // show elems now they're ready
			  $newElems.animate({ opacity: 1 });
			  if (typeof($container.font_resize) !== 'undefined') $container.font_resize();
			  if (typeof($container.ccb_ajaxbind) !== 'undefined') $container.ccb_ajaxbind();
			  $container.masonry( 'appended', $newElems, true ).fadeIn();
			});
		}
		else {
			$newElems.animate({ opacity: 1 });
		}
		// Destroy instance of infinitescroll if less than pagesize is loaded i.e. end is reached
		if (newElements.length < ccb_options.ccb_pagesize) {
			$container.unbind('.infinitescroll');
			$container.infinitescroll('destroy');
		}
	  } 
	);
	});  //imagesLoaded
}	//infinite scroll defined
*/