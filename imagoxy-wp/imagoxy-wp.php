<?php

/*

Plugin Name: Imagoxy-WP

Plugin URI: https://sourceforge.net/projects/imagoxy/

Description: Imagoxy-WP is a wordpress front-end for Imagoxy. Imagoxy downloads pictures from remote server to local server and relocate corresponding http requests to the local one. It is used to access pictures when the remote server is banned or slow from the network of clients. Imagoxy-WP now converts Picasa and Flickr URLs to local Imagoxy URLs, and you can also add your customed conversion.

Author: Jian Lin

Version: 0.54

Author URI: http://blog.linjian.org/

*/ 

function imagoxy_wp_convert($text) {
	
	// ======== Configure, MODIFY HERE FIRST ! ========
	
	// Do NOT omit the '/' after the dir name.
	// As same as Imagoxy's $work_dir.
	$imagoxy_dir = "http://www.example.com/imagoxy/";
	
	// ======== Initialization ========
	
	$exchangeSource = array();
	$exchangeDest = array();
	
	// ======== Conversion for Picasa ========
	
	$picasa_num = preg_match_all("/<img.+?src=\"http:\/\/.*?\.ggpht\.com\/.*?\".*?>/i", $text, $picasa_matches, PREG_PATTERN_ORDER);
	
	if ($picasa_num > 0) {
		foreach ($picasa_matches[0] as $picasa_img) {
			array_push($exchangeSource, $picasa_img);
			$picasa_url = preg_replace("/<img.+?src=\"(http:\/\/.*?\.ggpht\.com\/.*?)\".*?>/i", "$1", $picasa_img);
			$picasa_img_c1 = preg_replace("/<img(.+?)src=\"http:\/\/.*?\.ggpht\.com\/.*?\".*?>/i", "$1", $picasa_img);
			$picasa_img_c2 = preg_replace("/<img.+?src=\"http:\/\/.*?\.ggpht\.com\/.*?\"(.*?)>/i", "$1", $picasa_img);
			$picasa_url_new = urlencode(strrev(base64_encode($picasa_url)));
			$picasa_img_new = "<img" . $picasa_img_c1 . "src=\"$imagoxy_dir" . 
				"getimg.php?u=" . $picasa_url_new . "\"" . $picasa_img_c2 . ">";
			array_push($exchangeDest, $picasa_img_new);
		}
	}
	
	$picasa_num = preg_match_all("/<img.+?src=\"https:\/\/.*?\.googleusercontent\.com\/.*?\".*?>/i", $text, $picasa_matches, PREG_PATTERN_ORDER);
	
	if ($picasa_num > 0) {
		foreach ($picasa_matches[0] as $picasa_img) {
			array_push($exchangeSource, $picasa_img);
			$picasa_url = preg_replace("/<img.+?src=\"(https:\/\/.*?\.googleusercontent\.com\/.*?)\".*?>/i", "$1", $picasa_img);
			$picasa_img_c1 = preg_replace("/<img(.+?)src=\"https:\/\/.*?\.googleusercontent\.com\/.*?\".*?>/i", "$1", $picasa_img);
			$picasa_img_c2 = preg_replace("/<img.+?src=\"https:\/\/.*?\.googleusercontent\.com\/.*?\"(.*?)>/i", "$1", $picasa_img);
			$picasa_url_new = urlencode(strrev(base64_encode($picasa_url)));
			$picasa_img_new = "<img" . $picasa_img_c1 . "src=\"$imagoxy_dir" . 
				"getimg.php?u=" . $picasa_url_new . "\"" . $picasa_img_c2 . ">";
			array_push($exchangeDest, $picasa_img_new);
		}
	}
	
	// ======== Conversion for Flickr ========
	
	$flickr_num = preg_match_all("/<img.+?src=\"http:\/\/.*?\.static\.flickr\.com\/.*?\".*?>/i", $text, $flickr_matches, PREG_PATTERN_ORDER);
	
	if ($flickr_num > 0) {
		foreach ($flickr_matches[0] as $flickr_img) {
			array_push($exchangeSource, $flickr_img);
			$flickr_url = preg_replace("/<img.+?src=\"(http:\/\/.*?\.static\.flickr\.com\/.*?)\".*?>/i", "$1", $flickr_img);
			$flickr_img_c1 = preg_replace("/<img(.+?)src=\"http:\/\/.*?\.static\.flickr\.com\/.*?\".*?>/i", "$1", $flickr_img);
			$flickr_img_c2 = preg_replace("/<img.+?src=\"http:\/\/.*?\.static\.flickr\.com\/.*?\"(.*?)>/i", "$1", $flickr_img);
			$flickr_url_new = urlencode(strrev(base64_encode($flickr_url)));
			$flickr_img_new = "<img" . $flickr_img_c1 . "src=\"$imagoxy_dir" . 
				"getimg.php?u=" . $flickr_url_new . "\"" . $flickr_img_c2 . ">";
			array_push($exchangeDest, $flickr_img_new);
		}
	}
	
	// ======== Add more conversion here ========
	
	// ======== Replace ========
	
	$text = str_replace($exchangeSource, $exchangeDest, $text);

	return $text;
}

add_filter('the_content', 'imagoxy_wp_convert');

add_filter('the_excerpt', 'imagoxy_wp_convert');

?>