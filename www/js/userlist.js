$(document).ready(function(){
	
	complete_city(userCities);
	
	if(userIds) {
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
		$("input#user_"+this+"_content").focus(function() {
			$(this).click();
		});
		$("input#user_"+this+"_content").result(function(event, data, formatted) {
			 $("#user_"+id+"_content_link").attr('action', function() {
			 	return contentView+'/'+data.id_cnt;
			 });
		});
		$("#user_list_user_"+this+"_statistics_load_graphs").click(function() {
			if($("#user_list_user_"+id+"_statistics_load_graphs").attr('name') != 'complete') {
				$("#user_list_user_"+id+"_statistics_load_graphs").hide();
				$("#user_list_"+id+"_show_graphs_loading").html(loading).show();
				getUserStatisticsGraphs(id);
				$("#user_list_user_"+id+"_statistics_load_graphs").attr('name','complete');
			}
			else {
				var attr = $("#user_"+id+"_charts").css('display');
				if(attr == 'none') {
					$("#user_"+id+"_charts").slideDown(200);
					$("#user_list_user_"+id+"_statistics_load_graphs").html("Hide Graphs");
				}
				else {
					$("#user_"+id+"_charts").slideUp(400);
					$("#user_list_user_"+id+"_statistics_load_graphs").html("Show graphs");
				}
			}
		});
		
		$("#user_list_"+this+"_hide_graphs").click(function() {
			$("#user_"+id+"_charts").slideUp(400);
		});
	});
	$("#user_list_top_list_link").live('click', function() {
		if($(".user_list_top_list").html() == "") {
			getSearchTopList();
			generateTopListEffects(jQuery.parseJSON('["Count","View","Popularity","Rating","Comment"]'));
			
		}
		else {
			if($(".user_list_top_list").css("display") == "block") $(".user_list_top_list").slideUp(500);
			else $(".user_list_top_list").slideDown(1000);
		}
	});
	} //End of if userIds
	//Start of Top list js
	else {
		generateTopListEffects(topList);
	}
});

function generateTopListEffects(list) {
	$.each(list, function() {
		var name = this;
		$("#user_list_top_box_show_more_link_"+name+"").live('click', function() {
			if($("#user_list_top_box_right_"+name+"").css("display") == "none") {
				$("#user_list_top_box_right_"+name+"").slideDown(500);
				$(this).html("<img src=\""+arrowup+"\"/>");
			}
			else {
				$("#user_list_top_box_right_"+name+"").slideUp(500);
				$(this).html("<img src=\""+arrowdown+"\"/>");
			}			
		});
	});

	$("#user_list_top_list_expand_all").live('click',function() {
		if($("#user_list_top_list_expand_all").attr('name') != 'expand') {
			$.each(list, function() {
				
				var name = this;
				$("#user_list_top_box_right_"+name+"").slideDown(500);
				$("#user_list_top_box_show_more_link_"+name+"").html("<img src=\""+arrowup+"\"/>");
				$("#user_list_top_list_expand_all").html("<img src=\""+iconminus+"\"/>");
				$("#user_list_top_list_expand_all").attr('name','expand');
			});
		}
		else {
			$.each(list, function() {
				var name = this;
				$("#user_list_top_box_right_"+name+"").slideUp(500);
				$("#user_list_top_box_show_more_link_"+name+"").html("<img src=\""+arrowdown+"\"/>");
				$("#user_list_top_list_expand_all").html("<img src=\""+iconplus+"\"/>");
				$("#user_list_top_list_expand_all").attr('name','collapse');
			});
		}
	});
}

function getSearchTopList() {
	
	var before = $("#user_list_top_list_show span").html();
	$.ajax({
		beforeSend: function(){
			$("#user_list_top_list_show span").html(loading);
		},
		complete: function(){
			$("#user_list_top_list_show span").html(before);
			$("#user_list_top_list_link img").attr("src",iconminus);
		},	
		url: topUrl+requestedUrl,
		success: function(data) {
		  $(".user_list_top_list").html(data);
		  $(".user_list_top_list").slideDown(1000);

		}	
	});
};


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

function getUserStatisticsGraphs(user_id) {
	$.ajax({
		  url: userStatistics+"/user/"+user_id+"/search/graphs",
		  dataType: 'json',
		  success: function(data) {
			  createGraphs(user_id,data);
		}	
	});
}

function createGraphs(user_id, data) {
	var graphTypes = [0,0,0];
	var total = 0;
	$.each(data, function() {
		total += parseInt(this.amount);
		if(this.type == "finfo") graphTypes[0] = parseInt(this.amount);
		else if(this.type == "idea") graphTypes[1] = parseInt(this.amount);
		else if(this.type == "problem") graphTypes[2] = parseInt(this.amount);
	});

	var graphPie = [0,0,0];
	var i = 0;
	for(i=0;i<3;i++) {
		graphPie[i] = Math.round((graphTypes[i]/total)*100);
	}
	var data = [$.gchart.series(graphPie, ['FFC726', '4B9B07', 'D21034'])];
	
	$("#user_"+user_id+"_typechart").gchart({
		width: 270, height: 100,
		title: 'User content distribution',
		type: 'pie3D',
		series: data,
		dataLabels: ['Visions', 'Ideas', 'Problems']
	}).delay(500).queue(function() {
		$("#user_list_"+user_id+"_show_graphs_loading").hide();
		$("#user_list_user_"+user_id+"_statistics_load_graphs").html("Hide graphs").show();
		$("#user_"+user_id+"_charts").slideDown(200);
	});
}
