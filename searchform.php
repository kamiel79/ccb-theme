<div class="searchwrapper">
<span class="toggle"></span>
<form role="search" method="get" id="searchform" class="searchform" action="<?php echo home_url('/'); ?>">
	<label class="screen-reader-text" for="s"><?php _e("Search for:","ccb") ?></label>
	<input type="text" value="<?php echo trim( get_search_query() ); ?>" placeholder=<?php _e("Search term...", 'ccb'); ?> name="s" id="s" />
	<input type="submit" id="searchsubmit" value="<?php _e("Search","ccb") ?>" />
</form>
</div>
