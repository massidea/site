$(document).ready(function(){
	recentgroupsdiv  = '#group-list';
	recentgroupslink = '#recent_group_ajax_link';
	recentgroupslink_orig = $(recentgroupslink).html();
	pagecount = 1;
	previousresult = "";
	refreshTime = 30000;

	$(recentgroupslink+' a').live('click', function(){
		pagecount = pagecount + 1;
		ajaxLoad_getRecentGroups(recentgroupsdiv, pagecount);
	});

	ajaxLoad_getRecentGroups(recentgroupsdiv, pagecount);
});

function ajaxLoad_getRecentGroups(obj, offset, prepend){
	$.ajax({
		beforeSend: function(){
			$(recentgroupslink).html(
				'<h3 style="vertical-align: middle;"><img src="'+url_ajaxloader+'" style="padding-right: 10px;" /> ' + 
				'Please wait...</h3>'
			);
		},
		complete: function(){
			$(recentgroupslink).html(recentgroupslink_orig);
		},
		url: url_getrecentgroups+"/offset/"+offset,
		success: function(result){
			if(prepend == 1) {
				$(obj).prepend(result);
			} else {
				$(obj).append(result);
			}
			removeDupes_recentGroups(recentgroupsdiv);
		}
	});
};

function removeDupes_recentGroups(obj){
	$(recentgroupsdiv + ' [id]').each(function(){
		var ids = $('[id='+this.id+']');
		if(ids.length > 1 && ids[0]!=this){
			console.warn('Multiple IDs #'+this.id);
			$(this).remove();
		}
	});
}