var pics_count = jQuery("input#pics_count").val();


	if (pics_count !== '')	{
		 var pics_count_final = ' pics_count=' + jQuery("input#pics_count").val();
	}
	else	{
		var pics_count_final = ' pics_count=5';
}

//Rotate Script
//if (final_fts_rotate_shortcode && jQuery("#"+ rotate_form_id + " input.fts_rotate_feed").is(':checked')){
//				var final_instagram_shorcode = '[fts instagram' + instagram_id + pics_count_final + final_fts_rotate_shortcode +']'
//}
//else	{
		var final_instagram_shorcode = '[fts instagram' + instagram_id + pics_count_final + ']';
//}		