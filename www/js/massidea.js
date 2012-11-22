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
