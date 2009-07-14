/**
 *	OIBS - Open Innovation Banking System
 *	Javascript-functionality for the website
 *
  *	 This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License 
 * 	as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 * 	
 * 	This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied  
 * 	warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for  
 * 	more details.
 * 	
 * 	You should have received a copy of the GNU General Public License along with this program; if not, write to the Free 
 * 	Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *	
 *	 License text found in /license/ and on the website.
 *	
 *	authors:	Joel Peltonen <joel.peltonen@cs.tamk.fi>
 *	Licence:	GPL v2.0
 */	
 
function window_onload() {
    setTimeout("preCheck()", 30); 
}

window.onload = window_onload();

function preCheck() {
    checkCF();
    textCounter(document.getElementById("content_header"),'progressbar_header',0,100);
    textCounter(document.getElementById("content_keywords"),'progressbar_keywords',0,120);
    textCounter(document.getElementById("content_textlead"),'progressbar_ingress',0,160);
    textCounter(document.getElementById("content_text"),'progressbar_textcontent',1500,4000);
}

/**
*	On the fly validator for the registration form
*/
function validateFormAddContent()
{
	var innovation = document.getElementById("innovation_type");
	var industry = document.getElementById("content_industry");
	var division = document.getElementById("content_division");
	var title = document.getElementById("content_header");
	var keywords = document.getElementById("content_keywords");
	var textlead = document.getElementById("content_textlead");
	var textbody = document.getElementById("content_text");
	
	if (title.value.length == 0
	|| 	textlead.value.length == 0
	||	textbody.value.length < 1500) {
		return false;
	} else if (title.value.length > 100) {
		return false;
	}
	else if (keywords.value.length > 120) {
		return false;
	}
	else if (textlead.value.length > 160) {
		return false;
	}
	else if (textbody.value.length > 4000) {
		return false;
	}
	else if(industry.value == 0) {
		return false;
	}
	else if(division.value == 0) {
		return false;
	}
	else if(innovation.value == 0) {
		return false;
	}
	
	return true;
}

function checkCF() {
	var check = validateFormAddContent();
	if (check == true) {
		enableSubmit(document.getElementById("add_content_form"));
	} else {
		disableSubmit(document.getElementById("add_content_form"));
	}
}

/**
*	Counter for field character count, character count limiter and value changer
*/
function textCounter(field,counter,min,max) {
	var charcnt = field.value.length;   
	object = document.getElementById(counter);

	if (charcnt == min)		// if there are too few characters
	{	
		more = min - charcnt;
		object.innerHTML="Required field,max "+max+" characters";
		setobjpropval(object,"#fee","background");
		setobjpropval(object,"red","color");
	}
	
	if (charcnt < min)		// if there are too few characters
	{	
		more = min - charcnt;
		object.innerHTML="This field requires "+more+" more characters.";
		setobjpropval(object,"#fee","background");
		setobjpropval(object,"red","color");
	}
	
	if (charcnt > min) { 	// if there are enough charavters
		left = max - charcnt;
		object.innerHTML="Field length ok, "+left+" until maximun limit";
		setobjpropval(object,"#efe","background");
		setobjpropval(object,"green","color");
	}
	
	if (charcnt == max)		// if there are too few characters
	{	
		more = min - charcnt;
		object.innerHTML="This field is at maximum length.";
		setobjpropval(object,"#fee","background");
		setobjpropval(object,"red","color");
	}
	
	if (charcnt >= max) { 	// if there are too many characters
		over = charcnt-max;
		object.innerHTML=over+" characters too many";
		// we no longer forcibly cut, simply tell how many chars over the limit.
		//field.value = field.value.substring(0, max-1);		// trim extra chars
		setobjpropval(object,"#fee","background");
		setobjpropval(object,"red","color");
	}
	
	ridiculous = max+50000;			// I concider 50k > max to be ridiculous.
	if (charcnt >= ridiculous) {	// if the charcnt is riciulously long, trim value (helps prevent crashes)
		field.value = field.value.substring(0, max-1);
		object.innerHTML="Field trimmed, value too long.";
		alert("Gigantic pasting or button-stuck may crash browsers. Field trimmed.");
	}
}

/**
*	Change the property of an object.
*
*	@param obj:		what object to change
*	@param val		what value to change to
*	@param prop		what property to change
*/
function setobjpropval(obj,val,prop){
	obj.style[prop] = val;
}