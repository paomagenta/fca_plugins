var fb_page_title_option = ' title=' + jQuery("select#fb_page_title_option").val();
var fb_page_description_option = ' description=' + jQuery("select#fb_page_description_option").val();

var fb_page_post_count_final_check = jQuery("input#fb_page_post_count").val();
	if (fb_page_post_count_final_check !== '')	{
		 var fb_page_post_count_final = ' posts=' + jQuery("input#fb_page_post_count").val();
	}
	else	{
		var fb_page_post_count_final = ' posts=5';
}

var fb_page_word_count_option_check = jQuery("input#fb_page_word_count_option").val();
	if (fb_page_word_count_option_check !== '')	{
		var fb_page_word_count_option = ' words=' + jQuery("input#fb_page_word_count_option").val();
	}
	else	{
		var fb_page_word_count_option = ' words=45';
}

//Rotate Script
//if (final_fts_rotate_shortcode && jQuery("#"+ rotate_form_id + " input.fts_rotate_feed").is(':checked')){
//	var final_fb_page_shorcode = '[fts facebook page' + fb_page_id  + fb_page_post_count_final + fb_page_title_option + fb_page_description_option + ' type=page' + final_fts_rotate_shortcode + ']';			
//}
//else	{
	var final_fb_page_shorcode = '[fts facebook page' + fb_page_id  + fb_page_posts_displayed + fb_page_post_count_final + fb_page_title_option + fb_page_description_option + fb_page_word_count_option +  ' type=page]';
//}	