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
 
function preCheck(lang) {
    checkCF();
    textCounter(document.getElementById("content_header"),'progressbar_content_header',1,120,lang);
    textCounter(document.getElementById("content_keywords"),'progressbar_content_keywords',1,120,lang);
    textCounter(document.getElementById("content_textlead"),'progressbar_content_textlead',1,160,lang);
    textCounter(document.getElementById("content_text"),'progressbar_content_text',1000,4000,lang);
    textCounter(document.getElementById("content_related_companies"),'progressbar_content_related_companies',1,120,lang);
    textCounter(document.getElementById("content_campaigns"),'progressbar_content_campaigns',0,120,lang);
    textCounter(document.getElementById("content_references"),'progressbar_content_references',0,2000,lang);
    
    document.getElementById('progressbar_content_header').style.display = "block";
    document.getElementById('progressbar_content_keywords').style.display = "block";
    document.getElementById('progressbar_content_textlead').style.display = "block";
    document.getElementById('progressbar_content_text').style.display = "block";
    document.getElementById('progressbar_content_related_companies').style.display = "block";
    document.getElementById('progressbar_content_campaigns').style.display = "block";
    document.getElementById('progressbar_content_references').style.display = "block";
    
    if(document.getElementById("content_research")) {
        textCounter(document.getElementById("content_research"),'progressbar_content_research',1,120,lang);
        document.getElementById('progressbar_content_research').style.display = "block";
    }
    if(document.getElementById("content_opportunity")) {
        textCounter(document.getElementById("content_opportunity"),'progressbar_content_opportunity',1,120,lang);
        document.getElementById('progressbar_content_opportunity').style.display = "block";
    }
    if(document.getElementById("content_threat")) {
        textCounter(document.getElementById("content_threat"),'progressbar_content_threat',1,120,lang);
        document.getElementById('progressbar_content_threat').style.display = "block";
    }
    if(document.getElementById("content_solution")) {
        textCounter(document.getElementById("content_solution"),'progressbar_content_solution',1,120,lang);
        document.getElementById('progressbar_content_solution').style.display = "block";
    }
}

/**
*	On the fly validator for the AddContent form
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
    var relatedcompanies = document.getElementById("content_related_companies");
	
    if (title.value.length == 0
	|| 	textlead.value.length == 0
	||	textbody.value.length < 1000
    ||  keywords.value.length == 0
    ||  relatedcompanies.value.length == 0) {
		return false;
	} else if (title.value.length > 120) {
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
    else if (relatedcompanies.value.length > 120) {
        return false;
    }
    if(document.getElementById("content_research")) {
        var research = document.getElementById("content_research");
        if(research.value.length == 0 || research.value.length > 120) {
            return false;
        }
    }
    if(document.getElementById("content_opportunity")) {
        var opportunity = document.getElementById("content_opportunity");
        if(opportunity.value.length == 0 || opportunity.value.length > 120) {
            return false;
        }
    }
    if(document.getElementById("content_threat")) {
        var threat = document.getElementById("content_threat");
        if(threat.value.length == 0 || threat.value.length > 120) {
            return false;
        }
    }
    if(document.getElementById("content_solution")) {
        var solution = document.getElementById("content_solution");
        if(solution.value.length == 0 || solution.value.length > 120) {
            return false;
        }
    }
    // These fields aren't required anymore
	/*else if(industry.value == 0) {
		return false;
	}
	else if(division.value == 0) {
		return false;
	}
	else if(innovation.value == 0) {
		return false;
	}*/
	
	return true;
}

function checkCF() {
	var check = validateFormAddContent();
	var publishButton = document.getElementById("content_publish");
	if (check == true) {
		if (publishButton != null)
			publishButton.disabled = false;
		
        document.getElementById("content_save").disabled = false;
	} else {
		if (publishButton != null)
			publishButton.disabled = true;
        document.getElementById("content_save").disabled = true;
	}
}

/**
*	Counter for field character count, character count limiter and value changer
*/
function textCounter(field,counter,min,max,lang) {
	var charcnt = field.value.length;   
	object = document.getElementById(counter);
    
    // Initializing variables for progress bar texts, default texts are in english
    var required = "required";
    var needed = " needed";
    var until_limit = " until limit";
    var at_the_limit = "at the limit";
    var too_many = " too many";
    
    if(lang == "fi") {
        required = "pakollinen";
        needed = " lisÃ¤Ã¤";
        until_limit = " rajaan";
        at_the_limit = "rajalla";
        too_many = " liikaa";
    }

    // If the field is empty
    if (charcnt == 0)
    {	
        object.innerHTML=required;
        object.className = "progress";
    }
    
    // If there's enough characters
    if (charcnt >= min) {
		left = max - charcnt;
		object.innerHTML=left+until_limit;
        object.className = "progress_ok";
	}
	
    // If the maximum character amount is reached
    if (charcnt == max)		
	{	
		object.innerHTML=at_the_limit;
		object.className = "progress_ok";
	}
    
    // If the maximum character amount is exceeded
    if (charcnt > max) {
		over = charcnt-max;
		object.innerHTML=over+too_many;
        object.className = "progress";
		// we no longer forcibly cut, simply tell how many chars over the limit.
		//field.value = field.value.substring(0, max-1);		// trim extra chars
	}
    
    // If the field is not empty but character amount is below required amount
    if (charcnt < min && charcnt != 0)
	{	
		more = min - charcnt;
		object.innerHTML=more+needed;
		object.className = "progress";
	}
	
    // I concider 50k > max to be ridiculous.
	ridiculous = max+50000;
	// If the charcnt is riciulously long, trim value (helps prevent crashes)
    if (charcnt >= ridiculous) {
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
	obj.style.prop = val;
}