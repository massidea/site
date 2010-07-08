/**
 *	ContentPreview.js - Javascript functionality to add/edit content actions
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
 *	License text found in /license/ and on the website.
 *	
 *	authors:	Jaakko Paukamainen <jaakko.paukamainen@student.samk.fi>
 *	Licence:	GPL v2.0
 */	

var canExit = 0;
var inPreview = 0;
var tmpFormData;
var previewId = 'form_content_previewcontent';
var contentId = 'form_content_realcontent';

$(document).ready(function(){
	tmpFormData = getPreviewData();
	$('#content form button').click(function(){
		canExit = 1;
	});
});

// Warn user on exit
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