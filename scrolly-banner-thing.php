<?php
/*
Plugin Name: Scrolly Banner Thing
Plugin URI: http://omgubuntu.co.uk
Description: Fade some massive images for some reason (with links!). 
Author: Niall Molloy
Version: 0.00004
Author URI: http://purplejam.co.uk
License: GPL v3
*/

if (!defined('WP_CONTENT_URL'))
      define('WP_CONTENT_URL', get_option('siteurl').'/wp-content');
if (!defined('WP_CONTENT_DIR'))
      define('WP_CONTENT_DIR', ABSPATH.'wp-content');
if (!defined('WP_PLUGIN_URL'))
      define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');
if (!defined('WP_PLUGIN_DIR'))
      define('WP_PLUGIN_DIR', WP_CONTENT_DIR.'/plugins');
      
$SBT_PLUGIN_NAME  = "Scrolly Banner Thing";
$SBT_WIDGET_TITLE = "Scrolly Banner Thing";

add_action("plugins_loaded", "scrolly_banner_thing_init");

function scrolly_banner_thing_init() {
	global $SBT_WIDGET_TITLE;

	// instruction to only load if it is not the admin area
	if (!is_admin()) {
		// register your script location, dependencies and version
		wp_register_script('banner', WP_PLUGIN_URL.'/scrolly-banner-thing/banner.js', array('jquery'), '1.3');
		// enqueue the script
		wp_enqueue_script('jquery');
		wp_enqueue_script('banner');
	}

	scrolly_banner_thing_setup ();
	register_sidebar_widget ($SBT_WIDGET_TITLE, 'scrolly_banner_thing_render');
	register_widget_control ($SBT_WIDGET_TITLE, 'scrolly_banner_thing_preferences');
}

function scrolly_banner_thing_render ($args) {
	global $SBT_PLUGIN_NAME;

	scrolly_banner_thing_setup ();
	$options = get_option ($SBT_PLUGIN_NAME);
	extract($args);
	$echo_widget = $before_widget;
	$echo_widget .= scrolly_banner_thing_get_content ();
	$echo_widget .= $after_widget;
	echo $echo_widget;
}

function scrolly_banner_thing_get_content () {
	global $SBT_PLUGIN_NAME;
	$options = get_option ($SBT_PLUGIN_NAME);	

	$content = "
		<div id=\"post-ads\">
			<a id=\"image-2\" href=\"{$options['sbt-post-3']}\"><img width=\"960\" height=\"200\" src=\"{$options['sbt-image-3']}\" alt=\"banner 3\"/></a>
			<a id=\"image-1\" href=\"{$options['sbt-post-2']}\"><img width=\"960\" height=\"200\" src=\"{$options['sbt-image-2']}\" alt=\"banner 2\" /></a>
			<a id=\"image-0\" href=\"{$options['sbt-post-1']}\" class=\"opaque\"><img width=\"960\" height=\"200\" src=\"{$options['sbt-image-1']}\" alt=\"banner 1\" /></a>
			<div id=\"bar-container\"><div id=\"bar\"></div></div>
			<p id=\"controls\"><span id=\"to-2\" class=\"a2\"></span><span id=\"to-1\" class=\"a1\"></span></p>
		</div>
	";

	return $content;
}

function scrolly_banner_thing_setup () {
	global $SBT_PLUGIN_NAME;

	$options = get_option ($SBT_PLUGIN_NAME);
	if (!is_array ($options) || empty ($options["title"])) {
		$options = array ("title" => " ",
				   "sbt-image-1" => "1",
				   "sbt-post-1"  => "1",
				   "sbt-image-2" => "2",
				   "sbt-post-2"  => "2",
				   "sbt-image-3" => "3",
				   "sbt-post-3"  => "3",
				   "sbt-titlebar-visible" => false
				  );
		update_option ($SBT_PLUGIN_NAME, $options);
    }
}

function scrolly_banner_thing_preferences () {
	global $SBT_PLUGIN_NAME;
	$options = get_option ($SBT_PLUGIN_NAME);

	if ($_POST["submit-settings"]) {
		$options["sbt-items"]   = (int)$_POST['sbt-items'];
		$options["sbt-image-1"] = $_POST['sbt-image-1'];
		$options["sbt-post-1"]  = $_POST['sbt-post-1'];
		$options["sbt-image-2"] = $_POST['sbt-image-2'];
		$options["sbt-post-2"]  = $_POST['sbt-post-2'];
		$options["sbt-image-3"] = $_POST['sbt-image-3'];
		$options["sbt-post-3"]  = $_POST['sbt-post-3'];
	}

	update_option($SBT_PLUGIN_NAME, $options);
?>

<p>
	<label for="sbt-image-1">Image URL 1:</label>
	<input type="text" id="sbt-image-1" name="sbt-image-1" value="<?php echo $options['sbt-image-1'];?>" />
</p>
<p>
	<label for="sbt-post-1">Post URL 1:</label>
	<input type="text" id="sbt-post-1" name="sbt-post-1" value="<?php echo $options['sbt-post-1'];?>" />
</p>
<p>
	<label for="sbt-image-2">Image URL 2:</label>
	<input type="text" id="sbt-image-2" name="sbt-image-2" value="<?php echo $options['sbt-image-2'];?>" />
</p>
<p>
	<label for="sbt-post-2">Post URL 2:</label>
	<input type="text" id="sbt-post-2" name="sbt-post-2" value="<?php echo $options['sbt-post-2'];?>" />
</p>
<p>
	<label for="sbt-image-3">Image URL 3:</label>
	<input type="text" id="sbt-image-3" name="sbt-image-3" value="<?php echo $options['sbt-image-3'];?>" />
</p>
<p>
	<label for="sbt-post-3">Post URL 3:</label>
	<input type="text" id="sbt-post-3" name="sbt-post-3" value="<?php echo $options['sbt-post-3'];?>" />
</p>
<input type="hidden" id="submit-settings" name="submit-settings" value="1" />

<?php
}
?>