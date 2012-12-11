"use strict";

/**
 * Manages content feeds and lists.
 *
 * @namespace
 * @const
 */
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
            var $this = $(this);
			applySectionFilter($this.attr('rel'), $this.text());
		});
		$categoryFilter = $(SEL_CATEGORY).delegate('a', 'click', function () {
            var $this = $(this);
			applyCategoryFilter($this.attr('rel'), $this.text());
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
			page     : page,
			format   : 'html'
		}, function (body) {
			$contentList.append(body);
		});
	}

	/**
	 * Changes the selected number and updates the list
	 * @param {Number} category
     * @param {String} caption
	 */
	function applyCategoryFilter(category, caption) {
		_category = category;
		_page = 0;
        $(SEL_CATEGORY).find('.caption').text(caption);
		loadContent();
	}

	/**
	 * Changes the selected section and updates the list
	 * @param {Number} section
     * @param {String} caption
     */
	function applySectionFilter(section, caption) {
		_section = section;
		_page = 0;
        $(SEL_SECTION).find('.caption').text(caption);
        loadContent();
	}

	/** Loads more content */
	function loadMore() {
		appendContent(++_page);
		return false;
	}

	// module exports
	this.init = init;

})();
