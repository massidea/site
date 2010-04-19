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
 *	authors:	Joel Peltonen <joel.peltonen@cs.tamk.fi>
 *	Licence:	GPL v2.0
 */	
 
function window_onload() {
       if (!document.body) 
	   {
              setTimeout("window_onload()", 100); //retry after 1 sec if body has not loaded yet
       }
       else 
	   {
              checkRF();
       }
}

window.onload = window_onload();

/**
*	On the fly validator for the registration form
*/
function validateFormRegistration()
{
	var username = document.getElementById("register_form_username");
	var vq = document.getElementById("register_form_vq");
	var va = document.getElementById("register_form_va");
	var email = document.getElementById("register_form_email");
	var email2 = document.getElementById("register_form_email2");
	var pass = document.getElementById("register_form_pass");
	var pass2 = document.getElementById("register_form_pass2");
	var captcha = document.getElementById("register_form_captcha");
	var terms = document.getElementById("register_form_terms");
	
	if (username.value.length == 0
	||	va.value.length == 0
	|| 	vq.value.length == 0
	||	email.value.length == 0
	||	email2.value.length == 0
	||	pass.value.length == 0
	||	pass2.value.length == 0
	||	captcha.value.length == 0
	||	terms.checked == false) {
		return false;
	} else if (pass.value != pass2.value) {
		return false;
	} else if (email.value != email2.value) {
		return false;
	}
	else if (username.value.length >= 21) {
		return false;
	}
	else if (email.value.length >= 99) {
		return false;
	}
	else if (pass.value.length >= 99) {
		return false;
	}
	
	return true;
}

function checkRF() {
    alert('ass');
	var check = validateFormRegistration();
	if (check == true) {
		enableSubmit(document.getElementById("register_form"));
	} else {
		disableSubmit(document.getElementById("register_form"));
	}
}