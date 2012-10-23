var $liveFeed = $(".startPageFeed");
var $liveFeedContent = $(".feedContent");
var $liveFeedControl = $(".feedControl");
var $feedOverlay = $(".feedOverlay");
var time = 0;

    //stop reloading of the page
    $liveFeedControl.click(function (event) {
        event.stopPropagation();
        return false;
    });

    $liveFeed.hover(stopTimer, resetTimer);
    $liveFeedControl.click(loadContentClick);
    loadContent();
    resetTimer();

    function loadContentClick(){
        $feedOverlay.removeClass("hidden");
        $liveFeedContent.load('/'+LANGUAGE+'/content/feed/format/html', function (){
            $feedOverlay.addClass("hidden");
    });
}
    function loadContent(){
        $feedOverlay.removeClass("hidden");
        $liveFeedContent.load('/'+LANGUAGE+'/content/feed/format/html', function (){
            $feedOverlay.addClass("hidden");
            resetTimer();
        });
    }

    function resetTimer(){
        if (time) {
            window.clearTimeout(time);
        }
        time = window.setTimeout(loadContent, 3000);
    }

    function stopTimer(){
        if (time)
            window.clearTimeout(time);
        time = 0;
    }
