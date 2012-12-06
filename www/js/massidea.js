"use strict";

/**
 * Global MassIdea utilities
 *
 * @namespace
 * @const
 */
var MassIdea = new (function () {

	/** @const */ var URL_CHANGE_LANGUAGE = '/index/change-language';
	/** @const */ var SEL_LANGUAGE_MENU   = '#languageMenu';

	/** @type {String} */
	var _language;

	function init (language) {
		_language = language;

		$(SEL_LANGUAGE_MENU).change(function () {
			changeLanguage($(":selected", this).val());
		});
	}

	/**
	 * Generates a Zend Framework URL
	 *
	 * @param {String} url
	 * @param {Object} params
	 * @return {String}
	 */
	function zendUrl (url, params) {
		params = params || {};
		for (var key in params) {
			if (!params.hasOwnProperty(key)) continue;
			url += '/' + escape(key) + '/' + escape(params[key]);
		}
		return '/' + getLanguage() + url;
	}

	/**
	 * Loads the response from the given resource.
	 *
	 * @param {String}   url
	 * @param {Object}   [params]
	 * @param {Function} [callback]
	 * @param {String}   [dataType]
	 */
	function load (url, params, callback, dataType) {
		$.post(zendUrl(url, params), callback, dataType);
	}

	/**
	 * Loads HTML from a remote ressource and injects it
	 * into the given target
	 *
	 * @param {String|jQuery} target
	 * @param {String}        url
	 * @param {Object}        [params]
	 * @param {Function}      [callback]
	 */
	function loadHTML (target, url, params, callback) {
		params = params || {};
		callback = (typeof callback === 'function') ? callback : new Function();
		callback = (typeof params === 'function') ? params : callback;

		params.format = 'html';
		var $target = (typeof target === 'string') ? $(target) : target;

		$.post(zendUrl(url, params), function (response) {
			$target.html(response);
			callback();
		});
	}

	/**
	 * Redirects the page to the given url.
	 * @param {String} url
	 * @param {Object} [params]
	 */
	function redirect(url, params) {
		location.href = zendUrl(url, params);
	}

	/**
	 * Redirects the browser to change the language and then returns to the specified url.
	 * @param {String} language
	 */
	function changeLanguage(language) {
		var returnUrl = location.pathname;
		if (returnUrl.length > 1) {
			returnUrl = '/' + returnUrl.split('/').slice(2).join('/');
		}

		redirect(URL_CHANGE_LANGUAGE, {
			language : language,
			returnUrl : returnUrl
		});
	}

	/**
	 * Returns the current page language.
	 * @return {String}
	 */
	function getLanguage() {
		return _language;
	}

	// module exports
	this.init        = init;
	this.url         = zendUrl;
	this.redirect    = redirect;
	this.load        = load;
	this.loadHTML    = loadHTML;
	this.getLanguage = getLanguage();

})();
