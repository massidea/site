<?php
/**
 *  LoginForm -> Login form creation
 *
* 	Copyright (c) <2009>, Markus Riihel�
* 	Copyright (c) <2009>, Mikko Sallinen
* 	Copyright (c) <2009>, Pekka Piispanen
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
 *  LoginForm - class
 *
 *  @package 	Forms
 *  @author 	Markus Riihel�, Mikko Sallinen, Pekka Piispanen
 *  @copyright 	2009 Markus Riihel�, Mikko Sallinen, Pekka Piispanen
 *  @license 	GPL v2
 *  @version 	1.0
 */
class Default_Form_LoginForm extends Twitter_Bootstrap_Form_Vertical
{

	/** @var string */
	private $_returnUrl = '';

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		$translate = Zend_Registry::get('Zend_Translate');
		$language = $translate->getLocale();
		$baseurl = Zend_Controller_Front::getInstance()->getBaseUrl();
		$actionUrl = $baseurl.'/'.$language.'/account/login';

		$this->setName('login_form')
			->setAction($actionUrl)
			->setAttrib('id', 'login_form')
			->addElementPrefixPath('Oibs_Decorators', 'Oibs/Decorators/', 'decorator')
			->setDecorators(array(array(
				'ViewScript',
				array('viewScript' => 'forms/login.phtml'))));

		$this->addElement('text', 'login_username', array(
			'label'       => 'account-login-username',
			'placeholder' => $translate->translate('account-login-username'),
			'required'    => true,
			'filters'     => array('StringtoLower'),
			'validators'  => array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => $translate->_('account-login-field-empty'))))),
		));

		$this->addElement('password', 'login_password', array(
			'label'       => 'account-login-password',
			'placeholder' => $translate->translate('account-login-password'),
			'required'    => true,
			'validators'  => array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => $translate->_('account-login-field-empty')))),
			),
		));

		$this->addElement('hidden', 'login_returnurl', array (
			'value'      => $this->getReturnUrl(),
			'decorators' => array(array('ViewHelper')),
		));

		$this->addElement('submit', 'login_submit', array(
			'label'      => 'account-login-submit',
			'required'   => true,
			'validators' => array(),
		));

		parent::init();
	}

	/**
	 * @param string $returnUrl
	 * @return Default_Form_LoginForm
	 */
	public function setReturnUrl($returnUrl)
	{
		$this->_returnUrl = $returnUrl;

		$element = $this->getElement('login_returnurl');
		if ($element) $element->setValue($returnUrl);

		return $this;
	}

	/**
	 * @return string
	 */
	public function getReturnUrl()
	{
		return $this->_returnUrl;
	}

}
