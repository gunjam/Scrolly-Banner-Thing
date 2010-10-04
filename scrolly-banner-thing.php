<?php
/*
Plugin Name: Scrolly Banner Thing
Plugin URI: http://github.com/gunjam/Scrolly-Banner-Thing
Description: Fade some massive images for some reason (with links!). 
Author: Niall Molloy
Version: 0.8
Author URI: http://purplejam.co.uk
License: GPL v3
*/
      
$SBT_WIDGET_TITLE = $SBT_PLUGIN_NAME  = "Scrolly Banner Thing";

add_action("plugins_loaded", "scrolly_banner_thing_init");

function scrolly_banner_thing_init() {
	global $SBT_WIDGET_TITLE;

	if (!is_admin()) {
		wp_register_script('banner', '/'.PLUGINDIR.'/scrolly-banner-thing/banner.js', array('jquery'), '1.5');
		wp_enqueue_script('jquery');
		wp_enqueue_script('banner');

		wp_register_style('banner-style', '/'.PLUGINDIR.'/scrolly-banner-thing/banner.css', false, '0.4');
		wp_enqueue_style('banner-style');
	}
	else {
		wp_register_script('form', '/'.PLUGINDIR.'/scrolly-banner-thing/form.js', array('jquery'), '1.5');
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('form');

		wp_register_style('form-style', '/'.PLUGINDIR.'/scrolly-banner-thing/form.css', false, '0.2');
		wp_enqueue_style('form-style');
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
		$chooser .= "<li class=\"to-" .  substr($k,7);
		if ( $k == "banner-0" ) $chooser .= " displaying";
		$chooser .= "\"></li>";
	}

	$previous = count($options)-1;

	return $content .= "
		<div id=\"bar-container\"><div id=\"bar\"></div></div>
		<div id=\"controls\"><ul id=\"adjuster\"><li id=\"a$previous\" class=\"to-$previous\"></li><li id=\"a1\" class=\"to-1\"></li></ul><ul id=\"chooser\">$chooser</ul></div>
		</div>
	";
}

function scrolly_banner_thing_setup () {
	global $SBT_PLUGIN_NAME;

	$options = get_option ($SBT_PLUGIN_NAME);
	if ( !is_array ($options) || !count($options) ) {
		$options = array ("banner-0" => array("image" => '/'.PLUGINDIR.'/scrolly-banner-thing/default.jpg', "post" => "/about"));
		update_option ($SBT_PLUGIN_NAME, $options);
	}
}

function scrolly_banner_thing_preferences () {

	global $SBT_PLUGIN_NAME;

	if (isset($_POST['submit-settings'])) {
		$options = array();
		$i = 0;
		foreach ( $_POST as $k => $v ) {
			if ( substr($k,0,6) == 'banner' && $v ) {
				$options["banner-$i"] = $v;
				$i++;
			}
		}
		update_option ( $SBT_PLUGIN_NAME, $options );
	}
	else {
		$options = get_option ($SBT_PLUGIN_NAME);
	}

	$form = "<ul id=\"sbt-container\">";	
	
	for ( $i = 0; $i < count($options); $i++ ) {
		$form .= "
			<li id=\"sbt-$i\"><h3>Banner ".($i+1)."<span onClick=\"removeFormField($i)\">&#215;</span></h3><p><label for=\"sbt-image-$i\">Image URL:</label>
			<input type=\"text\" id=\"sbt-image-$i\" name=\"banner-{$i}[image]\" value=\"" . $options["banner-$i"]['image'] . "\" /></p>
			<p><label for=\"sbt-post-$i\">Post URL:</label>
			<input type=\"text\" id=\"sbt-post-$i\" name=\"banner-{$i}[post]\" value=\"" . $options["banner-$i"]['post'] . "\" /></p></li>
		";
	}

	echo $form."</ul><p><a id=\"sbt-add\" title=\"Add another banner fieldset\" href=\"#sbt-add\" onClick=\"addFormField()\">Add</a> a banner</p><input type=\"hidden\" name=\"submit-settings\" value=\"1\" />";
}
?>
