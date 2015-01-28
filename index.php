<?php
// header('Expires: '.gmdate('D, d M Y H:i:s', time() + 7200).' GMT');
// header('Cache-Control: no-cache, must-revalidate');
// $mt = filemtime($file_name);

// if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) &&
// strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $mt)
// {header('HTTP/1.1 304 Not Modified');
// die;
// }
$mt_str = gmdate("D, d M Y H:i:s ")."GMT";
header('Last-Modified: '.$mt_str);
// header("Vary: Accept-Encoding");
// header("Accept-Encoding:gzip,deflate,sdch");
?>
<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define('WP_USE_THEMES', true);

/** Loads the WordPress Environment and Template */
require('./wp-blog-header.php');
