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

var inPreview = 0;
var canExit = 0;
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
	
	var inputHelps = {
		'content_header': "<strong>Headline</strong><br /> Grab’s attention, summarize the whole thought and attracts to read the rest of the story.",
		'content_keywords': "<strong>Keywords</strong><br /> Words that capture the essence of the topic of your content. <br /> Are important, since we use them for related content automatization.",
		'content_textlead': "<strong>Lead chapter</strong><br /> Together with headline answers to what, why and whom questions and sum up the whole thought.",
		'content_text': "<strong>Body text</strong><br /> Is elaborating the headline and lead paragraph. Answer following questions:  <br /> 1) What is the insight, <br /> 2) Why the insight is important and valuable, <br /> 3) Who is the target group and whom should be interested, <br /> 4) When (temporal dimension) the insight is topical and <br /> 5) Where (geographical, physical location or circumstances) the insight is topical?",
		'content_related_companies': "<strong>Related companies and organizations</strong><br /> Similar as keywords but present existing companies and organizations, which are related to your insight.",		
		'content_research': "<strong>Research question</strong><br /> The single question in which you need an answer.",
		'content_opportunity': "<strong>Opportunity</strong><br /> Identify the most important opportunity if vision is realized.",
		'content_threat': "<strong>Threat</strong><br /> Identify the most important threat if vision is realized.",
		'content_solution': "<strong>Solution</strong><br /> Summarize your idea's key point to a one sentence.",
		'content_references': "<strong>References</strong><br /> Include references in your content when possible (e.g. website, book or article)."
	};
	                 
	$(allInputs).live('keydown', function(){
		if(this.name != "q") {
			textCount(this);
		}
	});
	
	$(allInputs).live('keyup', function(){
		if(this.name != "q") { 
			textCount(this);
			if (this.name == "content_keywords") textValidation(this);
		}
	});
	
	$(allInputs).each(function(){
		if(this.name != "q") { 
			textCount(this);
			if (this.name == "content_keywords") textValidation(this);
			
			$(this).focus(function (event) {
				$(this).parent().parent().css("z-index",9999);
				$(this).css("position","relative");
				var areaWidth = 729;
				var progressWidth = 109;
				$(this).css("width",areaWidth+"px");
				$("#progressbar_"+this.name).css("position","relative");
				$("#progressbar_"+this.name).css("left",areaWidth-385-progressWidth+"px");
				$("#progressbar_"+this.name).css("top","-23px");
			});
			$(this).blur(function (event) {
				$(this).css("position","");
				$(this).css("width","");
				$("#progressbar_"+this.name).css("position","");
			});
			$(this).qtip({
				content: inputHelps[this.name],
				style: { 
					width: "300",
					background: "#DDDDDD",
					border: {
				      width: 2,
				      radius: 2,
				      color: '#7F9DB9'
				   }
				},
				show: { when: { event: "focus" } },
				hide: { when: { event: "blur" } },
				position: { corner: { target: 'topLeft', tooltip: 'bottomLeft' } }
			});
		}
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
			$(thisProgress).addClass('bad');
			$(thisProgress).html(progressText);
		}
	}
	
	function textCount(obj) {
		var thisMin = inputDefinitions[obj.name][0];
		var thisMax = inputDefinitions[obj.name][1];
		var thisReq = inputDefinitions[obj.name][2];
		// Quick and ugly hack to prevent newline to fail whole validator
		var newLines = $(obj).val().split("\n").length - 1;
		var curLength = $(obj).val().length + newLines;
		var curLeft = (thisMax-curLength);
		
		var thisProgress = $('#progressbar_'+obj.name);

		if(curLength < thisMax) {
			progressText = curLeft + " until limit";
			$(thisProgress).attr('class','limit ok');
		}
		if(curLength > thisMax) {
			progressText = Math.abs(curLeft) + " too many";
			$(thisProgress).attr('class','limit bad');
		}
		if(curLength == thisMax) {
			progressText = "at the limit";
			$(thisProgress).attr('class','limit ok');
		}
		
		if(curLength == 0 && thisReq) {
			progressText = "required";
			$(thisProgress).attr('class','limit bad');
		}

		$(thisProgress).html(progressText);
		
		window.onbeforeunload = unloadWarning;
	}
	
	function selectCheck(obj) {
		if(inputDefinitions[obj.name]) {
			var thisReq = inputDefinitions[obj.name][2];
			var thisProgress = $('#progressbar_' + obj.name);
			if ( $(obj).attr('value') != 0 || thisReq == 0) {
				progressText = "ok";
				$(thisProgress).attr('class', 'limit ok');
			} else {
				progressText = "required";
				$(thisProgress).attr('class', 'limit bad');
			}
			$(thisProgress).html(progressText);
		}
	}
	
	
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
			window.onbeforeunload = null;
			$("#content_publish").val('1');
			$('.content_manage_button').attr('disabled', 'disabled');
			$('#form_content_realcontent').has(this).children('form').submit();
		} else if($(this).attr('id') == "content_save_button") {
			canExit = 1;
			window.onbeforeunload = null;
			$("#content_publish").val('');
			$("#content_save").val('1');
			$('.content_manage_button').attr('disabled', 'disabled');
			$('#form_content_realcontent').has(this).children('form').submit();
		} else if($(this).attr('id') == "content_preview_button") {
			canExit = 0;
			window.onbeforeunload = unloadWarning;
			generatePreview();
		}
	});
});

//Warn user on exit
function unloadWarning()
{
	if(contentHasChanged() && !canExit){
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