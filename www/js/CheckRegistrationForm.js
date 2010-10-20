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
 *	authors:	Tavish Vaidya (vaidyatavish24@gmail.com), Manu Bamba (manu.bamba@gmail.com), Abhishek Rana(abhishekrana502@gmail.com), Gaurav(gaurav.setx@gmail.com)
 *	Licence:	GPL v2.0
 */



var canExit = 0;
var inPreview = 0;
var tmpFormData;
var previewId = 'form_content_previewcontent';
var contentId = 'form_content_realcontent';
var ajaxCheck = false;

$(document).ready(function() {
    tmpFormData = getPreviewData();
     
    // Get all input elements
    // var allInputs = $(":input[type=text], :input[type=textarea], #password ");

    // Definitions for input boxes ([0] = minimum, [1] = maximum, [2] = required (1 true/0 false)

    var inputDefinitions = {
        'username': [4, 16, 1],
        'password': [4, 16, 1],
        'confirm_password': [4, 16, 1],
        'city': [1, 100, 1],
        'email': [6, 100, 1],
        'employment': [1, 100, 1],
        'captcha_text': [1, 5, 1]
    };

    var inputValidations = {
        'username': XRegExp("^[\\p{L}0-9, ]*$"),
        'city' : XRegExp("^[\\p{L}0-9, ]*$")
    };
    
   
    //checkMin()
    //textCount()
    //textValidation()
    call();
    function call()
    {
        //--------------------------------------username validation-------------//
        var username_input= $("#username");
        var thisProgress = $('#progressbar_username');
        if($(username_input).val()=="")
        {
            $(thisProgress).attr('class','progress').html("required");
        }
        //first validate all input
        $(username_input).live('change keydown keyup blur focus', function()
        {
            checkMin(this);
              
        });

        $("#username").blur(function()
        {
            if(ajaxCheck==true)
                {
                   checkUserAvail();
                }
        });
        


        //--------------------------------------------password validation-----------------------------------------//

        var password_input= $("#password");
        var password_progress = $('#progressbar_password');
        if($(password_input).val()=="")
        {
            $(password_progress).attr('class','progress').html("required");
        }
        //first validate all input
        $(password_input).live('change keydown keyup blur focus', function()
        {
            checkMin(this);
        });

        var password_confirm_input=$("#confirm_password");
        var password_confirm_progress =$("#progressbar_confirm_password");
        if($(password_confirm_input).val()=="")
        {
            $(password_confirm_progress).attr('class','progress').html("required");
        }
        $(password_confirm_input).live('change keydown keyup blur focus', function()
        {
            passMatch(this);
        });


        //-----------------------------------hometown validation--------------------------------//

        var city_input= $("#city");
        var city_progress = $('#progressbar_city');
        if($(city_input).val()=="")
        {
            $(city_progress).attr('class','progress').html("required");
        }
        
        $(city_input).live('change keydown keyup blur focus', function()
        {
            checkMin(this);
        });


        //-----------------------------------email id validation-------------------------------//
        
        var email_input= $("#email");
        var email_progress = $('#progressbar_email');
        if($(city_input).val()=="")
        {
            $(email_progress).attr('class','progress').html("required");
        }
        
        $(email_input).live('change keydown keyup blur focus', function()
        {
            checkEmail(this);
        });

        //------------------------------employment check--------------------------------------//
        var employment_input= $("#employment");
        var employment_progress = $('#progressbar_employment');
        if($(employment_input).val()=="0")
        {
            $(employment_progress).attr('class','progress').html("required");
        }

        $(employment_input).live('change keydown keyup blur focus', function()
        {
            selectCheck(this);
        });

        //-----------------------------captcha------------------------//
        var captcha_input= $("#captcha_text");
        var captcha_progress = $('#progressbar_captcha_text');
        if($(captcha_input).val()=="")
        {
            $(captcha_progress).attr('class','progress').html("required");
        }

        $(captcha_input).live('change keydown keyup blur focus', function()
        {
            if($(captcha_input).val()!="")
                $(captcha_progress).fadeOut(1000);
            else
                $(captcha_progress).show(1);
        });
        

    } // call function ends
    
    function checkUserAvail()
    {
        var Url = url_userexists;
        var username = $("#username").val();
        var thisProgress=$("#progressbar_username");
        $.ajax({
            url : Url+"/user/"+username,
            success: function(result)
            {
                if(result=="false")
                {
                    $(thisProgress).html("Good !");
                }
                else
                {
                    $(thisProgress).attr('class','progress').html("Username already exists !");
                }
            }
        });
        return false;
    }
    

    function passMatch(obj) {
        var thisProgress = $('#progressbar_'+obj.name);
        if(($(obj).val()) != ($("#password").val())) {
            progressText = "Mismatch!";
            $(thisProgress).attr('class','progress');

        }
        else if($(obj).val()=="")
        {
            progressText = "required";
            $(thisProgress).attr('class','progress');
        }
        else {
            progressText = "Match!";
            $(thisProgress).attr('class','progress_ok');
            if($("#password").val().length < inputDefinitions['password'][0]  )
                $(thisProgress).attr('class','progress');
        }
        $(thisProgress).html(progressText);
    }

    function checkEmail(obj) {
        var thisProgress = $('#progressbar_'+obj.name);
        var hasError = false;
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        var emailaddressVal = $("#email").val();
        if(emailaddressVal == '') {
            progressText = "Empty!!";
            $(thisProgress).attr('class','progress');
        }
        else if(!emailReg.test(emailaddressVal)) {
            progressText ="Enter valid id";
            $(thisProgress).attr('class','progress');
        }
        else  
        {
            $(thisProgress).attr('class','progress_ok');
            progressText="ok "
            
        }
        $(thisProgress).html(progressText);
    }


    function checkMin(obj) {

        var thisProgress = $('#progressbar_'+obj.name);
        var thisMin = inputDefinitions[obj.name][0];
       
        var curLength = $(obj).val().length;
        var curLeft = (thisMin-curLength)

        if(curLength < thisMin) {
          
            progressText =Math.abs(curLeft) + " too Less";
            $(thisProgress).attr('class','progress');
            $(thisProgress).html(progressText);
            
        }
        else if(textCount(obj))
        {
            textValidation(obj);
        }
    }

    

    function textCount(obj) {
        //var checkText = false;
        var thisMin = inputDefinitions[obj.name][0];
        var thisMax = inputDefinitions[obj.name][1];
        var thisReq = inputDefinitions[obj.name][2];
        var curLength = $(obj).val().length;
        var curLeft = (thisMax-curLength);
        var thisProgress = $('#progressbar_'+obj.name);

        if(curLength < thisMax && curLength >= thisMin ) {
            progressText = curLeft + " until limit";
            $(thisProgress).attr('class','progress_ok');
            $(thisProgress).html(progressText);
            return true;
        }

        if(curLength == thisMax) {
            progressText = "at the limit";
            $(thisProgress).attr('class','progress_ok');
            $(thisProgress).html(progressText);
            return true;
        }
        
        if(curLength > thisMax) {
            progressText = Math.abs(curLeft) + " too many";
            $(thisProgress).attr('class','progress');
            $(thisProgress).html(progressText);
            return false;
        }
        

        if(curLength == 0 && thisReq) {
            progressText = "required";
            $(thisProgress).attr('class','progress');
            $(thisProgress).html(progressText);
            return false;
        }

                
    }

    function textValidation(obj) {
        var thisProgress = $('#progressbar_'+obj.name);
        var regex = inputValidations[obj.name];
        if (regex.test($(obj).val()))
        {
            $(thisProgress).attr('class','progress_ok');
            if(obj.name=="username")
            {
                ajaxCheck = true;
            }
        }
        else {
            progressText = "Tag not valid!";
            $(thisProgress).attr('class','progress');
            $(thisProgress).html(progressText);
            if(obj.name=="username")
            {
                ajaxCheck = false;
            }
        }
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