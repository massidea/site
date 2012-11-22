$('*[rel=popover-hover]').popover({
	trigger:'hover',
	html:'true'
});



//language selection
$("#languageMenu").change(function() {
   var curLan = $(":selected", this).val();
    // not at startepage without any language (/de/...)
   var curLocation = location.pathname;
   if (curLocation.length > 1) {
       curLocation = curLocation.substr(3);
   }
   location.href = '/' + curLan + '/index/change-language?language=' + curLan + '&returnUrl=' + escape(curLocation);
});

var popover = null;
$('.mainnavigation_popover').popover({
    trigger:'manual',
    html:'true'
}).live("click", function () {
        // open new one
        if (this != popover) {
            $(popover).popover('hide');
            $(this).popover('show');
            $('.popover-title:not(:has(a))').append('<a class="close">x</a>');
            popover = this;

        } else { // close
            $(this).popover('hide');
            popover = null;
        }

    });
$('.popover .close').live('click', function() {
    $(popover).popover('hide');
    popover = null;
});

$("#appendedInputButton").bind('focus', function(){
    $(".search .btn").addClass("focus");
});

$('#appendedInputButton').blur(function() {
    $(".search .btn").removeClass("focus");
});