$(document).ready(function(){
	recentcampaignsdiv  = '#campaign-list';
	recentcampaignslink = '#recent_campaigns_ajax_link';
    active = '#active';
    forthcoming = '#forthcoming';
    ended = '#ended';
    status = 'active';
    campaignStatus = '#campaign-status';
	recentcampaignslink_orig = $(recentcampaignslink).html();
	pagecount = 1;
	previousresult = "";
	refreshTime = 30000;

	$(recentcampaignslink+' a').live('click', function(){
		pagecount = pagecount + 1;
		ajaxLoad_getRecentCampaigns(recentcampaignsdiv, pagecount, status, false);
	});

    // Not working in IE?
    $(active).live('click', function(){
        pagecount = 1;
        status = 'active';
		ajaxLoad_getRecentCampaigns(recentcampaignsdiv, pagecount, status, true);
	});
    $(forthcoming).live('click', function(){
        pagecount = 1;
        status = 'forthcoming';
		ajaxLoad_getRecentCampaigns(recentcampaignsdiv, pagecount, status, true);
	});
    $(ended).live('click', function(){
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
            if (stat === 'forthcoming') {
                $(campaignStatus).html(
                    '<a href="#" id="active" onclick="return false;">Active</a> |' +
                    ' <strong style="font-size: 14px">Forthcoming</strong> |' +
                    ' <a href="#" id="ended" onclick="return false;">Ended</a>'
                );
            } else if (stat === 'ended') {
                $(campaignStatus).html(
                    '<a href="#" id="active" onclick="return false;"><strong>Active</a> |' +
                    ' <a href="#" id="forthcoming" onclick="return false;">Forthcoming</a> |' +
                    ' <strong style="font-size: 14px">Ended</strong>'
                );
            } else {
                $(campaignStatus).html(
                    '<strong style="font-size: 14px">Active</strong> |' +
                    ' <a href="#" id="forthcoming" onclick="return false;">Forthcoming</a> |' +
                    ' <a href="#" id="ended" onclick="return false;">Ended</a>'
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