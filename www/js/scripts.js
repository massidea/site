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
	
	$("#login_link").click(function () {
		$("#login_box").dialog("open");
		$("#login_box > form > div:nth-child(2) > input").focus();
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
	
	$("#login_link").hover(
			function(){
				var optPos = $("#login_link").position().left;
				$("#user_options").clearQueue().css("left",optPos).slideDown();
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
	 $("#add_content_button").hover(
			 function () {$("#add_content_menu").fadeIn();}
			 );

	 $(".sub_menu_right").hover(
			 function () {$("#add_content_menu").clearQueue();},
			 function () {$("#add_content_menu").delay(1000).fadeOut();}
			 );

	 
});

function highlightContentMenuItem(target)
{
	document.getElementById(target).style.background = '#FFFFFF';
}

function dimContentMenuItem(target)
{
	document.getElementById(target).style.background = '#EEEEEE';
}