README for Imagoxy-WP 0.54
==============================

Imagoxy-WP is a wordpress front-end for Imagoxy. Imagoxy downloads pictures from remote server to local server and relocate corresponding http requests to the local one. It is used to access pictures when the remote server is banned or slow from the network of clients. Imagoxy-WP now converts Picasa and Flickr URLs to local Imagoxy URLs, and you can also add your customed conversion.

Licensed under a BSD license.

Install:

1. Download Imagoxy. Imagoxy is available at https://sourceforge.net/projects/imagoxy/.
2. Configure and deploy Imagoxy on your server as Imagoxy's README file described.
3. Modify Imagoxy-WP 'imagoxy-wp.php' file:
	* Set '$imagoxy_dir' to your Imagoxy location, which is the same as '$work_dir' in Imagoxy.
4. Upload Imagoxy-WP php file or the whole directory to your wordpress '/wp-content/plugins' directory.
5. Enable it in the dashboard.

After that, You will see:
	<img src="http://lhX.ggpht.com/_WWW/XXX/YYY/ZZZ/sMMM/IMG_NNN.JPG" />
in your blog posts now becomes:
	<img src="http://www.example.com/imagoxy/getimg.php?u=AABBCCDDEEFFGGHH" />
Since pictures are downloaded from your own server, it won't be banned then.

Enjoy it!

Jian Lin <lj@linjian.org>
2011-03-02
