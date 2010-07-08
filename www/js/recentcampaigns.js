$(document).ready(function(){
	recentcampaignsdiv  = '#campaign-list';
	recentcampaignslink = '#recent_campaigns_ajax_link';
	recentcampaignslink_orig = $(recentcampaignslink).html();
	pagecount = 1;
	previousresult = "";
	refreshTime = 30000;

	$(recentcampaignslink+' a').live('click', function(){
		pagecount = pagecount + 1;
		ajaxLoad_getRecentCampaigns(recentcampaignsdiv, pagecount);
	});

	ajaxLoad_getRecentCampaigns(recentcampaignsdiv, pagecount);
});

function ajaxLoad_getRecentCampaigns(obj, offset, prepend){
	$.ajax({
		beforeSend: function(){
			$(recentcampaignslink).html(
				'<h3 style="vertical-align: middle;"><img src="'+url_ajaxloader+'" style="padding-right: 10px;" /> ' + 
				'Please wait...</h3>'
			);
		},
		complete: function(){
			$(recentcampaignslink).html(recentcampaignslink_orig);
		},
		url: url_getrecentcampaigns+"/offset/"+offset,
		success: function(result){
			if(prepend == 1) {
				$(obj).prepend(result);
			} else {
				$(obj).append(result);
			}
			removeDupes_recentCampaigns(recentcampaignsdiv);
		}
	});
};

function removeDupes_recentCampaigns(obj){
	$(recentcampaignsdiv + ' [id]').each(function(){
		var ids = $('[id='+this.id+']');
		if(ids.length > 1 && ids[0]!=this){
			console.warn('Multiple IDs #'+this.id);
			$(this).remove();
		}
	});
}