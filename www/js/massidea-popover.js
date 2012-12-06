"use strict";

/**
 * Handles Bootstrap Popovers with additional functionality
 */
var Popover = new (function () {

	/** @const */ var $EMPTY = $();
	/** @const */ var $DOCUMENT = $(document);

	/** @type jQuery */
	var _activePopover = $EMPTY;

	/**  */
	!function constructor() {
		$DOCUMENT.delegate('.popover', 'click', function (e) {
			e.stopPropagation();
		});

		register('*[rel=popover-hover]');
	}();

	/**
	 * Registers new elements as popover hosts
	 * @param {String|jQuery} selector The selector or jQuery object which specifies those elements.
	 */
	function register (selector) {
		selector = (typeof selector === 'string') ? $(selector) : selector;

		selector
			.popover({
				trigger : 'manual',
				html    : true
			})
			.live('click', function (e) {
				openPopover($(this));
				e.stopPropagation();
			});
	}

	/**
	 * Opens a new popover dialog and closes an old one, if currently active
	 * @param {jQuery} target Defines the element which hosts content for the popover
	 */
	function openPopover (target) {
		if (target.get(0) == _activePopover.get(0)) return;

		closePopover();
		_activePopover = target;
		_activePopover.popover('show');
		$DOCUMENT.one('click', closePopover);
	}

	/** Closes the currently active popover */
	function closePopover () {
		_activePopover.popover('hide');
		_activePopover = $EMPTY;
		$DOCUMENT.unbind('click', closePopover);
	}

	// module exports
	this.register = register;

})();
