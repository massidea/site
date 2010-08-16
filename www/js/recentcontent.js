$(document).ready(function(){
	recentpostsdiv  = '#recent_posts_ajax';
	recentpostslink = '#recent_posts_ajax_link';
	recentpostslink_orig = $(recentpostslink).html();
	pagecount = 1;
	previousresult = "";
	refreshTime = 30000;

	$(recentpostslink+' a').live('click', function(){
		pagecount = pagecount + 1;
		ajaxLoad_getRecentPosts(recentpostsdiv, pagecount);
	});

	ajaxLoad_checkRecentPosts();
});

function ajaxLoad_getRecentPosts(obj, offset, prepend){
	$.ajax({
		beforeSend: function(){
			$(recentpostslink).html(
				'<h3 style="vertical-align: middle;"><img src="'+url_ajaxloader+'" style="padding-right: 10px;" /> ' + 
				'Please wait...</h3>'
			);
		},
		complete: function(){
			$(recentpostslink).html(recentpostslink_orig);
		},
		url: url_getrecentcontent+"/offset/"+offset,
		success: function(result){
			if(prepend == 1) {
				$(obj).prepend(result);
			} else {
				$(obj).append(result);
			}
			$('.user_content_row_new').hide();
			removeDupes_recentPosts(recentpostsdiv);
			$('.user_content_row_new').removeClass('user_content_row_new');
			generateTranslationLinks();
			$('.user_content_row').not('.user_content_row_hidden').fadeIn('slow');
		}
	});
};

function generateTranslationLinks()
{
	$('.summary_translatelink').each(function(){
		var metadata = $.parseJSON($(this).children('.summary_translatelink_meta').html());
		var textObject = $(this).children('.summary_translatelink_text');
		var isOriginal = $(this).parents('.user_content_row').hasClass('user_content_row_hidden');

		if(isOriginal) {
			var textLinkTagInner = 'Show translated';
			var textLinkDesc = ', original language: ';
		} else if(!isOriginal) {
			var textLinkTagInner = 'Show original';
			var textLinkDesc = ', translated from ';
		}
		
		var textLinkTag = '<a href="#" onclick="toggleTranslation(\''+metadata.id+'\'); return false;">'+textLinkTagInner+'</a>';
		var textLink = '[' + textLinkTag + textLinkDesc + metadata.language_name + ']';
		$(textObject).html(textLink);
	});
}

function removeDupes_recentPosts(obj){
	$(recentpostsdiv + ' [id]').each(function(){
		var ids = $('[id='+this.id+']');
		if(ids.length > 1 && ids[0]!=this){
			console.warn('Multiple IDs #'+this.id);
			$(this).remove();
		}
	});
}

function ajaxLoad_checkRecentPosts(){
	$.ajax({
		url: url_checkrecentcontent+"/check/",
		success: function(result){
			if(result != previousresult) {
				ajaxLoad_getRecentPosts(recentpostsdiv, 1, 1);
			}
			previousresult = result;
			setTimeout("ajaxLoad_checkRecentPosts()", refreshTime);
		}
	});
}

function toggleTranslation(id)
{
	//alert(id);
	translatedId = '#postid_'+id;
	originalId = '#hidden_postid_'+id;
	//alert($(translatedId).css('display'));
	if( $(translatedId).css('display') == 'block')
	{
		//alert(translatedId);
		$(translatedId).hide();
		$(originalId).show();
	}
	else if( $(translatedId).css('display') == 'none')
	{
		//alert(originalId);
		$(originalId).hide();
		$(translatedId).show();
	}
}
