$(function (){

    var $liveFeed = $(".startPageFeed");
    var $liveFeedContent = $(".feedContent");
    var $liveFeedControl = $(".feedControl");
    var $feedOverlay = $(".feedOverlay");
    var time = 0;

    $liveFeed.hover(stopTimer, resetTimer);
    $liveFeedControl.click(loadContent);
    loadContent();

    function loadContent(){
        $feedOverlay.removeClass("hidden");
        $liveFeedContent.load('/'+LANGUAGE+'/content/feed/format/html', function (){
            $feedOverlay.addClass("hidden");
        });
    }

    function resetTimer(){
        if (time)
            clearTimeout(time);
        time = setTimeout(loadContent, 3000);
    }

    function stopTimer(){
        if (time)
            clearTimeout(time);
        time = 0;
    }

});