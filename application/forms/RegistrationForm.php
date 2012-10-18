<?php

/**
 * RegistrationForm - class
 *
 * @package     Forms
 * @author      Markus Riihelä & Mikko Sallinen &  Joel Peltonen
 * @copyright   2009 Markus Riihelä & Mikko Sallinen & Joel Peltonen
 * @license     GPL v2
 * @version     1.0
 */
class Default_Form_RegistrationForm extends Twitter_Bootstrap_Form_Horizontal
{

	/**
	 * @inheritdoc
	 */
	public function init()
    {
        $this->setName('register_form')
            ->setAttrib('id', 'register_form')
	        ->addElementPrefixPath('Oibs_Validators', 'OIBS/Validators/', 'validate', 'decorate');

        $this->addElement('text', 'register_username', array(
            'label'      => 'account-register-username',
            'required'   => true,
            'validators' => array(
                array('NotEmpty', true, array('messages' => array('isEmpty' => 'error-field-empty'))),
                array('StringLength', false, array(4, 16, 'messages' => array('stringLengthTooShort' => 'error-field-too-short', 'stringLengthTooLong' => 'error-field-too-long'))),
                new Oibs_Validators_UsernameExists('username'),
                new Oibs_Validators_Username('username')
            ),
        ));

        $this->addElement('password', 'register_password', array(
            'label'      => 'account-register-password',
            'required'   => true,
            'validators' => array(
                new Oibs_Validators_RepeatValidator('register_confirm_password'),
                array('NotEmpty', true, array('messages' => array('isEmpty' => 'error-field-empty'))),
                array('StringLength', false, array(4, 16, 'messages' => array('stringLengthTooShort' => 'error-field-too-short', 'stringLengthTooLong' => 'error-field-too-long')))
            ),
        ));

        $this->addElement('password', 'register_confirm_password', array(
            'label'      => 'account-register-password-confirm',
            'required'   => true,
            'validators' => array(
                array('NotEmpty', true, array('messages' => array('isEmpty' => 'error-field-empty'))),
                array('StringLength', false, array(4, 16, 'messages' => array('stringLengthTooShort' => 'error-field-too-short', 'stringLengthTooLong' => 'error-field-too-long'))),
            ),
        ));

        $this->addElement('text', 'register_city', array(
            'label'      => 'account-register-city',
            'required'   => true,
            'validators' => array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'error-field-empty'))),
				array('Regex', true, array('/^[\\p{L}0-9.\- ]*$/'))
            ),
        ));

        $this->addElement('text', 'register_email', array(
            'label'      => 'account-register-email',
            'required'   => true,
            'validators' => array($this->getMailValidator()),
        ));

        $this->addElement('select', 'register_employment', array(
            'label'        => 'account-register-employment',
            'required'     => true,
	        'multiOptions' => $this->getAccountOptions(),
            'validators'   => array(
                array('NotEmpty', true, array('messages' => array('isEmpty' => 'error-field-empty')))
            ),
        ));

	    $this->addElement('captcha', 'captcha', array(
		    'captcha'    => array(
			    'captcha' => 'Image',
			    'wordLen' => 8,
			    'timeout' => 300,
			    'font'    => APPLICATION_PATH . '/../library/Fonts/Verdana.ttf',
			    'imgDir'  => APPLICATION_PATH . '/../www/img/captcha',
			    'imgUrl'  => '/img/captcha',
		    ),
		    'required'   => true,
		    'label'      => 'account-register-captcha',
	    ));

        $this->addElement('checkbox', 'register_terms', array(
            'label'          => 'account-register-gtc',
            'required'       => true,
	        'uncheckedValue' => '',
	        'checked'        => false,
	        'description'    => 'account-register-terms-and-privacy',
	        'errorMessages'  => array('empty' => 'error-checkbox-not-checked'),
        ));

        $this->addElement('submit', 'register_submit', array(
            'label'      => 'account-register-submit',
            'required'   => true,
            'validators' => array(),
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

	/**
	 * Returns an array of account options for the type of the new account.
	 *
	 * These have three big sections:
	 *  > Private Sector
	 *  > Public Sector
	 *  > Educational Sector
	 *
	 * Furthermore there are more detailed options: Student, Petitioner and Other.
	 *
	 * @return array
	 */
	protected function getAccountOptions()
	{
		return array(
			''                 => 'account-select',
			'private_sector'   => 'account-register-private-sector',
			'public_sector'    => 'account-register-public-sector',
			'education_sector' => 'account-register-education-sector',
			'student'          => 'account-register-student',
			'pentioner'        => 'account-register-pentioner',
			'other'            => 'account-register-other',
		);
	}

}
