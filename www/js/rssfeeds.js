$("document").ready(function() {
	getFeed();
});

function getFeed() {
	var jsmeta = jQuery.parseJSON($("#jsmetabox").text());
	
	data = new Array();
	data = {'type': jsmeta.currentPage[0].type, 'id': jsmeta.currentPage[0].id };
	$.ajax({
		type: "GET",
		//async: false,
		url: jsmeta.baseUrl + "/en/ajax/readrss", //urls.commentUrls[0].postCommentUrl,
		data: data,
		success: function(msg) {
			$('#rss').html(msg);
			$(".rss-item").each( function() {
				$(this).qtip({
					content: $(this).children(".rss-item-desc").html(),
					show: 'mouseover',
					style: { width: { min: 250, max: 500}}, 
					position: { target: $(this).parent(), corner: { target: 'topRight', tooltip: 'topLeft' }},
					hide: 'mouseout'
				});
				
			});
		}
	});
}