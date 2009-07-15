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
*   oldReloadCaptcha - The captcha changer
*
*   What kind of trickery is this? 
*   Most of this code is completely useless and serves no purpose... 
*   Or does it?
*
*   @param num:	security-related passnumber
*/
function oldReloadCaptcha(num){
    image 	= document.getElementById('captcha_image');
    link 	= document.getElementById('a-reloadcaptcha');
    num 	= Math.floor(Math.random()*11);	// do you see what I did here? ;)

    image.src = "/captcha/png?id=secret-reload-" + num;
    link.href = "javascript:reloadCaptcha('"+num+"')";
}

/**
*	The second captcha changer, now with baseurl
*
*   @param baseUrl base url
*/
function reloadCaptcha(baseUrl){
	image 	= document.getElementById('registration_captcha');
	image.src = baseUrl+"/en/account/captcha" + '?' + (new Date()).getTime();
}

/**
*	Hide or show an element and change some innerhtml related or more from user -block
*
*	@param e:		Affected element
*	@param a:		The link element which needs the image changed
*	@param t:		The link text after the image is changed
*/
function hideshow2(e, a, t) {
    if (e.length < 1) { return; }
    element = document.getElementById(e);
    link = document.getElementById(a);

    // search the innerHTML of the link element, if right to down, change image.. and the opposite
    if (link.innerHTML.search(/down/i) >= "0") {
        link.innerHTML = "<img src=\"/images/icon_arrow_right.png\" alt=\"\" /> " + t;
    } else {
        link.innerHTML = "<img src=\"/images/icon_arrow_down.png\" alt=\"\" /> " + t;
    }

    if (element.style.display == "none") {
        element.style.display = "block"; 
    } else { 
        element.style.display = "none"; 
    }
}

/**
*   Hide or show an element and change some innerhtml related or more from user -block
*
*   @param e:       Affected element
*   @param a:       The link element which needs the image changed
*/
function cycleMoreBox(e, a) {
    if (e.length < 1) { return; }
    element = document.getElementById(e);
    link = document.getElementById(a);

    // search the innerHTML of the link element, if right to down, change image.. and the opposite
    if (link.innerHTML.search(/minus/i) >= "0") {
        link.innerHTML = "<img src=\"/images/icon_plus_tiny.png\" alt=\"\" /> ";
    } else {
        link.innerHTML = "<img src=\"/images/icon_minus_tiny.png\" alt=\"\" /> ";
    }

    if (element.style.display == "none") {
        element.style.display = "block"; 
    } else { 
        element.style.display = "none"; 
    }
}

/**
*   Hide or show an element and change innerhtml (link text)
*
*   @param e1:      Element to hide / show
*   @param e2:      Element to change text to
*/
function hideshow3(e1, e2) {
    if (e1.length < 1) { return; }
    element1 = document.getElementById(e1);
    element2 = document.getElementById(e2);

    if (element1.style.display == "none") {
        element1.style.display = "block"; 
        element2.innerHTML = "Show less";
    } else { 
        element1.style.display = "none"; 
        element2.innerHTML = "Show more";
    }
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
        if (common + i != element) {    // don't deactivate the element you just changed
            document.getElementById(common+i).className = "";
        }
    }
}

/** wtf */
function changeIndustry(initial)
{
    var url = document.location.href;
    if (url.indexOf('?') != -1)
    {
        if (url.indexOf('industryinitial') != -1)
        {
            var letter = initial;
            var position = url.indexOf('industryinitial')+16;
            var url = setCharAt(url, position, letter);

            window.location = url;
        }
        else
        {
            window.location = url +"&industryinitial=" +initial;
        }
    }
    else
    {
        window.location = url +"?industryinitial=" +initial;
    }
}

/* TäSTä JATKUU */
function changeUrlValue(value, section, cnttype)
{
    var url = "http://oibs2.projects.tamk.fi/content/editcontent/" +cnttype;
    if (section == "industry")
    {
        if(value == 0)
        {
            window.location = url;
        }
        else
        {
            url = url + "?industryinitial=" + value;
            window.location = url;
        }
    }
    else if(section == "division")
    {
        if(value == 0)
        {
            window.location = url;
        }
        else
        {
            if (url.indexOf('?') != -1)
            {
                
                if (url.indexOf('industryinitial=') != -1)
                {
                    url = url + "&divisionid=";
                    var position = url.indexOf('industryinitial=')+29;
                    var url = setCharAt(url, position, value);
                }
            }
        }
    }
}

function replyToComment(id)
{
    var d = document.getElementById("replyto");
    d.value = id;
}

/**
*	Combine the previous functions to a container / loader function to generate the plop-up
*/
function popup(windowname) {	
		$("#backdrop").fadeIn("slow");
		$("#"+windowname).fadeIn("slow");
}

/**
 *	Close popup
 */
