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
                    '<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">' +
                    '<li id="active_span" class="ui-state-default ui-corner-top"><a href="#" onclick="return false;">Active</a></li>' +
                    '<li id="forthcoming_span" class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#" onclick="return false;">Forthcoming</a></li>' +
                    '<li id="ended_span" class="ui-state-default ui-corner-top"><a href="#" onclick="return false;">Ended</a></li>' +
                    '</ul>'
                );
            } else if (stat == 'ended') {
                $(campaignStatus).html(
                    '<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">' +
                    '<li id="active_span" class="ui-state-default ui-corner-top"><a href="#" onclick="return false;">Active</a></li>' +
                    '<li id="forthcoming_span" class="ui-state-default ui-corner-top"><a href="#" onclick="return false;">Forthcoming</a></li>' +
                    '<li id="ended_span" class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#" onclick="return false;">Ended</a></li>' +
                    '</ul>'
                );
            } else {
                $(campaignStatus).html(
                    '<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">' +
                    '<li id="active_span" class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#" onclick="return false;">Active</a></li>' +
                    '<li id="forthcoming_span" class="ui-state-default ui-corner-top"><a href="#" onclick="return false;">Forthcoming</a></li>' +
                    '<li id="ended_span" class="ui-state-default ui-corner-top"><a href="#" onclick="return false;">Ended</a></li>' +
                    '</ul>'
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