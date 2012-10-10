var interval = 5000;
var maxLevel = 3;

$("document").ready(function () {
	setTimeout("refreshComments(true)", interval);
	$("#commentPostButton").click(function() {
		postComment();
	});
	
	$('textarea#commentTextarea').autoResize({
	    extraSpace : 30,
	    limit : 10000000000
	});
	$('textarea#commentTextarea').resize();
	
	$("#content_view_comment_form_container > p > #commentTextarea").bind("keydown keyup change",function(){
		var limit = 1000;
		var newLines = $(this).val().split("\n").length - 1;
		var length = $(this).val().length + newLines;
		if(limit < length)
			$("#comment_character_cut").show();
		else $("#comment_character_cut").hide();
		$("#comment_character_count").html(length+"/"+limit);
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
	$('textarea#commentTextarea').resize();
	data = new Array();

	data = {'msg': message.substring(0,1000), 'parent': parent };
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
					var scrollToComment = !timer;
					addCommentRow(value.id, value.parent, value.commentDiv, scrollToComment);
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
function addCommentRow(id, parent, div, scrollToComment) {
	scrollToComment = (typeof(scrollToComment) != 'undefined') ? scrollToComment : false;
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
	if (scrollToComment) {
		$(window).scrollTop($("div#content_view_comment_"+id+"_container").offset().top);
	}
}

/**
 * clearForm
 * 
 * function to clear for and reset reply after posting
 */
function clearForm() {
	$("#comment_character_count").html("0/1000");
	$("#comment_character_cut").hide();
	$("#commentTextarea").val("");
	cancelReply();
}

/**
*   replyTo
*   
*   Basic function for comment replying
*/
function replyTo (replyId, username, rating) {
    // Apply values
    var body = $("div#content_view_comment_"+replyId+"_textbody").html();
    var usr = username + '(' + rating + ')';
    var linkToUser = usr.link(jsMeta.baseUrl+"/en/account/view/user/"+username);

    $('#comment_parent').val(replyId);
    $('#replying_to').html("Replying to "+ linkToUser + ":");
    $('#reply_body').html(body);

    // Show "Replying to..." text and "Cancel reply" -link
    $('p#replying_to').show();
    $('a#cancel_reply_link').show();
    
    $(window).scrollTop($('#content_view_comment_form_container').offset().top);
    
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
    $('#reply_body').html('');
    
    // Hide "Replying to..." text and "Cancel reply" -link
    $('p#replying_to').hide();
    $('a#cancel_reply_link').hide();
    
    $(window).scrollTop($('#content_view_comment_form_container').offset().top);
    
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
