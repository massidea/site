"use strict";


var Search = new (function () {
    /** @const */ var SEL_SEARCH       = '#search-accordion';
    /** @const */ var SEL_MATCHMAKING  = '#match-making-accordion';
    /** @const */ var SEL_SEARCHBAR    = '#searchbar';
	/** @const */ var SEL_SEARCH_ICON  = '#search .icon';
	/** @const */ var URL_LOAD_RESULTS = '/search/get-results';

	var $search      = $(SEL_SEARCH);
    var $searchInput = $(SEL_SEARCHBAR);
	var $searchIcon  = $(SEL_SEARCH_ICON);
    var $matchMaking = $(SEL_MATCHMAKING);

	/** Initializes the search and binds all event handlers */
	function init() {
		$searchInput.keyup(onSearch);
		$searchIcon.click(stopSearch);
		showMatchMaking();
	}

	/** Shows match making and hides the search */
	function showMatchMaking() {
		$matchMaking.show();
		$search.hide();
	}

	/** Shows the search results and hides match making */
	function showSearch() {
		$search.show();
		$matchMaking.hide();
	}

	/**
	 * Updates the search view and changes the search bar.
	 * @param {Event} [e] An event which triggered this action.
	 */
	function onSearch(e) {
		if (e.keyCode == 27) {
			stopSearch(e);
			return;
		}

		var input = $searchInput.val();
		setSearchIcon(input.length > 0);
		MassIdea.loadHTML(SEL_SEARCHBAR, URL_LOAD_RESULTS, showSearch);
	}

	/**
	 * Stops the search and resets the sidebar.
	 * @param {Event} [e] An event which triggered this action.
	 */
	function stopSearch(e) {
		$searchInput.val('');
		setSearchIcon(false);
		showMatchMaking();
	}

	/**
	 * Sets the style of the search icon and puts focus on the search input.
	 * @param {Boolean} searching Specifies whether the search bar is active or not.
	 */
	function setSearchIcon(searching) {
		$searchIcon
			.toggleClass('search',     !searching)
			.toggleClass('stop-search', searching);
		$searchInput.focus();
	}

	this.init = init;
})();
