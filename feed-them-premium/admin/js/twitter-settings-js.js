var tweets_count = jQuery("input#tweets_count").val();

if (tweets_count !== '')	{
		 var tweets_count_final = ' tweets_count=' + jQuery("input#tweets_count").val();
	}
	else	{
		var tweets_count_final = ' tweets_count=5';
}


//Rotate Script
//if (final_fts_rotate_shortcode && jQuery("#"+ rotate_form_id + " input.fts_rotate_feed").is(':checked')){
//	var final_twitter_shorcode = '[fts twitter' + twitter_name  + tweets_count_final + final_fts_rotate_shortcode + ']';			
//}
//else	{
	var final_twitter_shorcode = '[fts twitter' + twitter_name  + tweets_count_final + ']';
//}	