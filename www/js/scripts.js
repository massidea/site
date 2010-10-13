/**
*   Needs commenting!
*/

function changeBack(color)
{
	// change background color
	/*$('body').ready(function () {
		 colors = { 'problem': '#d21034',
				 	'idea': '#4b9b07',
					'finfo': '#ffc726'};
		$('body').css('background-color', colors[color]);
	});*/
}

function focusMenu(target, color, baseurl)
{
	document.getElementById(target).style.background = "url('"+baseurl+"/images/menu_border_"+color+"_focus.png')";
	document.getElementById(target).style.backgroundPosition = "bottom left";
	document.getElementById(target).style.backgroundRepeat = "repeat-x";
}

function blurMenu(target, color, baseurl)
{
	document.getElementById(target).style.background = "url('"+baseurl+"/images/menu_border_"+color+".png')";
	document.getElementById(target).style.backgroundPosition = "bottom left";
	document.getElementById(target).style.backgroundRepeat = "repeat-x";
}

$(document).ready(function(){
	var meta = jsMeta;
	
	$("#login_box").dialog({
		closeOnEscape: true,
		draggable: false,
		modal: true,
		resizable: false,
		title: 'Login to Massidea',
		autoOpen: false
	});

	$("#login_box_openid").dialog({
		closeOnEscape: true,
		draggable: false,
		modal: true,
		resizable: false,
		title: 'Login with OpenID',
		autoOpen: false
	});
	
	$("#add_new_content").dialog({
		closeOnEscape: true,
		draggable: false,
		modal: true,
		resizable: false,
		title: 'Select content type',
		autoOpen: false,
		width: 625,
		height: 345
	});
	$("#add_new_content > .add_new > .add_new_info > .add_new_title > a").each(function(){
		$(this).click(function(){		
			$("#add_new_content").dialog("close");
		});
	});
	
	//$("#add_new_content").parent().removeClass("ui-widget-content");
	$("#addnewcontent").click(function(){
		if($("#add_new_content").html() != null) {
			$("#add_new_content").dialog("open");
		}
		else {
			$("#login_box").dialog( "option", "title", 'You must login to add content' );
			$("#login_box").dialog("open");
			$("#login_box > form > div:nth-child(2) > input").focus();
		}
		
	});

	 $("#loginlink").click(function() {
			 $("#login_box").dialog( "option", "title", 'Login to Massidea' );
			 $("#login_box").dialog("open");
			 $("#login_box > form > div:nth-child(2) > input").focus();
	 });
	 
	 $("a#login_link").each(function() {
		 $(this).click(function (event) {
			 event.preventDefault();
			 $("#login_box").dialog( "option", "title", 'Login to Massidea' );
			 $("#login_box").dialog("open");
			 $("#login_box > form > div:nth-child(2) > input").focus();
		 });
	 });

	$("#login_link_openid").click(function () {
		$("#login_box").dialog("close");
		$("#login_box_openid").dialog("open");
		$("#login_box_openid > form > div:nth-child(2) > input").focus();
	});
	
	$("#login_link_in_box").click(function () {
		$("#login_box_openid").dialog("close");
		$("#login_box").dialog("open");
		$("#login_box > form > div:nth-child(2) > input").focus();
	});
	
	$("#loginlink").hover(
			 function(){
				 var optPos = $("#loginlink").position().left;
				 $("#user_options").clearQueue().css("left",optPos).show();
				 },
			 function(){$("#user_options").delay(1000).slideUp();}
	);
	
	$("#user_options").hover(
			function(){$("#user_options").clearQueue()},
			function(){$("#user_options").delay(1000).slideUp();}
	);
	
		

		
	/*
	$("#user_options > ul > #user_options_groups").hover(
			function() {
				var optPos1 = $("#user_options > ul").width() *-1;
				$("#user_options_sub_1").css("left",optPos1).css("top",81).show();
				},
			function() {
					$("#user_options_sub_1").hide();
				}
		
	);
	*/
	/*
	 $("#add_content_button").hover(
			 function () {$("#add_content_menu").fadeIn();}
			 );

	 $(".sub_menu_right").hover(
			 function () {$("#add_content_menu").clearQueue();},
			 function () {$("#add_content_menu").delay(1000).fadeOut();}
			 );

	*/
	 /*
	 $("#notification_close").live("mouseover mouseout click", function(event){ 
		 if(event.type == "mouseover")
			 $("a",this).addClass("notification_close_button");
		 if(event.type == "mouseout") $("a",this).removeClass("notification_close_button");
		 if(event.type == "click") $("#notification_box").slideToggle();
	 });

	 $.ajax({
		url: meta.baseUrl+"/en/ajax/getnotifications/",
		success: function(data) {
			if(data) {
				$("#notification_box").html(data);
				$("#notification_link").click( function() {$("#notification_box").slideToggle();} );
				$("#notification_link").css("cursor","pointer");
				var notificationIds = jQuery.parseJSON($("#notification_box > #notification_ids").text());
				 $.each(notificationIds,function(index,value) {
					 var div = "#notification_list_id_"+value;
					 $(div+"> .notification_list_row_first > .notification_time > a").live("click",function(){
						 if($(".notification_list_row_other",div).is(":hidden")) {
							 $(div+"> .notification_list_row_first > .notification_time > a").text("Less");
						 }
						 else { $(div+"> .notification_list_row_first > .notification_time > a").text("More"); }
						 $(".notification_list_row_other",div).slideToggle();

					 });
				 });
				$("#notification_box").tabs();
				$("#notification_link").attr("src",meta.baseUrl+"/images/notifications_a.png");
			}
		}	
	 });
	 */
});

function highlightContentMenuItem(target)
{
	document.getElementById(target).style.background = '#FFFFFF';
}

function dimContentMenuItem(target)
{
	document.getElementById(target).style.background = '#EEEEEE';
}