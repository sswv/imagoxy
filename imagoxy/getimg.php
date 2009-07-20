<?php

/**
 * Imagoxy 0.51 / 2009-07-20
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
	// Legal file extension name.
	$legal_type = array('png', 'jpe', 'jpeg', 'jpg', 'gif', 'bmp', 'ico', 'tiff', 'tif', 'svg', 'svgz');

	// ======== Access control ========
	
	$reffer = $_SERVER['HTTP_REFERER'];
	$reffer_legel = 0;
	if ($reffer) {
		while (list($reffer_domain, $reffer_subarray) = each($reffer_list)) {
			if (ereg($reffer_subarray, "$reffer")) {
				$reffer_legel = 1;
				break;
			}
		}
	}
	
	if (!$reffer_legel)
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
		
		if (strtolower(substr($url, 0, 7)) != 'http://') {
			$url = 'http://' . $url;
		}
		
		$extends = explode('.' , $url);
		$extends_va = count($extends) - 1;
		$extend = $extends[$extends_va];
		$extends = explode('?' , $extend);
		$extend = strtolower($extends[0]);
		
		if (!in_array($extend, $legal_type)) {
			header("Location: $error_file");
		} else {
			$file_name_base = basename($url);
			$file_name_bases = explode('?' , $file_name_base);
			$file_name_base = $file_name_bases[0];
			
			$file_name_url = $cache_dir . md5($url) . '-' . $file_name_base;
			$file_name_local = urldecode($file_name_url);
			
			if (!file_exists($file_name_local)) {
				
				// ======== Download ========
				
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
			}
			
			// ======== Relocation ========
			
			$relocation_file = $work_dir . $file_name_url;
			header("Location: $relocation_file");
		}
	}

?>