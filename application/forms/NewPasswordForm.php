<?php
/**
 *  NewPasswordForm -> New password request
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
 *  NewPasswordForm - class
 *
 *  @package    Forms
 *  @license    GPL v2
 *  @version    1.0
 */
class Default_Form_NewPasswordForm extends Twitter_Bootstrap_Form_Horizontal
{

    public function init()
    {
        $translate = Zend_Registry::get('Zend_Translate');
        $language = $translate->getLocale();
        $baseurl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $actionUrl = $baseurl.'/'.$language.'/account/fetchpassword';

        $this->setName('newpassword_form')
            ->setAction($actionUrl)
            ->addElementPrefixPath('Oibs_Decorators',
                'Oibs/Decorators/',
                'decorator');

        // Password input form element
        $this->addElement('password', 'password', array(
            'label'      => 'account-fetchpassword-password',
            'filter'     => 'StringtoLower',
            'required'   => true,
            'validators' => array(array(
                'StringLength',
                false,
                array(
                    4, 16,
                    'messages' => array(
                        'stringLengthTooShort' =>'field-too-short',
                        'stringLengthTooLong'  => 'field-too-long'
                    )
                ))),
            'decorators' => array(array('NewPasswordDecorator')),
        ));

        // password confirm field
        $this->addElement('password', 'confirm', array(
            'label'     => 'account-fetchpassword-confirm',
            'filter'    => 'StringtoLower',
            'decorators'=> array(array('NewPasswordDecorator')),
        ));

        // Form submit buttom element
        $this->addElement('submit', 'submit', array(
            'label'     => 'account-fetchpassword-newpassword-submit'
        ))->setAttrib('class', 'fetchpassword-submit');

        // Hidden element for new password
        $this->addElement('hidden', 'submittedform', array(
            'value'      => 'newpassword'
        ));

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
}