"use strict";

var Content = new (function () {
	/** @const */ var URL_LOAD_CONTENT = '/content/get-content';
	/** @const */ var SEL_CONTENT_LIST = '#content-list';
	/** @const */ var SEL_LOAD_MORE    = '#load-more';
	/** @const */ var SEL_SECTION      = '#filter-section';
	/** @const */ var SEL_CATEGORY     = '#filter-category';

	/** @type {Number} */
	var _category = 0;
	/** @type {Number} */
	var _section = 0;
	/** @type {Number} */
	var _page = 0;

	/** @type {jQuery} */
	var $contentList;
	/** @type {jQuery} */
	var $loadMoreButton;
	/** @type {jQuery} */
	var $sectionFilter;
	/** @type {jQuery} */
	var $categoryFilter;

	function init() {
		$contentList    = $(SEL_CONTENT_LIST);
		$loadMoreButton = $(SEL_LOAD_MORE).click(loadMore);
		$sectionFilter  = $(SEL_SECTION).delegate('a', 'click', function () {
			applySectionFilter($(this).attr('rel'));
		});
		$categoryFilter = $(SEL_CATEGORY).delegate('a', 'click', function () {
			applyCategoryFilter($(this).attr('rel'));
		});
		loadContent();
	}

	/** Loads content from the server. */
	function loadContent() {
		MassIdea.loadHTML($contentList, URL_LOAD_CONTENT, {
			category : _category,
			section  : _section,
			page     : 0
		});
	}

	/**
	 * Appends content to the list.
	 * @param {Number} [page=0] When specified, the given page will be loaded
	 */
	function appendContent(page) {
		page = page || 0;
		MassIdea.load(URL_LOAD_CONTENT, {
			category : _category,
			section  : _section,
			page     : page
		}, function (body) {
			$contentList.append(body);
		});
	}

	/**
	 * Changes the selected number and updates the list
	 * @param {Number} category
	 */
	function applyCategoryFilter(category) {
		_category = category;
		_page = 0;
		loadContent();
	}

	/**
	 * Changes the selected section and updates the list
	 * @param {Number} section
	 */
	function applySectionFilter(section) {
		_section = section;
		_page = 0;
		loadContent();
	}

	/** Loads more content */
	function loadMore() {
		appendContent(++_page);
		return false;
	}


	this.init = init;
})();
