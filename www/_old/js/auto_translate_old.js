//jquery.js is required for this js file

google.load("language", "1");      

$(document).ready(
    function(){
        //get target language code
        lanCode = $('#translate-language-code-value').val();
        if(lanCode==undefined)return;
	
	//change the value of language select box
	$('#languages').val(lanCode);	
        
	//translate the divs user_summary_list_without_stats. For index page
	translate_foreach_item('user_summary_list_without_stats',lanCode);
        //translate the divs contain class content_container_left. For challenges, visions, view ideas, and search results.
        translate_foreach_item('content_container_right',lanCode);
	//translate the divs contain class content_view_comment. For comments.
	translate_foreach_item('content_view_comment',lanCode);
	//translate the divs contain solutions_child_row_content. For solution.
	translate_foreach_item('solutions_child_row_content',lanCode);
	
	//translate content
	var relateClassList = new Array();
	relateClassList.push('content_view_comment');
	relateClassList.push('content_view_user_related_entry_finfo');
	relateClassList.push('content_view_user_related_entry_idea');
	relateClassList.push('content_view_user_related_entry_problem');
	relateClassList.push('solutions_child_row_content');
	content_translate_with_panel("view_content_left_main",lanCode,relateClassList);
    }
);
/**
 * Translate the target div, and add a translation panel on it
 * @param id {string}
 *   id of the HTML element
 * @param lanCode {string}
 *  code of target translate language
 * @param relateClassList {Array}
 *  list of class name where the content also need to be translated.
 */
function content_translate_with_panel(id, lanCode, relateClassList){
    //translate the content view: content_view_content_container
    var content_view = document.getElementById(id);
    if(content_view){
	var content_str = getElementContents(content_view);
	if(content_str.length>500)
        {
            content_str = content_str.substring(0,500);
        }
	//detect the language
	google.language.detect(content_str, function(result) {
	    if (!result.error) {
		//lanCode = 'fi';
		if(result.language!=lanCode)
		{
		    //get language name
		    var ori_language = getLanguageName(result.language);
		    var tar_language = getLanguageName(lanCode);
			
		    var $c_view = $('#'+id);
		    //copy the content
		    var $clone_view = $c_view.clone().attr('id',id+'_copy').css('display','none');
			
		    //create translate panel
		    var $t_panel = $('<div/>').css('float','right').css('background-color','#EEE').css('border','1px solid #CCC').css('text-align','center').css('padding','5px');
		    $t_panel.append("<p>This content has been translated</p><p><a href='javascript:show_content_in_translated_one("+'"'+id+'"'+")' id='"+id+"_tlan_link' style='display:none' >Show translation</a><a href='javascript:show_content_in_original_one("+'"'+id+'"'+")' id='"+id+"_olan_link'>Back to "+ori_language+"</a></p>");
		var $olan_select = $('<select/>');
		for(var key in google.language.Languages){
		    $olan_select.append("<option value='"+google.language.Languages[key]+"'>"+key+"</option>");
		}
		$olan_select.val(result.language);
		var $tlan_select = $olan_select.clone();
		$tlan_select.val(lanCode);
		$t_panel.append($olan_select).append('<br/>').append($tlan_select);
		var $translate_button = $('<input/>').attr('type','button').val('Translate').css('margin-right','30px').css('float','right');
		$translate_button.click(function(){
		    $c_view.html($clone_view.html());
		    var list = new Array();
		    list.push(content_view);
		    translate_elements($olan_select.val(),$tlan_select.val(),list);
		    show_content_in_translated_one(id);
		    for(var key in relateClassList){
			translate_foreach_item(relateClassList[key],$tlan_select.val());
		    }
		});
		$t_panel.append('<br/>').append($translate_button);
		$c_view.wrap('<div id="'+id+'_outer"></div>');
		$('#'+id+'_outer').prepend($t_panel).append($clone_view);
			
		//begin to translate...
                var e_list = new Array();
                e_list.push(content_view);
                translate_elements('',lanCode,e_list);
	    }
	}
    });
	}
}
/**
 * translate foreach item's content
 * @param className
 *     {string} the class name of the element
 * @param lanCode
 *     {string} the translate target language code
 * @param appendClass (option)
 *     {string} class name of the element where to append the translation info to
 */
function translate_foreach_item(className, lanCode, appendClass){
    $('.'+className).each(
        function (i) {
            if($(this).hasClass('not_translated')){
                return;
            }
	    if($(this).hasClass('translate_copy_one')){
		this.parentNode.removeChild(this);
                return;
	    }
	    $(this).css('display','block');
	    $(this).find('p').remove('.translate');
            var $object = $(this);
                
            var content_str = getElementContents(this);             
                
            if(content_str.length>500)
            {
                content_str = content_str.substring(0,500);
            }
            //detect the language
            google.language.detect(content_str, function(result) {
                if (!result.error) {
                    //lanCode = 'fi';
                    if(result.language!=lanCode){
			
                        //get language name
                        var ori_language = getLanguageName(result.language);
                        if(ori_language=='')
                        {
                            ori_language = 'unknow';
			    return;
                        }
                        var tar_language = getLanguageName(lanCode);
                        var $pclone = $object.clone();
                        //set id for object, if the id are not exist.
                        var o_id = $object.attr('id');
                        if(o_id==undefined||o_id==''){
                            $object.attr('id',className+'_'+i);
                            o_id = $object.attr('id');
                        }
                        //set id for clone object and mark with class translate_copy_one
                        $pclone.attr('id',o_id+'_translate');
			$pclone.addClass('translate_copy_one');
                        
                        //add translate info <p class="translate">[<a href="javascript:show_translated_content('o_id')"/>Show translate</a>, translate to tar_language]</p>
                        $object.append('<p class="translate" style="color:#999999;font-size:10px;padding:0px">[<a href="javascript:show_translated_content('+"'"+o_id+"'"+')"> Show translate</a>, translate to '+tar_language+' ]</p>');
                        $pclone.append('<p class="translate" style="color:#999999;font-size:10px;padding:0px">[<a href="javascript:show_original_content('+"'"+o_id+"'"+')"> Show original</a>, translate from '+ori_language+' ]</p>');
                            
                        $object.after($pclone);
                        //hide original one
                        $object.css('display','none');
                            
                            
                        //begin translating...
                        var e = document.getElementById(o_id+"_translate");
                        var e_list = new Array();
                        e_list.push(e);
                        translate_elements('',lanCode,e_list);
                            
                        
                    }
                }
            });
               	
        }
    );
}
/**
 * show original content and hiden the translated one
 */
