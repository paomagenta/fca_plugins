<?php
/*
Plugin Name: Feed Them Social Premium
Plugin URI: http://slickremix.com/
Description: This is the Premium Extension for the Free version of Feed Them Social.
Version: 1.2.4
Author: SlickRemix
Author URI: http://slickremix.com/
Requires at least: wordpress 3.4.0
Tested up to: wordpress 3.8.1
Stable tag: 1.2.4

 * @package    			Feed Them Social Premium
 * @category   			Core
 * @author				SlickRemix
 * @copyright  			Copyright (c) 2012-2014 SlickRemix

If you need support or want to tell us thanks please contact us at support@slickremix.com or use our support forum on slickremix.com

This is the main file for building the plugin into wordpress
*/

// Licensing and update code
add_action( 'plugins_loaded', 'init_fb_premium_plugin' );
function init_fb_premium_plugin() {
	if ( !class_exists( 'licence_manager' ) ) {
	require( 'licence-manager/licence-manager.php' );
	}
	$licence_manager = new licence_manager( __FILE__, 'fb_premium', "http://www.slickremix.com/" );
	
	if ( !$licence_manager->is_licence_active() ) {
	return false;
	}

	  // Include core files and classes
	  include( 'includes/feed-them-premium-functions.php' );
	  
	  include( 'feeds/youtube/youtube-feed.php' );
	  
	  include( 'feeds/pinterest/pinterest.php' );
	  
	  // Include Leave feedback, Get support and Plugin info links to plugin activation and update page.
	  add_filter("plugin_row_meta", "fts_add_leave_feedback_link", 10, 2);
	  
		  function fts_add_leave_feedback_link( $links, $file ) {
			  if ( $file === plugin_basename( __FILE__ ) ) {
				  $links['feedback'] = '<a href="http://wordpress.org/support/view/plugin-reviews/feed-them-social" target="_blank">' . __( 'Leave feedback', 'gd_quicksetup' ) . '</a>';
				  $links['support']  = '<a href="http://www.slickremix.com/support-forum/wordpress-plugins-group3/feed-them-social-forum9/" target="_blank">' . __( 'Get support', 'gd_quicksetup' ) . '</a>';
				  $links['plugininfo']  = '<a href="plugin-install.php?tab=plugin-information&plugin=feed-them-premium&section=changelog&TB_iframe=true&width=640&height=423" class="thickbox">' . __( 'Plugin info', 'gd_quicksetup' ) . '</a>';
			  }
			  return $links;
		  }
	  // Include our own Settings link to plugin activation and update page.
	  add_filter("plugin_action_links_".plugin_basename(__FILE__), "fts_plugin_actions", 10, 4);
	  
		  function fts_plugin_actions( $actions, $plugin_file, $plugin_data, $context ) {
			  array_unshift($actions, "<a href=\"".menu_page_url('feed-them-settings-page', false)."\">".__("Settings")."</a>");
			  return $actions;
	  }
}
?>