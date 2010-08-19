/**
 *	ContentForm.js - Javascript functionality to add/edit content actions
 *
 *	This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License 
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
 *				Jaakko Paukamainen <jaakko.paukamainen@student.samk.fi>
 *	Licence:	GPL v2.0
 */	

var canExit = 0;
var inPreview = 0;
var tmpFormData;
var previewId = 'form_content_previewcontent';
var contentId = 'form_content_realcontent';

$(document).ready(function() {
	tmpFormData = getPreviewData();
	
	// Get all input elements
	var allInputs = $(":input[type=text], :input[type=textarea]");

	// Definitions for input boxes ([0] = minimum, [1] = maximum, [2] = required (1 true/0 false)
	var inputDefinitions = {
		'content_header': 				[1,  140, 1],
		'content_keywords': 			[1,  120, 1],
		'content_textlead': 			[1,  320, 1],
		'content_text': 				[0, 4000, 0],
		'content_header': 				[1,  140, 1],
		'content_related_companies':	[0,  120, 0],		
		'content_research': 			[1,  140, 1],
		'content_opportunity': 			[1,  140, 1],
		'content_threat': 				[1,  140, 1],
		'content_solution': 			[1,  140, 1],
		'content_references': 			[0, 2000, 0],
		'content_language':				[0,    0, 1]
	};
	
	var inputValidations = { 
		'content_keywords':				XRegExp("^[\\p{L}0-9, ]*$")		
	}; 
	                 
	$(allInputs).live('keydown', function(){
		textCount(this);
	});
	
	$(allInputs).live('keyup', function(){
		textCount(this);
		if (this.name == "content_keywords") textValidation(this);
	});

	$('select').live('change keyup', function() {
		selectCheck(this);
	});

	function textValidation(obj) {
		var thisProgress = $('#progressbar_'+obj.name);
		var regex = inputValidations[obj.name];
		if (regex.test($(obj).val())) { }
		else {
			progressText = "Tag not valid!";
			$(thisProgress).attr('class','progress');
			$(thisProgress).html(progressText);
		}
	}
	
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
		if(inputDefinitions[obj.name]) {
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
		if(this.name != "q") { 
			textCount(this);
			if (this.name == "content_keywords") textValidation(this);
		}
	});
	
	$('select').each(function() {
		if ($(this).attr('id') != "languages") { 
			selectCheck(this);
		}
	});
	
	/**
	 * Set content publish button to disabled after click
	 * and submit form.
	 */
	$('.content_manage_button').click(function() {	
		if($(this).attr('id') == "content_publish_button") {
			canExit = 1;
			$("#content_publish").val('1');
			$('.content_manage_button').attr('disabled', 'disabled');
			$('#form_content_realcontent').has(this).children('form').submit();
		} else if($(this).attr('id') == "content_save_button") {
			canExit = 1;
			$("#content_save").val('1');
			$('.content_manage_button').attr('disabled', 'disabled');
			$('#form_content_realcontent').has(this).children('form').submit();
		} else if($(this).attr('id') == "content_preview_button") {
			canExit = 0;
			generatePreview();
		}
	});
});

//Warn user on exit
window.onbeforeunload = unloadWarning;
function unloadWarning()
{
	if(contentHasChanged() && !canExit){
		canExit = 0;
		switch(inPreview){
		case 0:
			return "You have made changes to your content that have not yet been saved. Exiting now will abandon them.";
			break;
		case 1:
			return "You are currently in preview mode, exiting now will abandon your changes to your content.";
			break;
		}		
	}
}

function contentHasChanged()
{
	if(tmpFormData == getPreviewData()){
		return 0;
	}
	else if(tmpFormData != getPreviewData()){
		return 1;
	}
}

function generatePreview()
{
	if(inPreview==0)
	{
		$.ajax({
			type: 'POST',
			url: previewUrl,
			data: getPreviewData(),
			success: function(html){
				$('#'+previewId).html(html);
				disableLinks();
			}
		});
	}
	toggleDiv();
}

function getPreviewData()
{
	return $('#content form').serialize();
}

function toggleDiv()
{
	if(inPreview==0){
		inPreview = 1;
		$('#'+contentId).fadeOut('normal', function(){
			$('#'+previewId).fadeIn();
		});
	} else if(inPreview==1) {
		inPreview = 0;
		$('#'+previewId).fadeOut('normal', function(){
			$('#'+contentId).fadeIn();
		});
	}
}

function disableLinks()
{
	$('#'+previewId+' a').click(function(e){
		e.preventDefault();
	});
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