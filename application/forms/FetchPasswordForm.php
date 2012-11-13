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
class Default_Form_FetchPasswordForm extends Twitter_Bootstrap_Form_Horizontal
{
    public function init()
    {
        $translate = Zend_Registry::get('Zend_Translate'); 
        $language = $translate->getLocale();
        $baseurl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $actionUrl = $baseurl.'/'.$language.'/account/fetchpassword';
        
        $this->setName('fetchpassword_form')
            ->setAction($actionUrl)
            ->addElementPrefixPath('Oibs_Decorators',
                'Oibs/Decorators/',
                'decorator');

	    // Username input form element
        $this->addElement('text', 'email', array(
            'label'       => 'account-fetchpassword-email',
            'placeholder' => $translate->_('account-fetchpassword-email'),
            'filter'      => 'StringtoLower',
            'required'    => true,
            'validators' => array(
                array('NotEmpty', true, array('messages' => array('isEmpty' => 'error-field-empty'))),
                array($this->getMailValidator()),
                new Oibs_Validators_EMailExists('email'),
            ),
        ));

	    // captcha input form element
        $this->addElement('captcha', 'captcha', array(
            'captcha'    => array(
                'captcha' => 'Image',
                'wordLen' => 8,
                'timeout' => 300,
                'font'    => APPLICATION_PATH . '/../library/Fonts/Verdana.ttf',
                'imgDir'  => APPLICATION_PATH . '/../www/img/captcha',
                'imgUrl'  => '/img/captcha',
                'Messages'    => array(
                    'badCaptcha' => 'error-captcha-no-same',
                )
            ),
            'required'   => true,
            'label'      => 'account-fetchpassword-captcha',
        ));

        $this->addElement('hidden', 'submittedform', array(
            'value'       => 'fetchpassword'
        ));
        // Form submit button element
        $this->addElement('submit', 'submit', array(
            'label'       => 'account-fetchpassword-submit',
        ))->removeDecorator('DtDdWrapper');

        $this->addDisplayGroup(array(
                'submit'
            ),
            'Actions',
            array(
                'disableLoadDefaultDecorators' => true,
                'decorators' => array('Actions'),
            ));

        parent::init();
    }
    /**
     * Creates an email address validator with better messages.
     * @return Zend_Validate_EmailAddress
     */
    protected function getMailValidator()
    {
        $mail_validator = new Zend_Validate_EmailAddress();
        $mail_validator->setMessage(
            'email-invalid',
            Zend_Validate_EmailAddress::INVALID);
        $mail_validator->setMessage(
            'email-invalid-hostname',
            Zend_Validate_EmailAddress::INVALID_HOSTNAME);
        $mail_validator->setMessage(
            'email-invalid-mx-record',
            Zend_Validate_EmailAddress::INVALID_MX_RECORD);
        $mail_validator->setMessage(
            'email-dot-atom',
            Zend_Validate_EmailAddress::DOT_ATOM);
        $mail_validator->setMessage(
            'email-quoted-string',
            Zend_Validate_EmailAddress::QUOTED_STRING);
        $mail_validator->setMessage(
            'email-invalid-local-part',
            Zend_Validate_EmailAddress::INVALID_LOCAL_PART);
        $mail_validator->setMessage(
            'email-length-exceeded',
            Zend_Validate_EmailAddress::LENGTH_EXCEEDED);
        $mail_validator->hostnameValidator->setMessage(
            'hostname-invalid-hostname',
            Zend_Validate_Hostname::INVALID_HOSTNAME);
        $mail_validator->hostnameValidator->setMessage(
            'hostname-local-name-not-allowed',
            Zend_Validate_Hostname::LOCAL_NAME_NOT_ALLOWED);
        $mail_validator->hostnameValidator->setMessage(
            'hostname-unknown-tld',
            Zend_Validate_Hostname::UNKNOWN_TLD);
        $mail_validator->hostnameValidator->setMessage(
            'hostname-invalid-local-name',
            Zend_Validate_Hostname::INVALID_LOCAL_NAME);
        $mail_validator->hostnameValidator->setMessage(
            'hostname-undecipherable-tld',
            Zend_Validate_Hostname::UNDECIPHERABLE_TLD);

        return $mail_validator;
    }
}