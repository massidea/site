<?php
/**
 *  RegistrationForm -> Register form creation
 *
* 	Copyright (c) <2009>, Markus Riihelä
* 	Copyright (c) <2009>, Mikko Sallinen
*	Copyright (c) <2009>, Joel Peltonen
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
 *  RegistrationForm - class
 *
 *  @package 	Forms
 *  @author 		Markus Riihelä & Mikko Sallinen &  Joel Peltonen
 *  @copyright 	2009 Markus Riihelä & Mikko Sallinen & Joel Peltonen
 *  @license 	GPL v2
 *  @version 	1.0
 */
class Forms_RegistrationForm extends Zend_Form
{
    public function __construct($options = null) 
    { 
        parent::__construct($options);
		
		$translate = Zend_Registry::get('Zend_Translate'); 
		
		$this->setName('register_form');
		$this->setAttrib('id', 'register_form');
		$this->addElementPrefixPath('Oibs_Decorators', 
								'Oibs/Decorators/',
								'decorator');
		$this->addElementPrefixPath('Oibs_Validators', 'OIBS/Validators/', 'validate');
		
		$username = new Zend_Form_Element_Text('username');
		$username->setLabel($translate->_("account-register-username"))
				->setRequired(true)
				->addFilter('StringtoLower')
				->addValidators(array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'field-empty'))), 
				array('StringLength', false, array(4, 16, 'messages' => array('stringLengthTooShort' => 'field-too-short'))),
                new Oibs_Validators_UsernameExists('username'),
				))
				->setDecorators(array('RegistrationDecorator'));
				//print_r($username->helper); 
		
		$password = new Zend_Form_Element_Password('password');
		$password->setLabel($translate->_("account-register-password"));
		$password->setRequired(true);
		//$password->addFilter('StringtoLower'); // why??
		$password->addValidators(array(
					new Oibs_Validators_RepeatValidator('confirm_password'),
					array('NotEmpty', true, array('messages' => array('isEmpty' => 'field-empty'))), 
					array('StringLength', false, array(4, 16, 'messages' => array('stringLengthTooShort' => 'field-too-short'))),
				));
		$password->setDecorators(array('RegistrationDecorator'));		
		
		$password_confirm = new Zend_Form_Element_Password('confirm_password');
		$password_confirm->setLabel($translate->_("account-register-password_confirm"));
		$password_confirm->setRequired(true);
		//$password_confirm->addFilter('StringtoLower'); // why??
		$password_confirm->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'field-empty'))); 
		$password_confirm->addValidator('StringLength', false, array(4, 16, 'messages' => array('stringLengthTooShort' => 'field-too-short')));
		$password_confirm->setDecorators(array('RegistrationDecorator'));
		
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

		$email = new Zend_Form_Element_Text('email');
		$email->setLabel($translate->_("account-register-email"))
				->setRequired(true)
				->addFilter('StringtoLower')
				->addValidators(array(
					$mailvalid,
					array('NotEmpty', true, array('messages' => array('isEmpty' => 'field-empty'))), 
					array('StringLength', false, array(6, 50,'messages' => array('stringLengthTooShort' => 'field-too-short'))),
				))
                ->addErrorMessage('email-invalid')
				->setDecorators(array('RegistrationDecorator'));
				
		$confirm_email = new Zend_Form_Element_Text('confirm_email');
		$confirm_email->setLabel($translate->_("account-register-email_confirmation"))
				->setRequired(true)
				->addFilter('StringtoLower')
				->addValidators(array(
                    new Oibs_Validators_RepeatValidator('email'),
					$mailvalid,
					array('NotEmpty', true, array('messages' => array('isEmpty' => 'field-empty'))), 
					array('StringLength', false, array(6, 50, 'messages' => array('stringLengthTooShort' => 'field-too-short'))),
				))
                ->addErrorMessage('email-invalid')
				->setDecorators(array('RegistrationDecorator'));
				// ->removeDecorator('errors');	
                
        $description = "<a href=\"#\" onclick=\"popup('popup_verification')\"><img src=\"/images/icon_question_registration.png\" alt=\"?\" style=\"margin-left:3px;\"></a>";
        $reminder_question = new Zend_Form_Element_Text('reminder_question');
        $reminder_question->setLabel($translate->_("account-register-reminder-question"))
				->setRequired(true)
				->addValidators(array(
					array('NotEmpty', true, array('messages' => array('isEmpty' => 'field-empty'))), 
					array('StringLength', false, array(6, 50,'messages' => array('stringLengthTooShort' => 'field-too-short'))),
				))
				->setDecorators(array('RegistrationDecorator'))
                ->setDescription($description);
				
        $reminder_answer = new Zend_Form_Element_Text('reminder_answer');
        $reminder_answer->setLabel($translate->_("account-register-reminder-answer"))
				->setRequired(true)
				->addValidators(array(
					array('NotEmpty', true, array('messages' => array('isEmpty' => 'field-empty'))), 
					array('StringLength', false, array(6, 50,'messages' => array('stringLengthTooShort' => 'field-too-short'))),
				))
				->setDecorators(array('RegistrationDecorator'));
                
		/*	
		$captcha = new Zend_Form_Element_Captcha('verification', array(
				'label' => $translate->_("account-register-verification"),
				'captcha' => 'Image',
			    'captchaOptions' => array(
			        'captcha' => 'Image',
			        'wordLen' => 6,
			        'timeout' => 300,
					'font' => '../library/fonts/Verdana.ttf',
					'imgDir' => '../www/images/captcha/',
					),
		));
		$captcha->removeDecorator('errors');	
		*/
		
		$captcha = new Zend_Form_Element('captcha');
		$captcha->setDecorators(array('CaptchaDecorator'));
				
		$captcha_text = new Zend_Form_Element_Text('captcha_text');
		$captcha_text->setLabel($translate->_("account-register-enter_text"))
					->addValidators(array(
						new Oibs_Validators_CaptchaValidator(),
						array('NotEmpty', true, array('messages' => array('isEmpty' => 'field-empty')))
						))
					->setRequired(true)
					->setDecorators(array('RegistrationDecorator'));

		/*
		$verification = new Zend_Form_Element_Text('verification');
		$verification->setLabel('Verification')
				->setRequired(true)
				->addFilter('StringtoLower')
				->addValidators(array(
				array('NotEmpty', true), 
				array('StringLength', false, array(6, 6))
				));				
				
				
				->setDecorators(array(
				'ViewHelper',
				'Description',
				'Errors',
				array(array('data' => 'HtmlTag'), array('tag' => 'td')),
				array('Label', array('tag' => 'td')),
				array(array('row' => 'HtmlTag'),array('tag' => 'tr'))
		));
		*/
		
		$text = sprintf($translate->_("account-register-terms_and_privacy_test"), "terms", "privacy");
		
		$terms = new Zend_Form_Element_Checkbox('terms');
		$terms->setDescription($text)
				->setLabel("account-register-terms")
				->setChecked(false)
				->setRequired(true)
				->addValidators(array(
						new Oibs_Validators_CheckboxValidator(),
						))
				->setDecorators(array('RegistrationTermsDecorator'));
		// A checkbox always has a value of 1 or 0, this is a feature ("feature" ?) in Zend
		// own validator is workaround for now.
				
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel($translate->_("account-register-submit"));
		
		/*
		$reset = new Zend_Form_Element_Reset('reset');
		$reset->setLabel($translate->_("reset"));
		*/
		
		$this->addElements(array($username, $password, $password_confirm, 
								$email, $confirm_email, $reminder_question, 
                                $reminder_answer, $captcha, $captcha_text, 
								$terms, $submit));
	}
}
?>

