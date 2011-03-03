<?php

/**
 * Imagoxy 0.54 / 2011-03-02
 * 
 * Imagoxy is a tiny PHP toolkit. It downloads pictures from remote server to local server and relocate
 * corresponding http requests to the local one. It is used to access pictures when the remote server 
 * is banned or slow from the network of clients.
 * 
 * Licensed under a BSD license.
 * 
 * Author      : Jian Lin <lj@linjian.org>
 * Author Blog : http://blog.linjian.org/
 * Project Web : https://sourceforge.net/projects/imagoxy/
 * 
 **/

	// ======== Configure, MODIFY HERE FIRST ! ========
	
	// Do NOT omit the '/' after the dir name.
	$work_dir = 'http://www.example.com/imagoxy/';
	// Make sure the dir is existed and writable. It is not checked for performance.
	$cache_dir = 'cache/';
	// Relocate to this URL when access control denied.
	$error_file = $work_dir . 'error.png';
	// Legal HTTP_REFERER prefix.
	$reffer_list = array('http://www.example.com/', 'http://example.com/');
	// Whether to check HTTP_REFERER before downloading new picutre.
	$check_reffer_before_download = true;
	// Whether to check HTTP_REFERER before showing downloaded picutre.
	$check_reffer_before_show = false;
	// Legal file URL patterns (regular expression). They meet 'OR' logic.
	$legal_pattern = array('http://.*\.ggpht\.com/.*\.(png|jpe|jpeg|jpg|gif|bmp|ico|tiff|tif|svg|svgz)(\?.*)?$',
		'https://.*\.googleusercontent\.com/.*\.(png|jpe|jpeg|jpg|gif|bmp|ico|tiff|tif|svg|svgz)(\?.*)?$',
		'http://.*\.static\.flickr\.com/.*\.(png|jpe|jpeg|jpg|gif|bmp|ico|tiff|tif|svg|svgz)(\?.*)?$');

	// ======== Access control ========
	
	$reffer = $_SERVER['HTTP_REFERER'];
	$reffer_legel = 0;
	if ($reffer) {
		while (list($reffer_domain, $reffer_subarray) = each($reffer_list)) {
			if (eregi($reffer_subarray, $reffer)) {
				$reffer_legel = 1;
				break;
			}
		}
	}
	
	if ($check_reffer_before_show && !$reffer_legel)
	{
		header("Location: $error_file");
	}
	else
	{
		// ======== Initialization ========
		
		// Query string 's' for direct URL.
		if ($_GET['s']) {
			$url = $_GET['s'];
		// Query string 'u' for base64_encoded/reversed/url_encoded URL. [ To pass the China GFW :) ]
		} else if ($_GET['u']) {
			$url = base64_decode(strrev(urldecode($_GET['u'])));
		} else {
			$url = '';
		}
		
		if (stripos($url, 'http://') !== 0 && stripos($url, 'https://') !== 0) {
			$url = 'http://' . $url;
		}
		
		$pattern_legel = 0;
		foreach ($legal_pattern as $pattern) {
			if (eregi($pattern, $url)) {
				$pattern_legel = 1;
				break;
			}
		}
		
		if (!$pattern_legel) {
			header("Location: $error_file");
		} else {
			$file_name_base = basename($url);
			$file_name_bases = explode('?' , $file_name_base);
			$file_name_base = $file_name_bases[0];
			
			$file_name_url = $cache_dir . md5($url) . '-' . $file_name_base;
			$file_name_local = urldecode($file_name_url);
			$file_downloaded = file_exists($file_name_local);
			
			// ======== Download ========
			
			if ($file_downloaded) {
				$relocation_file = $work_dir . $file_name_url;
			} else if ($check_reffer_before_download && !$reffer_legel) {
				$relocation_file = $error_file;
			} else {
				$remote_file = fopen($url, 'rb');
				if ($remote_file) {
					$local_file = fopen($file_name_local, 'wb');
					if ($local_file)
						while (!feof($remote_file)) {
							fwrite($local_file, fread($remote_file, 1024 * 8), 1024 * 8);
					}
				}
				if ($remote_file) {
					fclose($remote_file);
				}
				if ($local_file) {
					fclose($local_file);
				}
				$relocation_file = $work_dir . $file_name_url;
			}
			
			// ======== Relocation ========
			
			header("Location: $relocation_file");
		}
	}

?>