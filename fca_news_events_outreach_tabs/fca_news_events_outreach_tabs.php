<?php
/*
Plugin Name: FCA News/Events/Outreach Tabs
Plugin URI: 
Description: FCA News/Events/Outreach Tabs
Version: 1.0
Author: Patricia Masigla
Author URI: http://pbmasigla.github.io
License: UMD
*/

class fca_news_events_outreach_tabs extends WP_Widget {

	// constructor
	function fca_news_events_outreach_tabs() {
		parent::WP_Widget(false, $name = __('FCA News/Events/Outreach Tabs', 'wp_widget_plugin') );
	}

	// widget form creation
	function form($instance) {	
		// Check values
		if( $instance) {
		     $title = esc_attr($instance['title']);
		     $textarea = esc_textarea($instance['textarea']);
		} else {
		     $title = '';
		     $textarea = '';
		}
		?>

		<?php
	}

	// widget update
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
	     $instance['title'] = strip_tags($new_instance['title']);
	     $instance['textarea'] = strip_tags($new_instance['textarea']);
	     return $instance;
	}

	// widget display
	function widget($args, $instance) {
		extract( $args );
	   
	   $title = apply_filters('widget_title', $instance['title']);
	   $textarea = $instance['textarea'];
	   echo $before_widget;
	   // Display the widget

	   echo '<div class="fca_news_events_outreach_tabs_div">
	   		<ul>
   				<li class="active" id="news"><a><span>News</span></a></li>
   				<li class="" id="events"><a><span>Events</span></a></li>
   				<li class="" id="outreach"><a><span>Outreach</span></a></li>
			</ul></div>';
	   echo $after_widget;
		}
}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("fca_news_events_outreach_tabs");'));
?>