// Horribly working language changer script
// - Made horribly changes -iirouu <3

function changeLang(language, baseurl)
{
	var url = document.location.href;
	
	var oldLang = readCookie('lang');
	
	if(url.indexOf("/"+oldLang) != -1) {
		var splitter = url.split(baseurl+"/"+oldLang);
	
		if(splitter[1] == undefined && baseurl.indexOf("/") != -1) {
			alert("Check config! path.baseurl is what you are looking for...");
			return null;
		}
	
		window.location = "http://"+baseurl+"/"+language+splitter[1];
	} else {
		window.location = "http://"+baseurl+"/"+language;
	}

	return true;
}

function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name,"",-1);
}