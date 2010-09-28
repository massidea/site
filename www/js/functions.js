/**
 *  OIBS - Open Innovation Banking System
 *  Javascript-functionality for the website
 *
 *  This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License 
 *  as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied  
 *  warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for  
 *  more details.
 *
 *  You should have received a copy of the GNU General Public License along with this program; if not, write to the Free 
 *  Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 *  License text found in /license/ and on the website.
 *
 *  authors:  Joel Peltonen <joel.peltonen@cs.tamk.fi>
 *  Licence:  GPL v2.0
 */

/**
*   Needs commenting!
*/
function profileImageMenu(id, url, url2){
        var element = '#'+id;
        //$(element).fadeTo(50, 1);
        var position = $(element).position();   
        var img_height = $(element).height()/2;
        var div_width = $("#hiddenmenu").width()/2;
        var div_height = $("#hiddenmenu").height()/2;
        var img_width = ($(element).width()/2)-div_width;    
        var left = (position.left)+(img_width);
        var top = (position.top)+(img_height)-div_height;
        $("#hiddenmenu").fadeIn(100);
        document.getElementById("hiddenmenuselect").href = url;
        document.getElementById("hiddenmenudelete").href = url2;
        $("#hiddenmenu").css({'position' : 'absolute', 'left' : left, 'top' : top}); 
}

/**
*   Needs commenting!!
*/
function profileImageMenuOut(id) {
    // var element = '#'+id;
    // $(element).fadeTo(100, 1); 
    // $("#hiddenmenu").fadeOut("fast");
}

/**
*   Needs commenting!
*/
function menufadeout(){
    $("#hiddenmenu").fadeOut("fast"); 
}

/**
*	The captcha changer, now with baseurl
*
*   @param baseUrl
*/
function reloadCaptcha(baseUrl){
	var image = document.getElementById('registration_captcha');
    // time used to make sure the image URL is always different
	image.src = baseUrl+"/en/account/captcha" + '?' + (new Date()).getTime();
}

/**
*   Activate the given element, deactivate all others.
*   The tabs (clickable elements) must be named t0,t1,t2...tn
*
*   @param e        : which element should be the active one
*   @param max:     : how many clickable elements are there anyway?
*   @param common   : the common pre-numeric identifier of the tabs.
*/
function replaceActive(element, max, common) {
    //first, let's activate the requested element
    document.getElementById(element).className = "active";

    //then, deactivate the rest
    for (i=0;i<=max;i++) {
        if (common + i != element) {    // ignore the element you just changed
            document.getElementById(common+i).className = "";
        }
    }
}

/**
*   Fade in a pop-up
*   
*   @param windowname - which name to use for the pop-up window
*/
function popup(windowname) {
    $("#backdrop").fadeIn("slow");
    $("#"+windowname).fadeIn("slow");
    $("#"+windowname).focus();
}

/**
*   Fade out backdrop
*   Fade out popup with class ".popup"
*/
function popup_close() {
    $("#backdrop").fadeOut("slow");
    $(".popup").fadeOut("slow");
}

/**
*   Close popup with class ".popup_terms"
*/
function popup_terms_close() {
    $("#backdrop").fadeOut("slow");
    $(".popup_terms").fadeOut("slow");
}

/**
*   Disable all submit buttons in form
*
*   @param form element
*/
function disableSubmit(form) 
{
    if (document.all || document.getElementById) {
        for (i = 0; i < form.length; i++) {
            var tempobj = form.elements[i];
            if (tempobj.type.toLowerCase() == "submit") {
                tempobj.disabled = true;
            }
        }
        return true;
    } else {
        return false;
    }
}

/**
*   Enable all submit buttons in form
*/
function enableSubmit(form) 
{
    if (document.all || document.getElementById) {
        for (i = 0; i < form.length; i++) {
            var tempobj = form.elements[i];
            if (tempobj.type.toLowerCase() == "submit")
            tempobj.disabled = false;
        }
        return true;
    } else {
        return false;
    }
}

/**
*   Redirect with a delay
*
*   @param  url where to redirect
*   @param  time after what time to redirect 
*/
function redirectDelay(url, time)
{
    setTimeout('window.location = "' + url + '"', time);
}

/**
*   returns the XML HTTP object
*/
function getXMLHTTP() {
    var xmlhttp=false;
    try {
        xmlhttp = new XMLHttpRequest();
    }
    catch(e) {
        try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch(e) {
            try {
                xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
            }
            catch(e1){
                xmlhttp = false;
            }
        }
    }
        
    return xmlhttp;
}

