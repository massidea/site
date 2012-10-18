$(function (){

    var $liveFeed = $(".startPageFeed");
    var $liveFeedContent = $(".feedContent");
    var $liveFeedControl = $(".feedControl");
    var time = 0;

    $liveFeed.hover(stopTimer, resetTimer);
    $liveFeedControl.click(loadContent);
    resetTimer();


    function loadContent(){
        $liveFeedContent.load('/'+LANGUAGE+'/content/feed/format/html');
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