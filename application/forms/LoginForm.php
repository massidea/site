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
class Default_Form_LoginForm extends Zend_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);

		$translate = Zend_Registry::get('Zend_Translate');
		$language = $translate->getLocale();
		$baseurl = Zend_Controller_Front::getInstance()->getBaseUrl();
		$actionUrl = $baseurl.'/'.$language.'/account/login';

		$this->setName('login_form');
		$this->setAction($actionUrl);
		$this->addElementPrefixPath('Oibs_Decorators', 'Oibs/Decorators/', 'decorator');

             $this->setDecorators(array(array(
            'ViewScript',
            array('viewScript' => 'forms/login.phtml')
        )));

		// Username input form element
		$username = new Zend_Form_Element_Text('username');
		$username//->setLabel($translate->_("account-login-username"))
                ->setAttrib('placeholder','E-Mail')
				->addFilter('StringtoLower')
                ->setRequired(true)
				->addValidators(array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => $translate->_('account-login-field-empty')))),
				))
				->setDecorators(array('ViewHelper'))
        ;

		// Password input form element
		$password = new Zend_Form_Element_Password('password');
		$password//->setLabel($translate->_("account-register-password"))
                ->setAttrib('placeholder', 'Password')
                ->setRequired(true)
				->addValidators(array(
					array('NotEmpty', true, array('messages' => array('isEmpty' => $translate->_('account-login-field-empty')))),
				))
				->setDecorators(array('ViewHelper'))
        ;

		// Form submit buttom element
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel($translate->_("account-login-submit"))
            ->removeDecorator('DtDdWrapper')
            ->setAttrib('class', 'btn')
         ;

		// Add elements to form
		$this->addElements(array($username, $password, $submit));
	}
}
