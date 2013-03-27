(function($) {
  // put together the name of the new jQuery variable
  var version = $().jquery;
  var versionName = version.replace(/\./g, '');
  // save the new version of jQuery globally and restore Drupal version
  window.jq_bootstrap = jQuery.noConflict(true);
})(jQuery);
