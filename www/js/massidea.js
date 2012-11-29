$('*[rel=popover-hover]').popover({
	trigger : 'hover',
	html    : 'true'
});

//language selection
$("#languageMenu").change(function () {
	var curLan = $(":selected", this).val();
	// not at startepage without any language (/de/...)
	var pathName = location.pathname;
	if (pathName.length > 1) {
		pathName = '/' + pathName.split('/').slice(2).join('/');
	}
	location.href = '/' + curLan + '/index/change-language?language=' + curLan + '&returnUrl=' + escape(pathName);
});

var MassIdea = new (function () {

	/**
	 * Generates a Zend Framework URL
	 *
	 * @param {String} url
	 * @param {Object} params
	 * @return {String}
	 */
	function zendUrl (url, params) {
		params = params || {};
		for (key in params) {
			if (!params.hasOwnProperty(key)) continue;
			url += '/' + escape(key) + '/' + escape(params[key]);
		}
		return '/' + LANGUAGE + url;
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
	 */
	function loadHTML (target, url, params) {
		params = params || {};

		params.format = 'html';
		var $target = (typeof target === 'string') ? $(target) : target;

		$.post(zendUrl(url, params), function (response) {
			$target.html(response);
		});
	}

	this.url = zendUrl;
	this.load = load;
	this.loadHTML = loadHTML;

})();

var popover = null;
$('.mainnavigation_popover').popover({
    trigger:'manual',
    html:'true'
}).live("click", function () {
        // open new one
        if (this != popover) {
            $(popover).popover('hide');
            $(this).popover('show');
            $('.popover-title:not(:has(a))').append('<a class="close">x</a>');
            popover = this;

        } else { // close
            $(this).popover('hide');
            popover = null;
        }

    });
$('.popover .close').live('click', function() {
    $(popover).popover('hide');
    popover = null;
});

$("#appendedInputButton").bind('focus', function(){
    $(".search .btn").addClass("focus");
});

$('#appendedInputButton').blur(function() {
    $(".search .btn").removeClass("focus");
});
