$('*[rel=popup]').hide();

//language selection
$("#languageMenu").change(function() {
   var curLan = $(":selected", this).val();
   location.href = '/index/changeLanguage?language=' + curLan + '&returnUrl=' + location.pathname;
});