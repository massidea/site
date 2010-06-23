/**
*   Needs commenting!
*/

function changeBack(color)
{
	// change background color
	$('body').ready(function () {
		 colors = { 'problem': '#d21034',
				 	'idea': '#4b9b07',
					'finfo': '#ffc726'};
		$('body').css('background-color', colors[color]);
	});	
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
	$("#login_link_in_box").click(
			function () {$("#login_box").fadeIn();}
	);
	

	$("#login_link_in_box").click(
			function () {$("#login_box_openid").fadeOut();}
	);

	$("#login_link").click(
			function () {$("#login_box").fadeIn();}
	);

	$("#login_link_openid").click(
			function () {$("#login_box_openid").fadeIn();}
	);

	$("#login_link_openid").click(
			function () {$("#login_box").fadeOut();}
	);

	$("#login_box").hover(
			function () {}, 
			function () {$("#login_box").fadeOut();}
	);

	$("#login_box_openid").hover(
			function () {}, 
			function () {$("#login_box_openid").fadeOut();}
	);
  
	$("#add_content_button").hover(
			function () {$("#add_content_menu").fadeIn();}, 
			function () {}
	);

	$(".sub_menu_right").hover(
			function () {}, 
			function () {$("#add_content_menu").fadeOut();}
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