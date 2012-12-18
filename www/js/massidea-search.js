"use strict";


var Search = new (function () {
    /** @const */ var SEARCH_ACCORDION = '#search-accordion';
    /** @const */ var MATCH_MAKING_ACCORDION = '#match-making-accordion'
    /** @const */ var SEARCH_CONTENT = '#searchbar';
    /** @const */ var URL_LOAD_SEARCH_RESULTS = '/search/get-results';
    /** @const */ var ICON_SEARCH = '#search .icon';



    var $searchInput = $(SEARCH_CONTENT);
    var $searchAccordion = $(SEARCH_ACCORDION);
    var $matchMakingAccordion = $(MATCH_MAKING_ACCORDION);
    var $searchIcon =  $(ICON_SEARCH);

    $matchMakingAccordion.css('display', 'block');
    $searchAccordion.css('display', 'none');






    $searchInput.keyup(function() {

        if ($searchInput.val().length > 0) {
            $searchIcon.removeClass("search");
            $searchIcon.addClass("stop-search");
        } else {

            $searchIcon.removeClass("stop-search");
            $searchIcon.addClass("search");
        }

        MassIdea.loadHTML(SEARCH_CONTENT, URL_LOAD_SEARCH_RESULTS, function () {
            $matchMakingAccordion.css('display', 'none');
            $searchAccordion.css('display', 'block');
        });
    });
})();