function popup_close(windowname) {
		$("#backdrop").fadeOut("slow");
		$("#"+windowname).fadeOut("slow");	
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
*   Enable all submit btns in form
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
*   Submit content form
*/
function submitForm(field)
{
    if(field == "industry")
    {
        document.getElementById('add_content_form').submit();
        /*// Get the index of selected option
        var index = document.getElementById('division_').selectedIndex;
        // Get the option object
        var option = document.getElementById('division_')[index];
        // Change it to 0
        option.value = 0;
        
        if(document.getElementById('group_') != null)
        {
            var index2 = document.getElementById('group_').selectedIndex;
            var option2 = document.getElementById('group_')[index];
            option2.value = "";
        }*/
    }
    /*else if(field == "division")
    {
        if(document.getElementById('group_') != null)
        {
            var index = document.getElementById('group_').selectedIndex;
            var option = document.getElementById('group_')[index];
            option.value = "";
        }
        
        if(document.getElementById('class_') != null)
        {
            var index2 = document.getElementById('class_').selectedIndex;
            var option2 = document.getElementById('class_')[index];
            option2.value = "";
        }
    }
    else if(field == "group")
    {
        if(document.getElementById('class_') != null)
        {
            var index = document.getElementById('class_').selectedIndex;
            var option = document.getElementById('class_')[index];
            option.value = "";
        }
    }

    document.getElementById('content').submit(); */
    }


/**
*   Redirect with a delay
*
*   @param  url string  where to redirect
*   @param  time    string  when (seconds)
*/
function redirectDelay(url, time)
{
    setTimeout('window.location = "'+url+'"', time);
    
}

/************* Horrible image changer for the front page ****/
/**
*   @param to: image to be changed to src
*   @param from: image to be changed from as this object
*   @param langcat: language(from php)
*/
function imageChanger(to, langcat) {
    from = document.getElementById("frontpage_imagemap");

    //alert(langcat);
    if (langcat == "fi") {
            newImage = "/images/Oibs_frontpage_finnish_" + to + ".png";
    } else {
            newImage = "/images/Oibs_frontpage_" + to + ".png";
    }
    from.src = newImage;
}

/**
*   @param  x: change placeholer to show This.
*/
function frontpageChanger(x) {
    var placeholder = document.getElementById("placeholder");
    z = document.getElementById(x);
    placeholder.innerHTML = z.innerHTML;
}

function getXMLHTTP() { //fuction to return the xml http object
    var xmlhttp=false;
    try {
        xmlhttp=new XMLHttpRequest();
    }
    catch(e) {
        try {
            xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
        } catch(e) {
            try {
                xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
            }
            catch(e1){
                xmlhttp=false;
            }
        }
    }
        
    return xmlhttp;
}

function getItems(strURL, id, val) {
    var req = getXMLHTTP();

    if(id == "divisiondd")
    {
        var select = document.getElementById("content_division");
        select.options.length = 0;
        select.options[select.options.length] = new Option('Select a division', 0);
        
        select = document.getElementById("content_group");
        select.options.length = 0;
        select.options[select.options.length] = new Option('Select a group (optional)', 0);
        
        select = document.getElementById("content_class");
        select.options.length = 0;
        select.options[select.options.length] = new Option('Select a class (optional)', 0);
    }
    else if(id == "groupdd")
    {
        var select = document.getElementById("content_group");
        select.options.length = 0;
        select.options[select.options.length] = new Option('Select a group (optional)', 0);
        
        select = document.getElementById("content_class");
        select.options.length = 0;
        select.options[select.options.length] = new Option('Select a class (optional)', 0);
    }
    else if(id == "classdd")
    {
        var select = document.getElementById("content_class");
        select.options.length = 0;
        select.options[select.options.length] = new Option('Select a class (optional)', 0);
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
*   populatePreview() - insert data to preview pop-up
*
*
*/
function populatePreview() 
{
    // get form elements
    var innovation = document.getElementById("innovation_type");
	var industry = document.getElementById("content_industry");
	var division = document.getElementById("content_division");
	var title = document.getElementById("content_header");
	var tags = document.getElementById("content_keywords");
	var textlead = document.getElementById("content_textlead");
	var textbody = document.getElementById("content_text");
    
    // get preview elements
    var title_p = document.getElementById("preview_header");
    var textlead_p = document.getElementById("content_view_textlead_item_p");
    var textbody_p = document.getElementById("content_view_textbody_p");
    
    var tags_p = document.getElementById("content_view_tags_items_p");
    var industry_p = document.getElementById("content_view_industries_item_p");
    var division_p = document.getElementById("content_view_division_item_p");
    
    // START THE ENGINE
    title_p.innerHTML = title.value;
    textlead_p.innerHTML = textlead.value;
    textbody_p.innerHTML = textbody.value.replace(/([^>]?)\n/g, '$1'+ '<br />');

    industry_p.innerHTML = industry[industry.selectedIndex].innerHTML;
    division_p.innerHTML = division[division.selectedIndex].innerHTML;
    
    // Split (explode) the string to array
    var lollero = tags.value.split(",");
    var taglist = "";

    for(i = 0; i < lollero.length; i++){
        if(i == lollero.length-1){
            taglist += lollero[i];
        } else {
            taglist += lollero[i] + ", ";
        }
    }

    tags_p.innerHTML = lollero;
    
    window.scrollTo(0,0);
}