/**
*   Needs commenting!!!
*
*   @param strURL
*   @param id
*   @param val
*/
function getItems(strURL, id, val) {
    var req = getXMLHTTP();

    if(id == "content_division_div")
    {
        var select = document.getElementById("content_division");
        select.options.length = 0;
        select.options[select.options.length] = new Option('Select a division', 0);
        
        select = document.getElementById("content_group");
        select.options.length = 0;
        select.options[select.options.length] = new Option('Select a division first', 0);
        
        select = document.getElementById("content_class");
        select.options.length = 0;
        select.options[select.options.length] = new Option('Select a group first', 0);
    }
    else if(id == "content_group_div")
    {
        var select = document.getElementById("content_group");
        select.options.length = 0;
        select.options[select.options.length] = new Option('Select a group', 0);
        
        select = document.getElementById("content_class");
        select.options.length = 0;
        select.options[select.options.length] = new Option('Select a group first', 0);
    }
    else if(id == "content_class_div")
    {
        var select = document.getElementById("content_class");
        select.options.length = 0;
        select.options[select.options.length] = new Option('Select a class', 0);
    }

    if (val != 0 && req) {
        
        req.onreadystatechange = function() {
            if (req.readyState == 4) {
                // only if "OK"
                if (req.status == 200) {						
                    document.getElementById(id).innerHTML=req.responseText;						
                } else {
                    alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                }
            }
        }
        
        req.open("GET", strURL, true);
        req.send(null);
    }
}


/**
*  Inserts data to preview pop-up
*/
function populatePreview() 
{
    // get form elements
	var title = document.getElementById("content_header");
	var tags = document.getElementById("content_keywords");
	var textlead = document.getElementById("content_textlead");
	var textbody = document.getElementById("content_text");
    
    var industry = document.getElementById("content_industry");
	var division = document.getElementById("content_division");
    var group = document.getElementById("content_group");
    
    // "class" is a reserved word, cannot be used for variable names
    var class_ = document.getElementById("content_class");
    
    var innovation = document.getElementById("innovation_type");
    
    // get preview elements
    var title_p = document.getElementById("preview_header");
    var textlead_p = document.getElementById("content_view_textlead_item_p");
    var textbody_p = document.getElementById("content_view_textbody_p");
    
    var tags_p = document.getElementById("content_view_tags_items_p");
    var industry_p = document.getElementById("content_view_industries_item_p");
    var division_p = document.getElementById("content_view_division_item_p");
    var group_p = document.getElementById("content_view_group_item_p");
    var class_p = document.getElementById("content_view_class_item_p");
    
    // START THE ENGINE
    title_p.innerHTML = title.value;
    textlead_p.innerHTML = textlead.value;
    textbody_p.innerHTML = textbody.value.replace(/([^>]?)\n/g, '$1'+ '<br />');

    industry_p.innerHTML = industry[industry.selectedIndex].innerHTML;
    division_p.innerHTML = division[division.selectedIndex].innerHTML;
    group_p.innerHTML = group[group.selectedIndex].innerHTML;
    class_p.innerHTML = class_[class_.selectedIndex].innerHTML;
    
    // Split (explode) the string to array
    var lollero = tags.value.split(",");
    var taglist = "";

    for(i = 0; i < lollero.length; i++){
        if(i == lollero.length-1){
            taglist += '<a href="#">' + lollero[i] + '</a>';
        } else {
            taglist += '<a href="#">' + lollero[i] + '</a>' + ', ';
        }
    }

    tags_p.innerHTML = taglist;
    
    window.scrollTo(0,0);
}

$(document).ready(function() {
	/**
	* Project group selection 
	*/
	$('#project_groups').change(function() {
		var value = $(this).val();
        
        if(value != '' && value != undefined && value != 0) {
            window.open(value);
        }
	});
});

$(document).ready(function() {
	idleInterval = 181000;
	setTimeout("onlineIdle()", idleInterval);
});

function onlineIdle() {
	var json = jQuery.parseJSON($('#jsmetabox').text());
	var url = json.idleRefreshUrl;
	$.ajax({
		type: "POST",
		url: url,
		success: function(msg) {
			setTimeout("onlineIdle()", idleInterval);
		}
	});
}

/**
* Industry selection
* This is jQuery plugin to replace current getItems() function
*/
(function($) {
	/*$.fn.selectIndustry = {
		init: function(o) {
			getI();
		}
	};
	
	function getI() {
		alert('1');
	};*/

	$.fn.industrySelector = function(options) {		
		var options = $.extend({}, $.fn.industrySelector.defaults, options);
		
		/*$(this).bind("click", function() {
			getItems();
		});*/
		
		return this.each(function() {
			
			$.get(
				options.url,
				{
					id: $(this).val(), 
					type: options.type
				},
				function(responseText) {
					$('#'+options.target).html(responseText);
				},
				"html"
			);
		});

	};
	
	/*function getItems() {
		alert(element);
	};*/
	
	$.fn.industrySelector.defaults = {
		//language: "en",
		url: "",
		target: "",
		type: ""
	};
})(jQuery);

