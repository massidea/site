$(document).ready(function(){
	recentcampaignsdiv  = '#campaign-list';
	recentcampaignslink = '#recent_campaigns_ajax_link';
    active = '#active_span';
    forthcoming = '#forthcoming_span';
    ended = '#ended_span';
    var status = 'active';
    campaignStatus = '#campaign-status';
	recentcampaignslink_orig = $(recentcampaignslink).html();
	var pagecount = 1;

	$(recentcampaignslink+' a').live('click', function(){
		pagecount = pagecount + 1;
		ajaxLoad_getRecentCampaigns(recentcampaignsdiv, pagecount, status, false);
	});

    $(active+' a').live('click', function(){
        pagecount = 1;
        status = 'active';
		ajaxLoad_getRecentCampaigns(recentcampaignsdiv, pagecount, status, true);
	});
    $(forthcoming+' a').live('click', function(){
        pagecount = 1;
        status = 'forthcoming';
		ajaxLoad_getRecentCampaigns(recentcampaignsdiv, pagecount, status, true);
	});
    $(ended+' a').live('click', function(){
        pagecount = 1;
        status = 'ended';
		ajaxLoad_getRecentCampaigns(recentcampaignsdiv, pagecount, status, true);
	});

	ajaxLoad_getRecentCampaigns(recentcampaignsdiv, pagecount, status, false);
});

function ajaxLoad_getRecentCampaigns(obj, offset, stat, empty, prepend){
	$.ajax({
		beforeSend: function(){
			$(recentcampaignslink).html(
				'<h3 style="vertical-align: middle;"><img src="'+url_ajaxloader+'" style="padding-right: 10px;" /> ' + 
				'Please wait...</h3>'
			);
            if (empty) {
                $(recentcampaignsdiv).html('');
            }
            if (stat == 'forthcoming') {
                $(campaignStatus).html(
                    '<span id="active_span"><a href="#" onclick="return false;">Active</a></span> |' +
                    ' <strong style="font-size: 14px">Forthcoming</strong> |' +
                    ' <span id="ended_span"><a href="#" onclick="return false;">Ended</a></span>'
                );
            } else if (stat == 'ended') {
                $(campaignStatus).html(
                    '<span id="active_span"><a href="#" onclick="return false;"><strong>Active</a></span> |' +
                    ' <span id="forthcoming_span"><a href="#" onclick="return false;">Forthcoming</a></span> |' +
                    ' <strong style="font-size: 14px">Ended</strong>'
                );
            } else {
                $(campaignStatus).html(
                    '<strong style="font-size: 14px">Active</strong> |' +
                    ' <span id="forthcoming_span"><a href="#" onclick="return false;">Forthcoming</a></span> |' +
                    ' <span id="ended_span"><a href="#" onclick="return false;">Ended</a></span>'
                );
            }
		},
		complete: function(){
            $(recentcampaignslink).html(recentcampaignslink_orig);
		},
		url: url_getrecentcampaigns+"/offset/"+offset+"/status/"+stat,
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