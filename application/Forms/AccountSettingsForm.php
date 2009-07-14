<?php
/**
 *  AccountSettingsForm -> Form for account settings
 *
* 	Copyright (c) <2009>, Markus Riihelä
* 	Copyright (c) <2009>, Mikko Sallinen
*
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License 
 * as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied  
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for  
 * more details.
 * 
 * You should have received a copy of the GNU General Public License along with this program; if not, write to the Free 
 * Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * License text found in /license/
 */

/**
 *  AccountSettingsForm - class
 *
 *  @package 	Forms
 *  @author 	Markus Riihelä & Mikko Sallinen
 *  @copyright 	2009 Markus Riihelä & Mikko Sallinen
 *  @license 	GPL v2
 *  @version 	1.0
 */
 
class Forms_AccountSettingsForm extends Zend_Form
{
    public function __construct($options = null) 
    { 
        parent::__construct($options);
		
		$translate = Zend_Registry::get('Zend_Translate'); 
		
		$this->setName('account_settings_form');
		$this->addElementPrefixPath('Oibs_Decorators', 
								'Oibs/Decorators/',
								'decorator');
		
		$mailvalid = new Zend_Validate_EmailAddress();
		$mailvalid->setMessage(
			'email-invalid',
			Zend_Validate_EmailAddress::INVALID);
		$mailvalid->setMessage(
			'email-invalid-hostname',
			Zend_Validate_EmailAddress::INVALID_HOSTNAME);
		$mailvalid->setMessage(
			'email-invalid-mx-record',
			Zend_Validate_EmailAddress::INVALID_MX_RECORD);
		$mailvalid->setMessage(
			'email-dot-atom',
			Zend_Validate_EmailAddress::DOT_ATOM);
		$mailvalid->setMessage(
			'email-quoted-string',
			Zend_Validate_EmailAddress::QUOTED_STRING);
		$mailvalid->setMessage(
			'email-invalid-local-part',
			Zend_Validate_EmailAddress::INVALID_LOCAL_PART);
		$mailvalid->setMessage(
			'email-length-exceeded',
			Zend_Validate_EmailAddress::LENGTH_EXCEEDED);
		$mailvalid->hostnameValidator->setMessage(
			'hostname-invalid-hostname',
			Zend_Validate_Hostname::INVALID_HOSTNAME);
		$mailvalid->hostnameValidator->setMessage(
			'hostname-local-name-not-allowed',
			Zend_Validate_Hostname::LOCAL_NAME_NOT_ALLOWED);
		$mailvalid->hostnameValidator->setMessage(
			'hostname-unknown-tld',
			Zend_Validate_Hostname::UNKNOWN_TLD);	
		$mailvalid->hostnameValidator->setMessage(
			'hostname-invalid-local-name',
			Zend_Validate_Hostname::INVALID_LOCAL_NAME);	
		$mailvalid->hostnameValidator->setMessage(
			'hostname-undecipherable-tld',
			Zend_Validate_Hostname::UNDECIPHERABLE_TLD);
		
		// First name input form element
		$first_name = new Zend_Form_Element_Text('first_name');
		$first_name->setLabel($translate->_("account-profile-first-name"))
				//->setRequired(true)
				//->addFilter('StringtoLower')
				->addValidators(array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Empty'))), 
				))
				->setDecorators(array('CustomDecorator'));
		
		// Surname input form element
		$surname = new Zend_Form_Element_Text('surname');
		$surname->setLabel($translate->_("account-profile-surname"))
				//->setRequired(true)
				//->addFilter('StringtoLower')
				->addValidators(array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Empty'))), 
				))
				->setDecorators(array('CustomDecorator'));
		
		// Gender input form element
		$gender = new Zend_Form_Element_Select('gender');
		$gender->setLabel("Gender")
				->addFilter('StringtoLower')
				->setDecorators(array('CustomDecorator'))
				->setMultiOptions(array("Male","Female"));
		
		// Password input form element
		$password = new Zend_Form_Element_Password('password');
		$password->setLabel($translate->_("account-register-password"))
				//->setRequired(true)
				//->addFilter('StringtoLower')
				->addValidators(array(
				new Oibs_Validators_RepeatValidator('confirm_password'),
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Empty'))), 
				array('StringLength', false, array(4, 22, 'messages' => array('stringLengthTooShort' => 'PASSWORD TOO SHORT'))),
				))
				->setDecorators(array('CustomDecorator'));		
		
		// Password confirm input form element
		$password_confirm = new Zend_Form_Element_Password('confirm_password');
		$password_confirm->setLabel($translate->_("account-register-password_confirm"))
				//->setRequired(true)
				//->addFilter('StringtoLower')
				->addValidators(array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Empty'))), 
				array('StringLength', false, array(4, 22, 'messages' => array('stringLengthTooShort' => 'PASSWORD TOO SHORT'))),
				))
				->setDecorators(array('CustomDecorator'));	
		
		// E-mail input form element
		$email = new Zend_Form_Element_Text('email');
		$email->setLabel($translate->_("account-register-email"))
				//->setRequired(true)
				->addFilter('StringtoLower')
				->addValidators(array(
				new Oibs_Validators_RepeatValidator('confirm_email'),
				$mailvalid,
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Empty'))), 
				array('StringLength', false, array(4, 32,'messages' => array('stringLengthTooShort' => 'E-MAIL TOO SHORT'))),
				))
				->setDecorators(array('CustomDecorator'));
				// ->removeDecorator('errors');
		
		// E-mail confirm input form element
		$confirm_email = new Zend_Form_Element_Text('confirm_email');
		$confirm_email->setLabel($translate->_("account-register-email_confirmation"))
				//->setRequired(true)
				->addFilter('StringtoLower')
				->addValidators(array(
				$mailvalid,
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Empty'))), 
				array('StringLength', false, array(4, 32, 'messages' => array('stringLengthTooShort' => 'E-MAIL TOO SHORT'))),
				))
				->setDecorators(array('CustomDecorator'));
				
		// E-mail confirm input form element
		$current_password = new Zend_Form_Element_Password('current_password');
		$current_password->setLabel($translate->_("account-register-current_password"))
				->setRequired(true)
				//->addFilter('StringtoLower')
				->addValidators(array(
				new Oibs_Validators_CurrentPasswordValidator(),
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Empty'))), 
				))
				->setDecorators(array('CustomDecorator'));
		
		// Form submit buttom form element		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel($translate->_("account-register-submit"));
		
		// Add elements to form
		$this->addElements(array($first_name, $surname, $password, $password_confirm, $email, $confirm_email, $current_password, $submit));
	}
}
?>