"use strict";

/**
 * Manages the start page live feed.
 *
 * @namespace
 * @const
 */
var LiveFeed = new (function () {

	/** @const */ var URL_LOAD_FEED = '/content/feed';
	/** @const */ var SEL_FEED      = '.startPageFeed';
	/** @const */ var SEL_CONTENT   = '.feedContent';
	/** @const */ var SEL_CONTROL   = '.feedControl';
	/** @const */ var SEL_OVERLAY   = '.feedOverlay';
	/** @const */ var LOAD_INTERVAL = 15000;

	/** @type {jQuery} */
	var $liveFeed;
	/** @type {jQuery} */
	var $liveFeedContent;
	/** @type {jQuery} */
	var $liveFeedControl;
	/** @type {jQuery} */
	var $feedOverlay;
	/** @type {Number} */
	var time;

	function init () {
		$liveFeed = $(SEL_FEED);
		$liveFeedContent = $(SEL_CONTENT);
		$liveFeedControl = $(SEL_CONTROL);
		$feedOverlay = $(SEL_OVERLAY);
		time = 0;

		$liveFeed.hover(stopTimer, resetTimer);
		$liveFeedControl.click(function (e) {
			loadContentClick();
			e.stopPropagation();
			return false;
		});

		loadContent();
		resetTimer();
	}

	/** Loads content without resetting the timer */
	function loadContentClick () {
		$feedOverlay.removeClass("hidden");
		MassIdea.loadHTML(SEL_CONTENT, URL_LOAD_FEED, function () {
			$feedOverlay.addClass("hidden");
		});
	}

	/** Loads content and resets the timer */
	function loadContent () {
		$feedOverlay.removeClass("hidden");
		MassIdea.loadHTML(SEL_CONTENT, URL_LOAD_FEED, function () {
			$feedOverlay.addClass("hidden");
			resetTimer();
		});
	}

	/** Resets the timer to load new content in the next LOAD_INTERVAL milliseconds */
	function resetTimer () {
		if (time) {
			window.clearTimeout(time);
		}
		time = window.setTimeout(loadContent, LOAD_INTERVAL);
	}

	/** Resets the timer, to stop autoloading content */
	function stopTimer () {
		if (time) {
			window.clearTimeout(time);
		}
		time = 0;
	}

	// module exports
	this.init = init;

})();
