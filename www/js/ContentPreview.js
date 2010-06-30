var inPreview = 0;
var tmpFormData;
var previewId = 'form_content_previewcontent';
var contentId = 'form_content_realcontent';

$(document).ready(function(){
	tmpFormData = getPreviewData();
});

// Warn user on exit
window.onbeforeunload = unloadWarning;
function unloadWarning()
{
	if(contentHasChanged()){
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