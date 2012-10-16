$('*[rel=popup]').hide();

//language selection
$("#languageMenu").change(function() {
   var curLan = $(":selected", this).val();
    // not at startepage without any language (/de/...)
   var curLocation = location.pathname;
   if (curLocation.length > 1) {
       curLocation = curLocation.substr(3);
   }
   location.href = '/' + curLan + '/index/change-language?language=' + curLan + '&returnUrl=' + curLocation;
});