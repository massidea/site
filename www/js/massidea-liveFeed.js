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
$liveFeedControl.click(loadContent);
loadContent();
resetTimer();


    function loadContent(){
        console.log("load");
        $feedOverlay.removeClass("hidden");
        $liveFeedContent.load('/'+LANGUAGE+'/content/feed/format/html', function (){
            $feedOverlay.addClass("hidden");
        });
    }

    function resetTimer(){
        console.log(time + "timer");
        if (time) {
            window.clearInterval(time);
            console.log(time);
        }
        time = window.setInterval(loadContent, 3000);
        console.log(time);
    }

    function stopTimer(){
        console.log("stop" + time);
        if (time)
            window.clearInterval(time);
        time = 0;
    }
