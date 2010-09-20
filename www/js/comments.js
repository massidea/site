var interval = 5000;
var maxLevel = 3;

$("document").ready(function () {
	setTimeout("refreshComments(true)", interval);
	$("#commentPostButton").click(function() {
		postComment();
	});
});

/**
 * postComment
 * 
 * functionality of the post comment button
 */
function postComment() {
	var message = $("#commentTextarea").val();
	var parent = $("#comment_parent").val();
	clearForm();
	data = new Array();

	data = {'msg': message, 'parent': parent };
	$.ajax({
		type: "POST",
		//async: false,
		url: jsMeta.commentUrls[0].postCommentUrl,
		data: data,
		success: function(msg) {
			refreshComments(false);
		}
	});
}

function isScrolledIntoView(elem)
{
	if ($(elem).length == 0) return 0;
    var docViewTop = $(window).scrollTop();
    var docViewBottom = docViewTop + $(window).height();

    var elemTop = $(elem).offset().top;
    var elemBottom = elemTop + $(elem).height();

    return ((elemBottom >= docViewTop) && (elemTop <= docViewBottom));
}


/** 
 * refreshComments
 * checks for new comments
 * 
 * @param timer bool, if new timer should be made after running the function
 */
function refreshComments(timer) {
	if (isScrolledIntoView("#content_view_comments")) {
		$.getJSON(jsMeta.commentUrls[0].getCommentsUrl, function(data) {
			if (data != "0") {
				$.each(data, function(key, value) {
					addCommentRow(value.id, value.parent, value.commentDiv);
				});
			}
		});
	}
	if (timer) setTimeout("refreshComments(true)", interval);
}

/**
 * addCommentRow
 * 
 * function to add new comments to page
 * 
 * @param id		id of the comment
 * @param parent	parent of the comment
 * @param div		commentrow
 * @return
 */
function addCommentRow(id, parent, div) {
	target = "";
	level = "";
	if (parent != 0) {
		target = $("div#content_view_comment_"+parent+"_container");
		level = parseInt($(target).attr('class').replace("content_view_comment_container_", "")) + 1;
	} else {
		target = $("div#content_view_comments_header");
		level = "0";
	}

	if (level > maxLevel) level = maxLevel;
	
	if (! $('div#content_view_comments_header').is(':visible')) $('div#content_view_comments_header').css("display", "block");
	$(target).after(div);
	
	$("div#content_view_comment_"+id+"_container").removeClass("content_view_comment_container_");
	$("div#content_view_comment_"+id+"_container").addClass("content_view_comment_container_" + level);
	$("div#content_view_comment_"+id+"_container").effect("highlight", {}, 2000);	
}

/**
 * clearForm
 * 
 * function to clear for and reset reply after posting
 */
function clearForm() {
	$("#commentTextarea").val("");
	cancelReply();
}

/**
*   replyTo
*   
*   Basic function for comment replying
*/
function replyTo (replyId, username) {
    // Apply values
    $('#comment_parent').val(replyId);
    $('#replying_to').html("Replying to "+username);

    // Show "Replying to..." text and "Cancel reply" -link
    $('p#replying_to').show();
    $('a#cancel_reply_link').show();
    
    $('#commentTextarea').focus();
    
    return false;
}

function trimmer (str) {
    var	str = str.replace(/^\s\s*/, ''),
        ws = /\s/,
        i = str.length;
    while (ws.test(str.charAt(--i)));

    return str.slice(0, i + 1);

}

/**
*   cancelReply
*   
*   Basic function for canceling replying
*/
function cancelReply () {
    // Apply values
    $('#comment_parent').val(0);
    $('#parent_username').val('');
    
    // Hide "Replying to..." text and "Cancel reply" -link
    $('p#replying_to').hide();
    $('a#cancel_reply_link').hide();
    
    return false;
}

/**
*   flagAsSpam
*   
*   Basic function for flagging comments as spam
*/
function flagAsSpam(commentId)
{
	var replaceCommentText;
	$.ajax({
		type: "POST",
		url: jsMeta.commentUrls[0].flagAsSpamUrl,
		data: "flaggedid=" + commentId,
		success: function(msg){
			if(msg == '1') { replaceCommentText = 'Flagged'; }
			if(msg == '0') { replaceCommentText = 'Already flagged'; }
			$('#flagSpamLink_'+commentId).replaceWith(replaceCommentText);
		}
	});
}

/**
*   deleteComment
*
*   Basic function for deleting comment
*/
/*
deleteComment = function (cmtid)
{
    // Show "Are you sure..." text and "Yes / No" -link
    $('div#content_view_comment_'+cmtid+'_textbody').hide();
    $('div#delete_comment_text_'+cmtid).show();

    return false;
}*/

/**
*   undodeleteComment
*
*   Basic function for forget deleting comment
*/
/*
undodeleteComment = function (cmtid)
{
    // Show "Are you sure..." text and "Yes / No" -link
    $('div#content_view_comment_'+cmtid+'_textbody').show();
    $('div#delete_comment_text_'+cmtid).hide();

    return false;
}*/
