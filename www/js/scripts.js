/**
*   Needs commenting!
*/

function changeBack(color)
{
	document.body.style.background = color;
}

function focusMenu(target, color)
{
	document.getElementById(target).style.background = "url('/images/menu_border_"+color+"_focus.png')";
	document.getElementById(target).style.backgroundPosition = "bottom left";
	document.getElementById(target).style.backgroundRepeat = "repeat-x";
}

function blurMenu(target, color)
{
	document.getElementById(target).style.background = "url('/images/menu_border_"+color+".png')";
	document.getElementById(target).style.backgroundPosition = "bottom left";
	document.getElementById(target).style.backgroundRepeat = "repeat-x";
}

$(document).ready(function(){
	$("#login_link_in_box").click(
		  function () {$("#login_box").fadeIn();}
	
		);
	}
);

$(document).ready(function(){
	$("#login_link_in_box").click(
		  function () {$("#login_box_openid").fadeOut();}
	
		);
	}
);

$(document).ready(function(){
	$("#login_link").click(
		  function () {$("#login_box").fadeIn();}
	
		);
	}
);

$(document).ready(function(){
	$("#login_link_openid").click(
		  function () {$("#login_box_openid").fadeIn();}
		);
	}
);

$(document).ready(function(){
	$("#login_link_openid").click(
		  function () {$("#login_box").fadeOut();}
		);
	}
);

$(document).ready(function(){
		$("#login_box").hover(
			function () {}, 
			function () {$("#login_box").fadeOut();}
		);
	}
);
  
$(document).ready(function(){
		$("#login_box_openid").hover(
			function () {}, 
			function () {$("#login_box_openid").fadeOut();}
		);
	}
);
  
$(document).ready(function(){
	$("#add_content_button").hover(
		  function () {$("#add_content_menu").fadeIn();}, 
		  function () {}
		);
	}
);

$(document).ready(function(){
	$(".sub_menu_right").hover(
		  function () {}, 
		  function () {$("#add_content_menu").fadeOut();}
		);
	}
);

function highlightContentMenuItem(target)
{
	document.getElementById(target).style.background = '#FFFFFF';
}

function dimContentMenuItem(target)
{
	document.getElementById(target).style.background = '#EEEEEE';
}