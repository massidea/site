$(document).ready(function(){

	complete_city(userCities);
	
	$.each(userIds, function() {
		var id = this;
		$("#user_"+this+"_list_more").click(function() {
				$(this).attr("style",'display: none;');
				$("#user_"+id+"_list_less").attr("style",'display: block;');
				$("#user_list_user_"+id+"_statistics").slideDown(200,
				function(){ $("#user_list_user_"+id+"_search").slideDown(200); }
				);
		});
		$("#user_"+this+"_list_less").click(function() {
				$(this).attr("style",'display: none;');
				$("#user_"+id+"_list_more").attr("style",'display: block;');
				$("#user_list_user_"+id+"_statistics").slideUp(400);
				$("#user_list_user_"+id+"_search").slideUp(400);
		});
		$("#user_list_"+this+"_show_more").click(function() {
				$(this).hide();
				$("#user_list_"+id+"_show_more_loading").html(loading).show();
				var childs = $("#user_"+id+"_list_user_recent_contents_list").children().length;
				json_search_contents(childs,id,this);
				
		});
		$("#user_list_"+this+"_hide_new").click(function() {
				$("#user_"+id+"_list_user_recent_contents_list > .user_list_user_line_new").slideUp(700);
		});
		$("input#user_"+this+"_content").click(function() {
			if($("#user_"+id+"_content_link").attr('name') != 'complete') {
				var old = $("#user_"+id+"_content_search_text").html();
				$("#user_"+id+"_content_search_text").html(loading);
				jsonSearchContents(id,old);
				$("#user_"+id+"_content_link").attr('name','complete');
			}
		});
		$("input#user_"+id+"_content").focus(function() {
			$(this).click();
		});
		$("input#user_"+this+"_content").result(function(event, data, formatted) {
			 $("#user_"+id+"_content_link").attr('action', function() {
			 	return contentView+'/'+data.id_cnt;
			 });
		});		
	});	
});

function json_search_contents(listStart,id,div) {
	$.ajax({
	  url: userContents+"/search/"+id+"/start/"+listStart,
	  dataType: 'json',
	  success: function(data) {
	  		
	  		var output = "";
			$.each(data, function() {
				if(this.title_cnt.length > 70) { 
					this.title_cnt.length = 70;
					this.title_cnt += "...";
				}
				output += "<div class=\"user_list_user_line_new\" style=\"display:none\">"+
							"<span class=\"user_list_content_type_"+this.key_cty+"\">> </span>"+
							"<a class=\"user_list_content_title\" href=\""+contentView+"/"+this.id_cnt+"\">"+
							this.title_cnt+"</a></div>"
							;
			});

		  	$("#user_"+id+"_list_user_recent_contents_list").append(output);
		  	$("#user_"+id+"_list_user_recent_contents_list .user_list_user_line_new").slideDown(700,function(){ 
		  	$("#user_list_"+id+"_show_more_loading").hide();
		  	$(div).show();
		  	 });
		  	if(output == "") {
			    $("#user_list_"+id+"_show_more_loading").hide();
			  	$(div).show();
		  	}

	  }	
	});
};

function jsonSearchContents(user_id,old){
	$.ajax({
	  url: userContents+"/search/"+user_id,
	  dataType: 'json',
	  success: function(data) {
		  complete_user_content_search(user_id,data,old);
		}	
	});
};

function complete_user_content_search(user_id,result,old){

	var green = "smallsize_idea_border";
	var red = "smallsize_problem_border";
	var yellow = "smallsize_finfo_border";
	
	$("#user_"+user_id+"_content").autocomplete(result, {
	minChars: 0,
	width:347,
	delay:100,
	scrollHeight:200,
	cacheLength:20,
	matchContains: true,
	autoFill: false,
	selectFirst:true,
	max:result.length,
	formatItem: function(row, i, max) {
		if (row.id_cty_cnt == 1) var type = yellow;
		if (row.id_cty_cnt == 2) var type = green;
		if (row.id_cty_cnt == 3) var type = red;
		
		var rating_positive = Math.ceil(((row.rating_sum / row.ratings) + 1) * 50);
		var rating_negative = Math.floor(100-((row.rating_sum / row.ratings) + 1) * 50);

		var rating = "<span class=\"user_list_positive_icon\">"+rating_positive+"% </span>"+
					"<img src=\""+thumbUp+"\" alt=\"\" />"+
					"<img src=\""+thumbDown+"\" alt=\"\" />"+
					"<span class=\"user_list_negative_icon\"> "+rating_negative+"%</span>";
		
		if (row.views)
			var views = row.views;
		else var views = "Not viewed";
		
		if (row.created_cnt)
			var created_cnt = "Created: "+row.created_cnt.split(" ")[0];
		else var created_cnt = "Created: Date missing";
		
		if (!row.rating_sum) var rating = "Not rated"
		return "<span class=\""+type+"\">"+
					"<span class=\"user_list_input_box_title\">"+
						"<a href=\""+contentView+"/"+row.id_cnt+"\" OnClick=\"window.location=\'"+contentView+"/"+row.id_cnt+"'\;\">"+
						 row.title_cnt + "</a></span><br />"+
					"<span class=\"user_list_input_box_lead\">"+row.lead_cnt+"</span><br />"+
					"<span class=\"user_list_input_box_meta\">"+rating+" | Views: "+views+ " | " + created_cnt + "</span>"+
			   "</span>";

	},
	formatMatch: function(row, i, max) {
		return row.title_cnt+" "+row.lead_cnt;
	},
	formatResult: function(row) {
		return row.title_cnt;
	}
});
$("#user_"+user_id+"_content_search_text").html(old);
};

function complete_city(result){
	
	$("#city").autocomplete(result, {
	minChars: 1,
	width:247,
	delay:400,
	scrollHeight:200,
	matchContains: true,
	autoFill: false,
	selectFirst:false,
	formatItem: function(row, i, max) {
		return row.name + "<span style=\"float:right\"> [" + row.amount + "]</span>";
	},
	formatMatch: function(row, i, max) {
		return row.name;
	},
	formatResult: function(row) {
		return row.name;
	}
});

};