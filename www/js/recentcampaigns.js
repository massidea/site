$(document).ready(function(){

	//initial data
	var index = 0;
	var status = 'active';
	var data, ndata
	
	var pageCounts = {'active':1, 'forthcoming':1, 'ended':1};
	
	$('#recent_campaigns_ajax_link > h3 > a').live('click', function(){
		pageCounts[status] = pageCounts[status] + 1;

		var url = jsMeta.baseUrl + "/en/ajax/getrecentcampaigns/offset/" + pageCounts[status] + "/status/" + status;
		$("#campaign-status").tabs("url",index,url);
		$("#campaign-status").tabs("load",index);
		
	});
	
	$(function() {
		$("#campaign-status").tabs({
			select: function(event,ui) {
				pagecount = 1;
				index = ui.index;
				
				var tab_name = ui.tab.toString();
				var a = tab_name.split("#");
				status = a[1];
			},
			load: function(event,ui) {
				ndata = $(ui.panel).html();
				if(data.match(ndata))
					$(ui.panel).html(data);
				else
					$(ui.panel).html(data + ndata);
			},
			
			ajaxOptions: {
				error: function(xhr, status, index, anchor) {
					$(anchor.hash).html("Couldn't load this tab.");
				},
				beforeSend: function() {
					data = $('#' + status).html();
				}
			}
		});
	});

	
});