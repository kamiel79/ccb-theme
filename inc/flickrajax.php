<?php
/* Simple Flickr Photo URL Getter for loading with AJAX
 * Returns URL of first image Flickr offers with $tag if $count=1
 * Else an array with such URLs
 * Since 0.1
 * Contains hardcoded API-key but whatever
 */

const FLICKR_API_KEY = "cbf4af9da8fa6d913d2340b54f180686"; 
$tag = substr($_GET['tag'],1);

function get_flickr_img ($tag, $count=1, $m="_m", $format="") {
  $tag 			= urlencode($tag);
  $thumb_url 	= "";
  $url 			= 'https://api.flickr.com/services/rest/?';
  $url 			.= 'method=flickr.photos.search&api_key='.FLICKR_API_KEY.'&tags='.$tag.'&per_page='.$count;
  $url 			.= 'format='.$format;
  $xml = simplexml_load_file($url);
  if (!$xml) return false;
# http://www.flickr.com/services/api/misc.urls.html
# http://farm{farm-id}.static.flickr.com/{server-id}/{id}_{secret}.jpg

if (count ($xml->photos->photo)>0) {
	foreach ($xml->photos->photo as $photo) {
	  $title 	= $photo['title'];	
	  $farmid 	= $photo['farm'];
	  $serverid = $photo['server'];
	  $id		= $photo['id'];
	  $secret 	= $photo['secret'];
	  $owner 	= $photo['owner'];
	  if ($count>1) $thumb_url[] = "http://farm{$farmid}.static.flickr.com/{$serverid}/{$id}_{$secret}{$m}.jpg";
	  else $thumb_url = "http://farm{$farmid}.static.flickr.com/{$serverid}/{$id}_{$secret}{$m}.jpg";
	  }
	}

return $thumb_url;

} // get_flickr_img

echo get_flickr_img($tag);	//Print resulting url of the image

?>