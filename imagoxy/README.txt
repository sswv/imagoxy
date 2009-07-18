README for Imagoxy 0.5
==============================

Imagoxy ("image proxy") is a tiny PHP toolkit. It downloads pictures from remote server to local server and relocate corresponding http requests to the local one. It is used to access pictures when the remote server is banned or slow from the network of clients (e.g. Download pictures from Picasa on an unbanned US server and tranfer them to China's viewers where Picasa is banned sometimes).

Licensed under a BSD license.

Install:

1. Modify Imagoxy 'getimg.php' file:
	* Set '$work_dir' to the location you wish Imagoxy works at.
	* Set '$cache_dir' to the location you wish files downloaded to. (default is OK in most cases)
	* Set '$error_file' as the file relocated to when access control denied. (default is OK in most cases)
	* Set '$reffer_list' as the legal HTTP_REFERER prefix list.
	* Set '$legal_type' as the legal file extension name list. (default is OK in most cases)
2. Update the 'imagoxy' directory to your '$work_dir' and make the '$cache_dir' writable.
3. Configure and deploy Imagoxy front-ends such as Imagoxy-WP.

Usage:

1. [ http://www.example.com/imagoxy/getimg.php?s={original URL} ] In this format (we called 'Imagoxy URL'), the http request will be relocated to a file on local server which is downloaded from the original URL. You can embed the Imagoxy URL into your <img src="..."> label. 'http://' in original URL is omissible and the other protocols are not allowed.
2. [ http://www.example.com/imagoxy/getimg.php?u={encoded URL} ] Also does downloading and relocation. However, you should base64-encode, reverse and then url encode the original URL. In PHP, these are done by 'urlencode(strrev(base64_encode($original_url)))'. This encoding method can pass the URL filter in certain regions.

Test:

You can write a html page with an Imagoxy URL in <img src="..."> label. Open it locally, you should see the '$error_file'; after uploading it to the server with the URL prefix in '$reffer_list', you will see the relocated picture correctly.

Enjoy it!

Jian Lin <lj@linjian.org>
2009-07-17
