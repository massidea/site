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

$(document).ready(function() {
	// Get all input elements
	var allInputs = $(":input[type=text], :input[type=textarea]");

	$(allInputs).live('keydown keyup', function(){
		textCount(this);
	});

	$('select').live('change keyup', function() {
		selectCheck(this);
	});

	function textCount(obj) {
		var thisMin = inputDefinitions[obj.name][0];
		var thisMax = inputDefinitions[obj.name][1];
		var thisReq = inputDefinitions[obj.name][2];
		var curLength = $(obj).val().length;
		var curLeft = (thisMax-curLength);
		var thisProgress = $('#progressbar_'+obj.name);

		if(curLength < thisMax) {
			progressText = curLeft + " until limit";
			$(thisProgress).attr('class','progress_ok');
		}
		if(curLength > thisMax) {
			progressText = Math.abs(curLeft) + " too many";
			$(thisProgress).attr('class','progress');
		}
		if(curLength == thisMax) {
			progressText = "at the limit";
			$(thisProgress).attr('class','progress_ok');
		}
		
		if(curLength == 0 && thisReq) {
			progressText = "required";
			$(thisProgress).attr('class','progress');
		}

		$(thisProgress).html(progressText);
	}
	
	function selectCheck(obj) {
		if($(obj).attr('id') != "project_groups") {
			var thisReq = inputDefinitions[obj.name][2];
			var thisProgress = $('#progressbar_' + obj.name);
			if ( $(obj).attr('value') != 0 || thisReq == 0) {
				progressText = "ok";
				$(thisProgress).attr('class', 'progress_ok');
			} else {
				progressText = "required";
				$(thisProgress).attr('class', 'progress');
			}
			$(thisProgress).html(progressText);
		}
	}
	
	// Precheck on page load
	$(allInputs).each(function(){
        // Quick & dirty fix.
		if (this.name != "q" &&
            this.name != "campaign_start")
        {
            textCount(this);
        }
	});
	
	$('select').each(function() {
		if ($(this).attr('id') != "languages") { 
			selectCheck(this);
		}
	});
});

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