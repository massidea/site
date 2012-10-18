<?php

/**
 *  RegistrationForm -> Register form creation
 *
 *     Copyright (c) <2009>, Markus Riihelä
 *     Copyright (c) <2009>, Mikko Sallinen
 *    Copyright (c) <2009>, Joel Peltonen
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
 * @package     Forms
 * @author     Markus Riihelä & Mikko Sallinen &  Joel Peltonen
 * @copyright     2009 Markus Riihelä & Mikko Sallinen & Joel Peltonen
 * @license     GPL v2
 * @version     1.0
 */
class Default_Form_RegistrationForm extends Twitter_Bootstrap_Form_Horizontal
{
    public function init()
    {
        $translate = Zend_Registry::get('Zend_Translate');
        $this->removeDecorator('DtDdWrapper');
        $this->setName('register_form');
        $this->setAttrib('id', 'register_form');
        $this->addElementPrefixPath('Oibs_Decorators',
            'Oibs/Decorators/',
            'decorator');

        $this->addElementPrefixPath('Oibs_Validators', 'OIBS/Validators/', 'validate');

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

        $this->addElement('text', 'register_username', array(
            'label' => $translate->_("account-register-username"),
            'required' => true,
            'validators' => array(
                array('NotEmpty', true, array('messages' => array('isEmpty' => 'field-empty'))),
                array('StringLength', false, array(4, 16, 'messages' => array('stringLengthTooShort' => 'field-too-short', 'stringLengthTooLong' => 'field-too-long'))),
                new Oibs_Validators_UsernameExists('username'),
                new Oibs_Validators_Username('username')
            ),
        ));

        $this->addElement('password', 'register_password', array(
            'label' => $translate->_("account-register-password"),
            'required' => true,
            'validators' => array(
                new Oibs_Validators_RepeatValidator('confirm_password'),
                array('NotEmpty', true, array('messages' => array('isEmpty' => 'field-empty'))),
                array('StringLength', false, array(4, 16, 'messages' => array('stringLengthTooShort' => 'field-too-short', 'stringLengthTooLong' => 'field-too-long')))
            ),
        ));

        $this->addElement('password', 'register_confirm_password', array(
            'label' => $translate->_("account-register-password_confirm"),
            'required' => true,
            'validators' => array(
                array('NotEmpty', true, array('messages' => array('isEmpty' => 'field-empty'))),
                array('StringLength', false, array(4, 16, 'messages' => array('stringLengthTooShort' => 'field-too-short', 'stringLengthTooLong' => 'field-too-long'))),
            )
        ));

        $this->addElement('text', 'register_city', array(
            'label' => $translate->_("account-register-city"),
            'required' => true,
            'validators' => array(
                array('NotEmpty', true, array('messages' => array('isEmpty' => 'field-empty'))),
                array('Regex', true, array('/^[\\p{L}0-9.\- ]*$/'))
            ),
        ));

        $this->addElement('text', 'register_email', array(
            'label' => $translate->_("account-register-email"),
            'required' => true,
            'validators' => array(
                $mailvalid
            ),
        ));

        $e_options = array(
            "" => "account-select",
            "private_sector" => "account-register_private_sector",
            "public_sector" => "account-register_public_sector",
            "education_sector" => "account-register_education_sector",
            "student" => "account-register_student",
            "pentioner" => "account-register_pentioner",
            "other" => "account-register_other",
        );

        $this->addElement('select', 'register_employment', array(
            'label' => $translate->_("account-register-employment"),
            'required' => true,
            'validators' => array(
                array('NotEmpty', true, array('messages' => array('isEmpty' => 'field-empty')))
            ),
            'multiOptions' => $e_options
        ));

        $captcha = new Zend_Form_Element('captcha');
        $captcha->setDecorators(array('CaptchaDecorator'));

        $this->addElement('text', 'account-register-enter_text', array(
            'label' => $translate->_("account-register-enter_text"),
            'required' => true,
            'validators' => array(
                new Oibs_Validators_CaptchaValidator(),
                array('NotEmpty', true, array('messages' => array('isEmpty' => 'field-empty'))),
            ),
        ));

        $this->addElement('checkbox', 'register_terms', array(
            'label' => $translate->_("account-register-terms"),
            'required' => true,
            'validators' => array(
                new Oibs_Validators_CheckboxValidator(),
            ),
            'checked' => false
        ));

        $this->addElement('submit', 'register_submit', array(
            'label' => $translate->_("account-register-submit"),
            'required' => true,
            'validators' => array(),
            'checked' => false
        ));


        parent::init();
    }
}