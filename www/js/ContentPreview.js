var inPreview = 0;
var previewId = 'form_content_previewcontent';
var contentId = 'form_content_realcontent';
var tmpPreviewData;

function generatePreview()
{
	alert('woot!');
	if(inPreview==0)
	{
		$.ajax({
			type: 'POST',
			url: previewUrl,
			data: getPreviewData(),
			success: function(html){
				$('#'+previewId).html(html);
			}
		});
	}
	//switchDiv(contentId, previewId);
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

/*
function switchDiv(div1, div2)
{
	if(inPreview==0){
		inPreview = 1;
	} else if(inPreview==1) {
		inPreview = 0;
	}

	var tmpDiv = $('#'+div1).clone();
	$('#'+div1).html($('#'+div2).html());
	$('#'+div2).html(tmpDiv);
}
*/

$(document).ready(function(){
	$('body').append('<h1><a href="#" onclick="generatePreview(); return false;">KLICKKAA</a></h1>');
	//$('body').append('<div id="'+previewId+'" style="display: none;"></div>');
});