/* multiFile
 * 
 * function to create new file input for each file chosen, hides the old one. Also makes a button to make 
 * it possible to remove a chosen file
 *
 * @param   obj      file input object
 * @param  message    translated text for remove file button
 */
function multiFile(obj) {
	var json = jQuery.parseJSON($('#jsmetabox').text());
	var baseUrl = json.baseUrl;

	var allowedFiles = {
		'.doc' 		: 1,
		'.docx' 	: 1,
		'.png' 		: 1,
		'.gif' 		: 1,
		'.jpg' 		: 1,
		'.jpeg' 	: 1,
		'.zip' 		: 1,
		'.xls' 		: 1,
		'.mpp' 		: 1,
		'.pdf' 		: 1,
		'.wmv' 		: 1,
		'.avi' 		: 1,
		'.mkv' 		: 1,
		'.mov' 		: 1,
		'.mpeg' 	: 1,
		'.mp4' 		: 1,
		'.divx' 	: 1,
		'.flv' 		: 1,
		'.ogg'	 	: 1,
		'.3gp' 		: 1,
		'.txt'		: 1
	}
	
	var file = $(obj).val();

	var re = /\..+$/;
    var ext = file.match(re);

	if ( $(":file[value="+file+"]").length == 1 && allowedFiles[ext]) {
		if ($(obj).is(':visible')) {
			$(obj).hide();
			$(obj).before("<input id='content_file_upload' type='file' onchange='multiFile(this);' name='content_file_upload[]' />");
			$(obj).parent().after("<div class='file_row field'><div class='clear' /><img id='removeFile' class='right' src='" + baseUrl + "/images/icon_red_cross.png' style='cursor: pointer'/><div class='content_file_list_file'>"+ file + "</div></div>");
			$("#removeFile").click(function() {
				$(this).parent().remove();
				$(obj).remove();
			});
		}
	}
	else {
		$(obj).val("");
		alert("Error: \nDuplicate file or invalid filetype");
	}
}
/*
$(document).ready(function() {    
    $('#content_industry').change(function() {
        $(this).industrySelector({
            target: 'content_division_div',
            url: 'http://localhost/en/content/ajaxindustry',
            type: 'division'
        });
    });
});*/

/**
* selectAllPrivmsgs
* 
* function to select or unselect all private messages for deletion
*/
function selectAllPrivmsgs()
{
	// Get the form elements
	var elems = document.getElementById('delete_privmsgs');
	var checked = document.delete_privmsgs.select_all.checked;

	// Change values according to the "select_all" checkbox
	for (var i = 1; i < elems.elements.length; i++) {
		elems.elements[i].checked = checked;
	}
}

/**
* selectOnlyThisMsg
* 
* function to select only one message (used when a message's "Delete"-link is pressed)
*/
function selectOnlyThisMsg(id)
{
	// Get the form elements
	var elems = document.getElementById('delete_privmsgs');
	
	// Set everything unchecked
	document.delete_privmsgs.select_all.checked = false;
	for (var i = 1; i < elems.elements.length; i++) {
		elems.elements[i].checked = false;
	}
	
	// Mark as checked only the message that is going to be deleted
	document.getElementById('select_' + id).checked = true;
}

/**
* acceptAllUsrInWaitinglist
*
* function to select or unselect all accept users from waiting list
*/
function acceptAllUsrInWaitinglist()
{
	// Get the form elements
	var elems = document.getElementById('group_waiting_list_form');
	var checked = document.group_waiting_list_form.accept_all.checked;

	// Change values according to the "accept_all" checkbox
	for (var i = 1; i < elems.elements.length; i++) {
        if (elems.elements[i].id[0] == "a")
            elems.elements[i].checked = checked;
	}

	for (i = 1; i < elems.elements.length; i++) {
        if (elems.elements[i].id[0] == "d")
            elems.elements[i].checked = false;
	}
}

/**
* denyAllUsrInWaitinglist
*
* function to select or unselect all deny users from waiting list
*/
function denyAllUsrInWaitinglist()
{
	// Get the form elements
	var elems = document.getElementById('group_waiting_list_form');
	var checked = document.group_waiting_list_form.deny_all.checked;

	// Change values according to the "deny_all" checkbox
	for (var i = 1; i < elems.elements.length; i++) {
        if (elems.elements[i].id[0] == "d")
            elems.elements[i].checked = checked;
	}

    for (i = 1; i < elems.elements.length; i++) {
        if (elems.elements[i].id[0] == "a")
            elems.elements[i].checked = false;
	}
}

/**
* unselectRadiobutton
*
* Function to unselect accept all and deny all radio buttons
*/
function unselectRadiobutton()
{
	document.group_waiting_list_form.accept_all.checked = false;
    document.group_waiting_list_form.deny_all.checked = false;
}
