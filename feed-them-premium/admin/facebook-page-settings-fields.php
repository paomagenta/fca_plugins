<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label"># of Posts (optional)</div>
  <input type="text" id="fb_page_post_count" class="feed-them-social-admin-input" value="" placeholder="5 is the default value" />
  <div class="clear"></div>
</div>
<!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label">Show Page Title</div>
  <select id="fb_page_title_option" class="feed-them-social-admin-input">
    <option selected="selected" value="yes">Yes</option>
    <option value="no">No</option>
  </select>
  <div class="clear"></div>
</div>
<!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label">Show Page Description</div>
  <select id="fb_page_description_option" class="feed-them-social-admin-input">
    <option selected="selected" value="yes">Yes</option>
    <option value="no">No</option>
  </select>
  <div class="clear"></div>
</div>
<!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label">Amount of words per post</div>
 	<input type="text" id="fb_page_word_count_option" class="feed-them-social-admin-input" value="" placeholder="45 is the default value"/>
  <div class="clear"></div>
</div>
<!--/feed-them-social-admin-input-wrap-->

<?php 
if(is_plugin_active('fts-rotate/fts-rotate.php')) {
	include('../wp-content/plugins/fts-rotate/admin/fts-rotate-settings-fields.php');
}
?>