<?php
/**
 *  OpenIDLoginForm -> OpenID Login form creation
 *
 * 	Copyright (c) <2009>, Jaakko Paukamainen
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
 *  OpenIDLoginForm - class
 *
 *  @package 	Forms
 *  @author 	Jaakko Paukamainen
 *  @copyright 	2009 Jaakko Paukamainen
 *  @license 	GPL v2
 *  @version 	1.0
 */
class Default_Form_OpenIDLoginForm extends Zend_Form
{
    public function __construct($options = null) 
    { 
        parent::__construct($options);
		
		$translate = Zend_Registry::get('Zend_Translate'); 
		$language = $translate->getLocale();
		$baseurl = Zend_Controller_Front::getInstance()->getBaseUrl();
		$actionUrl = $baseurl.'/'.$language.'/account/openid';
		
		$this->setName('openid_login_form');
		$this->setAction($actionUrl);
		$this->addElementPrefixPath('Oibs_Decorators', 
								'Oibs/Decorators/',
								'decorator');
		
		// Url input form element
		$inputurl = new Zend_Form_Element_Text('openid_identifier');
		$inputurl->setLabel($translate->_("account-openid-loginurl"))
				 ->addFilter('StringtoLower')
                 ->setRequired(true)
				 ->addValidators(array(
				 array('NotEmpty', true, array('messages' => array('isEmpty' => $translate->_('account-login-field-empty')))), 
				 ))
				 ->setDecorators(array('LoginDecorator'));
		
		// Form submit buttom element
		$submit = new Zend_Form_Element_Submit('openid_action');
		//$submit->setValue('submit');
		$submit->setLabel($translate->_("account-login-submit"));
		
		// Add elements to form
		$this->addElements(array($inputurl, $submit));
	}
}