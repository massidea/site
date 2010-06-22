var inPreview = 0;
var previewId = 'form_content_previewcontent';
var contentId = 'form_content_realcontent';

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
		alert('blocked');
	});
}