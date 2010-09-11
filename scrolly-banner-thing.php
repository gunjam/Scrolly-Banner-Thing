<?php
/*
Plugin Name: Scrolly Banner Thing
Plugin URI: http://omgubuntu.co.uk
Description: Fade some massive images for some reason (with links!). 
Author: Niall Molloy
Version: 0.5
Author URI: http://purplejam.co.uk
License: GPL v3
*/
      
$SBT_PLUGIN_NAME  = "Scrolly Banner Thing";
$SBT_WIDGET_TITLE = "Scrolly Banner Thing";

add_action("plugins_loaded", "scrolly_banner_thing_init");

function scrolly_banner_thing_init() {
	global $SBT_WIDGET_TITLE;

	if (!is_admin()) {
		wp_register_script('banner', '/'.PLUGINDIR.'/scrolly-banner-thing/banner.js', array('jquery'), '1.4');
		wp_enqueue_script('jquery');
		wp_enqueue_script('banner');
	}
	else {
		wp_register_script('form', '/'.PLUGINDIR.'/scrolly-banner-thing/form.js', array('jquery'), '1.3');
		wp_enqueue_script('jquery');
		wp_enqueue_script('form');
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

	echo $before_widget.scrolly_banner_thing_get_content().$after_widget;
}

function scrolly_banner_thing_get_content () {
	global $SBT_PLUGIN_NAME;
	$options = get_option ($SBT_PLUGIN_NAME);	

	$content = "<div id=\"post-ads\">";

	foreach ( $options as $k => $v ) {
		$content .= "<a id=\"$k\" ";
		if ( $k == "banner-0" ) $content .= "class=\"opaque\" ";
		$content .= "href=\"{$v['post']}\"><img width=\"960\" height=\"200\" src=\"{$v['image']}\" alt=\"banner\"/></a>";
	}

	$previous = count($options)-1;

	return $content .= "
		<div id=\"bar-container\"><div id=\"bar\"></div></div>
		<p id=\"controls\"><span id=\"to-$previous\" class=\"$previous\"></span><span id=\"to-1\" class=\"a1\"></span></p>
		</div>
	";
}

function scrolly_banner_thing_setup () {
	global $SBT_PLUGIN_NAME;

	$options = get_option ($SBT_PLUGIN_NAME);
	if ( !is_array ($options) || !count($options) ) {
		$options = array ("banner-0" => array("image" => "", "post" => ""),
				   "banner-1" => array("image" => "", "post" => ""),
				   "banner-2" => array("image" => "", "post" => "")
				  );
		update_option ($SBT_PLUGIN_NAME, $options);
	}
}

function scrolly_banner_thing_preferences () {

	global $SBT_PLUGIN_NAME;

	if (isset($_POST['submit-settings'])) {
		$options = array();
		foreach ( $_POST as $k => $v ) {
			if ( substr($k,0,6) == 'banner' && $v ) {
				$options[$k] = $v;
			}
		}
		update_option ( $SBT_PLUGIN_NAME, $options );
	}
	else {
		$options = get_option ($SBT_PLUGIN_NAME);
	}

	$form = "<div id=\"sbt-container\"><input type=\"hidden\" name=\"submit-settings\" value=\"1\" />";	
	
	for ( $i = 0; $i < count($options); $i++ ) {
		$form .= "
			<div id=\"sbt-$i\" style=\"margin:0 0 0.5em;background:#efefef;\"><p style=\"background:#f5f5f5;\"><strong>Banner ".($i+1)."</strong></p><p><label for=\"sbt-image-$i\">Image URL:</label>
			<input type=\"text\" id=\"sbt-image-$i\" name=\"banner-{$i}[image]\" value=\"" . $options["banner-$i"]['image'] . "\" /></p>
			<p><label for=\"sbt-post-$i\">Post URL:</label>
			<input type=\"text\" id=\"sbt-post-$i\" name=\"banner-{$i}[post]\" value=\"" . $options["banner-$i"]['post'] . "\" /></p></div>
		";
	}

	echo $form."</div><p><a id=\"sbt-add\" title=\"Add another banner fieldset\" href=\"#sbt-add\" onClick=\"addFormField()\">Add</a> /
	<a id=\"sbt-rem\" title=\"Remove last banner fieldset\" href=\"#sbt-rem\" onClick=\"removeFormField()\">Remove</a> a banner</p>";
}
?>
