<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label"># of Pics (optional)</div>
  <input type="text" id="pics_count" class="feed-them-social-admin-input" value="" />
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<?php 
if(is_plugin_active('fts-rotate/fts-rotate.php')) {
	include('../wp-content/plugins/fts-rotate/admin/fts-rotate-settings-fields.php');
}
?> 