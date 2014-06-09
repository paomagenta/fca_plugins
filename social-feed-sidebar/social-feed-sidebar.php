<?php
/*
Plugin Name: FB/Twitter/IG Sidebar
Plugin URI: 
Description: FB/Twitter/IG Sidebar
Version: 1.0
Author: Patricia Masigla
Author URI: http://pbmasigla.github.io
License: UMD
*/

class fb_twitter_ig_sidebar extends WP_Widget {

	// constructor
	function fb_twitter_ig_sidebar() {
		parent::WP_Widget(false, $name = __('FCA FB/Twitter/IG Sidebar', 'wp_widget_plugin') );
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

	   // echo '<div class="fb_widget">'.do_shortcode('[recent-facebook-posts]').'</div>';
	   // echo'<iframe class="fb_widget" height="300px" src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Ffcaatumd&amp;width=400&amp;height=590&amp;colorscheme=light&amp;show_faces=true&amp;header=true&amp;stream=true&amp;show_border=true&amp;appId=499134553545897" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:400px; height:300x; border-bottom:1px solid gray" allowTransparency="true"></iframe>';
	   echo "<div class='facebook_div'><h2>Latest FB News</h2>";
	   echo do_shortcode('[fts facebook page id=fcaatumd posts_displayed=page_only posts=1 title=no description=no words=15 type=page]');
	   echo '</div>';
	   echo "<div class='twitter_div'><h2>Latest Tweets</h2>"; 
	   echo do_shortcode('[fts twitter twitter_name=fca_umcp tweets_count=1]');
	   echo '</div>';
	   echo "<div class='instagram_div'><h2>Latest on Instagram</h2>";
	   echo do_shortcode('[fts instagram instagram_id=365186458 pics_count=1]');
	   echo '</div>';
	   echo $after_widget;
		}
}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("fb_twitter_ig_sidebar");'));
?>