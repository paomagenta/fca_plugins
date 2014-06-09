<div class="feed-them-social-admin-input-wrap pinterest_name">
  <div class="feed-them-social-admin-input-label">Pinterest Username (required)</div>
  <input type="text" id="pinterest_name" class="feed-them-social-admin-input" value="" />
  <div class="clear"></div>
</div>
<!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label"># of Boards</div>
  <input type="text" id="boards_count" class="feed-them-social-admin-input" placeholder="6 is the default value" value="" />
  <div class="clear"></div>
</div>
<!--/feed-them-social-admin-input-wrap-->

<?php 
if(is_plugin_active('fts-rotate/fts-rotate.php')) {
	include('../wp-content/plugins/fts-rotate/admin/fts-rotate-settings-fields.php');
}
?>

   <input type="button" class="feed-them-social-admin-submit-btn" value="Generate Pinterest Shortcode" onclick="updateTextArea_pinterest();" tabindex="4" style="margin-right:1em;" />
      <div class="feed-them-social-admin-input-wrap final-shortcode-textarea">
      
      	<h4>Copy the ShortCode below and paste it on a page or post that you want to display your feed.</h4>
      
        <div class="feed-them-social-admin-input-label">Pinterest Feed Shortcode</div>
        <input class="copyme pinterest-final-shortcode feed-them-social-admin-input" value="" />
    <div class="clear"></div>
      </div><!--/feed-them-social-admin-input-wrap-->