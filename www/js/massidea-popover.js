"use strict";

// TODO: refactor this to a jQuery plugin.

$('*[rel=popover-hover]').popover({
	trigger : 'hover',
	html    : 'true'
});

var popover = null;
$('.mainnavigation_popover').popover({
	trigger : 'manual',
	html    : 'true'
}).live("click", function () {
		if (this != popover) {
			// open new one
			$(popover).popover('hide');
			$(this).popover('show');
			$('.popover-title:not(:has(a))').append('<a class="close">x</a>');
			popover = this;
		} else {
			// close
			$(this).popover('hide');
			popover = null;
		}
	});

$('.popover .close').live('click', function () {
	$(popover).popover('hide');
	popover = null;
});

$("#appendedInputButton").bind('focus', function () {
	$(".search .btn").addClass("focus");
});

$('#appendedInputButton').blur(function () {
	$(".search .btn").removeClass("focus");
});
