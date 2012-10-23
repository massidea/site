<?php

	/**
	 * ResetPassword - class
	 *
	 * @package     Forms
	 * @author      Lisa Jedinger
	 * @version     1.0
	 */
class ResetPasswordForm extends Twitter_Bootstrap_Form_Horizontal
{

	/**
	 * @inheritdoc
	 */
	public function init()
    {
	    $translate = Zend_Registry::get('Zend_Translate');
	    $this->setName('resetPassword_form')
		    ->setAttrib('id', 'resetPassword_form')
		    ->addElementPrefixPath('Oibs_Validators', 'OIBS/Validators/', 'validate', 'decorate');

	    $this->addElement('text', 'password', array(
		    'label'      => $translate->_('reset-password-password'),
		    'required'   => true,
		    'validators' => array(
			    new Oibs_Validators_RepeatValidator('resetPassword_password'),
			    array('NotEmpty', true, array('messages' => array('isEmpty' => 'error-field-empty'))),
			    array('StringLength', false, array(4, 16, 'messages' => array('stringLengthTooShort' => 'error-field-too-short', 'stringLengthTooLong' => 'error-field-too-long')))
		    ),
	    ));

	    $this->addElement('text', 'passwordConfirm', array(
		    'label'      => $translate->_('reset-password-password_confirm'),
		    'required'   => true,
		    'validators' => array(
			    new Oibs_Validators_RepeatValidator('resetPassword_confirm_password'),
			    array('NotEmpty', true, array('messages' => array('isEmpty' => 'error-field-empty'))),
			    array('StringLength', false, array(4, 16, 'messages' => array('stringLengthTooShort' => 'error-field-too-short', 'stringLengthTooLong' => 'error-field-too-long')))
		    ),
	    ));


	    $this->addElement('submit', 'submit', array(
		    'label'      => $translate->_('reset-password-submit'),
		    'required'   => true,
		    'validators' => array(),
	    ));

	    parent::init();
    }
}