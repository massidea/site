$("document").ready(function() {
	getFeed();
});

function getFeed() {
	data = new Array();
	data = {'type': jsMeta.currentPage[0].type, 'id': jsMeta.currentPage[0].id };
	$.ajax({
		type: "GET",
		//async: false,
		url: jsMeta.baseUrl + "/en/ajax/readrss",
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