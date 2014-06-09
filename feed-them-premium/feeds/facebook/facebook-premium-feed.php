<?php 
	extract( shortcode_atts( array(
		'id' => '',
		'posts' => '5',
		'posts_displayed' => '',
		'title' => '',
		'description' => '',
		'words' => '',
		'type' => '',
		'fts_rotate_feed' => 'no',
		'fts_rotate_poh' =>'true',
		'fts_rotate_speed' =>'200',
		'fts_rotate_fx' =>'fade',
		'fts_rotate_random' => 'no'
	), $atts ) );
	
	$custom_name = $posts_displayed;
	$fts_limiter = $posts;
	$fts_fb_id = $id;
	$access_token = '226916994002335|ks3AFvyAOckiTA1u_aDoI4HYuuw';
	$fts_fb_type = $type;
?>