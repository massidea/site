/**
 * Created by JetBrains PhpStorm.
 * User: Martin Chalupar
 * Date: 11.10.12
 * Time: 09:32
 * Javascript for dynamically loading of Newsfeed into layout_new.phtml
 */
$(function() {

    $('*[rel=popup]').hide();

    /* ---------------- News Feed ------------- */
    $('.feedContent').load('/'+LANGUAGE+'/content/feed/format/html');
    console.log('foo');

});