function show_content_in_translated_one(id){
    $('#'+id+'_tlan_link').css('display','none');
    $('#'+id+'_olan_link').css('display','block');
    $('#'+id).css('display','block');
    $('#'+id+'_copy').css('display','none');

}
/**
 * show translated one and hiden the original content
 */
function show_content_in_original_one(id){
    $('#'+id+'_tlan_link').css('display','block');
    $('#'+id+'_olan_link').css('display','none');
    $('#'+id).css('display','none');
    $('#'+id+'_copy').css('display','block');

}

/**
 * show original content and hiden the translated one
 */
function show_original_content(id){
    $('#'+id).css('display','block');
    $('#'+id+'_translate').css('display','none');
}
/**
 * show translated on and hiden the orginal content
 */
function show_translated_content(id){
    $('#'+id).css('display','none');
    $('#'+id+'_translate').css('display','block');
}


/**
 * get language name
 */
function getLanguageName(lanCode)
{
    for(var key in google.language.Languages)
    {
        if(google.language.Languages[key] == lanCode)
            return key;
    }
    return '';
}

/**
 * button onclick function for Translate
 */
/*function translate_button_onclick(){
    //get the target translation language code
    var language_select = document.getElementById("select_target_language");
    var language_original = document.getElementById("select_original_language");
    var tlangCode;
    var olangCode;
    for(var i=0;i<language_select.length;i++)
    {
        if(language_select[i].selected)
        {
            tlangCode = language_select[i].value;
            break;
        }
    }
    for(i=0;i<language_original.length;i++)
    {
        if(language_original[i].selected)
        {
            olangCode = language_original[i].value;
            break;
        }
    }
    if(olangCode==undefined)
    {
        olangCode = '';
    }
    //get the target content element
    var originalElement = document.getElementById("content_body_info");
    var translatedElement = document.getElementById("content_body_info_translated");
    //copy original text to replace the translated text before the new translation.
    translatedElement.innerHTML = originalElement.innerHTML;
    //display translated text and hide the original one
    translatedElement.style.display = "block";
    originalElement.style.display = "none";
    //display the back to original link and hide tranlated link
    var t_l = document.getElementById("tranlation_back_to_translated_link");
    t_l.style.display = "none";
    var b_l = document.getElementById("tranlation_back_to_original_link");
    b_l.style.display = "block";
    //begin translate
    if(translatedElement.innerHTML.length>500)
    {
        var translate_elist = new Array();
        translate_elist.push(translatedElement);
        translate_elements(olangCode,tlangCode,translate_elist);
    }else{
        translate_innerHTML(contentElement,olangCode,tlangCode);
    }
} */






/**
 * translate all elements in elist array
 */
function translate_elements(olangCode,tlangCode,translate_elist)
{
    for(var n=0; n<translate_elist.length;n++)
    {
        element = translate_elist[n];
        cNodelist = element.childNodes;
        if(cNodelist == undefined)
        {
            continue;
        }
        for(var i=0; i<cNodelist.length;i++)
        {
            try{
                cnode = cNodelist[i];
                if(cnode.childNodes.length==0)
                {
                    translate_value(cnode,olangCode,tlangCode);
                }else
                {
                    if(cnode.innerHTML.length<500&&cnode.innerHTML.length>0)
                    {
                        translate_innerHTML(cnode,olangCode,tlangCode);
                    }else{
                        translate_elist.push(cnode);
                    }
                }
            }catch(error){}
            //timeout=setTimeout("translate_elements('"+tlangCode+"')",1000);
        }
    }
}
/**
 * translate node's textContent and replace it.
 * The node's chlidnode should be 0 and textContent.length<500
 */
function translate_value(cnode,olangCode,tlangCode)
{
    google.language.translate(cnode.textContent, olangCode, tlangCode, function(result) {
        if (result.translation) {
            cnode.textContent = result.translation;
        }
    });
}
/**
 * translate node's innerHTML value
 */
function translate_innerHTML(cnode,olangCode,tlangCode)
{
    google.language.translate(cnode.innerHTML, olangCode, tlangCode, function(result) {
        if (result.translation) {
            cnode.innerHTML = result.translation;
        }
    });
}

//str.trim()
String.prototype.trim= function(){
    return this.replace(/(^\s*)|(\s*$)/g, "");    
}

/**
 * get text contents in a element, includes its child node content
 */
function getElementContents(e)
{
    var s = '';
    if(e.childNodes.length==0)
    {
        return e.textContent;
    }else{
        var clist = e.childNodes;
        for(var i=0;i<clist.length;i++)
        {
            s = s + getElementContents(clist[i]);
            s = s.trim()+' ';//need a space after one sentence.
            //google can just check 500 chars. so 500 chars of text content is enough.
            if(s.length>500)
            {
                return s;
            }
        }
        return s;
    }
}

