<?php 

/* support functions */
function string_limit_words($string, $word_limit, $char_limit)
{
  $words = explode(' ', $string, ($word_limit + 1));
  if(count($words) > $word_limit)
  array_pop($words);
  return substr (implode(' ', $words),0,$char_limit) . "...";
}

function ccb_img_style($url) {
	//assume .PNG-images should not be cropped
	if (strpos($url, ".png"))
		echo "background-size:contain";
}
?>
