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
 *  @author 	Markus Riihelä & Mikko Sallinen &  Joel Peltonen
 *  @copyright 	2009 Markus Riihelä & Mikko Sallinen & Joel Peltonen
 *  @license 	GPL v2
 *  @version 	1.0
 */
class Default_Form_RegistrationForm extends Zend_Form
{
    public function __construct($options = null) 
    { 
        parent::__construct($options);
		$translate = Zend_Registry::get('Zend_Translate'); 
		$this->removeDecorator('DtDdWrapper');
		$this->setName('register_form');
		$this->setAttrib('id', 'register_form');
		$this->addElementPrefixPath('Oibs_Decorators', 
								'Oibs/Decorators/',
								'decorator');
                                
		$this->addElementPrefixPath('Oibs_Validators', 'OIBS/Validators/', 'validate');
        
        $city = new Zend_Form_Element_Text('city');
        $city->setLabel($translate->_("account-register-city"))
                ->setRequired(true)
				->addValidators(array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'field-empty'))), 
				))
				->setDecorators(array('RegistrationDecorator'));
    
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
					array('NotEmpty', 
                        true, 
                        array('messages' => array('isEmpty' => 'field-empty'))
                    ), 
					array('StringLength', 
                        true, 
                        array(6, 50,
                            'messages' => array(
                                'stringLengthTooShort' => 'field-too-short', 
                                'stringLengthTooLong' => 'field-too-long'
                            )
                        )
                    ),
				))
                ->addErrorMessage('email-invalid')
				->setDecorators(array('RegistrationDecorator'));

        $e_options = array(
                        "" => "account-select",
                        "private_sector" => "account-register_private_sector",
                        "public_sector" => "account-register_public_sector",
                        "student" => "account-register_student",
                        "pentioner" => "account-register_pentioner",
                        "other" => "account-register_other",
                     );
        
        $employment = new Zend_Form_Element_Select('employment');
        $employment->setLabel($translate->_("account-register-employment"))
                    ->setRequired(true)
                    ->addValidators(array(
                        array('NotEmpty', true, array('messages' => array('isEmpty' => 'field-empty'))), 
                    ))
                    ->addMultiOptions($e_options)
                    ->setDecorators(array('RegistrationDecorator'));
                    
		$username = new Zend_Form_Element_Text('username');
		$username->setLabel($translate->_("account-register-username"))
				->setRequired(true)
				->addValidators(array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'field-empty'))), 
				array('StringLength', false, array(4, 16, 'messages' => array('stringLengthTooShort' => 'field-too-short', 'stringLengthTooLong' => 'field-too-long'))),
                new Oibs_Validators_UsernameExists('username'),
				))
				->setDecorators(array('RegistrationDecorator'));
		
		$password = new Zend_Form_Element_Password('password');
		$password->setLabel($translate->_("account-register-password"));
		$password->setRequired(true);
		$password->addValidators(array(
					new Oibs_Validators_RepeatValidator('confirm_password'),
					array('NotEmpty', true, array('messages' => array('isEmpty' => 'field-empty'))), 
					array('StringLength', false, array(4, 16, 'messages' => array('stringLengthTooShort' => 'field-too-short', 'stringLengthTooLong' => 'field-too-long'))),
				));
		$password->setDecorators(array('RegistrationDecorator'));		
		
		$confirm_password = new Zend_Form_Element_Password('confirm_password');
		$confirm_password->setLabel($translate->_("account-register-password_confirm"));
		$confirm_password->setRequired(true);
		$confirm_password->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'field-empty'))); 
		$confirm_password->addValidator('StringLength', false, array(4, 16, 'messages' => array('stringLengthTooShort' => 'field-too-short', 'stringLengthTooLong' => 'field-too-long')));
		$confirm_password->setDecorators(array('RegistrationDecorator'));
		     
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
       
		$text = sprintf($translate->_("account-register-terms_and_privacy"), "terms", "privacy");
		// this solution sucks. the codes are in the translate block directly. 
        // anyone think of a fix to move codes out of there?
        // - Joel
        
		$terms = new Zend_Form_Element_Checkbox('terms');
		$terms->setDescription($text)
				->setLabel("account-register-terms")
				->setChecked(false)
				->setRequired(true)
				->addValidators(array(
						new Oibs_Validators_CheckboxValidator(),
						))
				->setDecorators(array('RegistrationTermsDecorator'));
		// checkboxes always have a value of 1or0, this is a "feature" in ZF
		// custom validator is a workaround
        // -Joel
				
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel($translate->_("account-register-submit"))
               ->removeDecorator('DtDdWrapper')
               ->setDecorators(array(
                                    'ViewHelper',
                                     array('HtmlTag', array('tag' => 'div', 'class' => 'registration_submit_div'))
                                ))
               ->setAttrib('class', 'registration_form_submit_' . $translate->getLocale())
               ;
        
		$this->addElements(array($username, $password, $confirm_password, 
                                $city, $email, $employment, 
                                $captcha, $captcha_text, $terms, $submit));
       
       $this->addDisplayGroup(array('username', 'password', 'confirm_password'), 'account_information');
       $this->account_information->setLegend('register-account-information');
       $this->account_information->removeDecorator('DtDdWrapper');
       $this->addDisplayGroup(array('city', 'email', 'employment'), 'personal_information');
       $this->personal_information->removeDecorator('DtDdWrapper');
       $this->personal_information->setLegend('register-personal-information');
       
       $this->addDisplayGroup(array('captcha', 'captcha_text', 'terms', 'submit'), 'confirmations');
       $this->confirmations->removeDecorator('DtDdWrapper');
       $this->confirmations->setLegend('register-confirmations');
	}
}