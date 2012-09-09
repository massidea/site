// Horribly working language changer script

// Get old language and replace current url with new language
function changeLang(oldLanguage, baseurl) {
    //reset cookies
    var language = $("#languages").val();
    oldLan = readCookie('language');
    if(oldLan==null||oldLan==''||oldLan!=language){
	eraseCookie('language');
	createCookie('language',language,1);
	
    }
    var url = document.location.href;
    //alert("indexOf = "+url.indexOf(oldLanguage)+"\noldLanguage = "+oldLanguage+"\nvalittu = "+$("#languages").val()+"\nurl = "+url+"\nbaseurl = "+baseurl);
    if(url.indexOf(oldLanguage) > 0) {
    	window.location = url.replace("/"+oldLanguage, "/"+$("#languages").val());
    } else {
    	window.location = baseurl+$("#languages").val();
    }
}

function createCookie(name, value, days) {
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