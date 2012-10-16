<?php
/**
 *  FetchPasswordForm -> New password request
 *
*   Copyright (c) <2009>, Markus Riihelä
*   Copyright (c) <2009>, Mikko Sallinen
*   Copyright (c) <2009>, Pekka Piispanen
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
 *  FetchPasswordForm - class
 *
 *  @package    Forms
 *  @license    GPL v2
 *  @version    1.0
 */
class Default_Form_FetchPasswordForm extends Zend_Form
{
    public function __construct($options = null) 
    { 
        parent::__construct($options);
        
        $translate = Zend_Registry::get('Zend_Translate'); 
        $language = $translate->getLocale();
        $baseurl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $actionUrl = $baseurl.'/'.$language.'/account/fetchpassword';
        
        $this->setName('fetchpassword_form')
            ->setAction($actionUrl)
            ->addElementPrefixPath('Oibs_Decorators',
                'Oibs/Decorators/',
                'decorator')
	        ->setDecorators(array(array(
			    'ViewScript',
			    array('viewScript' => 'forms/fetchPassword.phtml')
		    )));


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
        

        
        // Form submit button element
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel($translate->_("account-fetchpassword-submit"))
        		->removeDecorator('DtDdWrapper')
               ->setAttrib('class', 'btn');

	    $hidden = new Zend_Form_Element_Hidden('submittedform');
	    $hidden->setValue('fetchpassword');

	    // Username input form element
	    $email = new Zend_Form_Element_Text('email');
	    $email//->setLabel($translate->_("account-login-username"))
		    ->setAttrib('placeholder','E-Mail')
		    ->addFilter('StringtoLower')
		    ->setRequired(true)
		    ->addValidators(array(
		    array($mailvalid),
	    ))
		    ->setDecorators(array('ViewHelper'))
	    ;
        // Add elements to form
        $this->addElements(array($email, $submit, $hidden));
    }
}