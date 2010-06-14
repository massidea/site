<?php
/**
 *  PrivMsgForm -> Form for sending private messages
 *
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
 *  PrivMsgForm - class
 *
 *  @package 	Forms
 *  @author 	Pekka Piispanen
 *  @copyright 	2009 Pekka Piispanen
 *  @license 	GPL v2
 *  @version 	1.0
 */
class Default_Form_PrivMsgForm extends Zend_Form
{
    public function __construct($options = null, $data = null) 
    { 
        parent::__construct($options);
        
        $translate = Zend_Registry::get('Zend_Translate');
		
		$this->setDisableLoadDefaultDecorators(true);
 
        $this->addDecorator('FormElements')
             ->addDecorator('HtmlTag', array('tag' => 'div'))
             ->addDecorator('Form');
		
		$this->setName('send_privmsg_form');
		$this->addElementPrefixPath('Oibs_Decorators', 
						'Oibs/Decorators/',
						'decorator');

        $header = new Zend_Form_Element_Text('privmsg_header');
        $header->setLabel($translate->_("privmsg-header"))
              ->setAttrib('size', 53)
              ->addValidators(array(
                array('StringLength', false, array(1, 255,'messages' => array('stringLengthTooShort' => 'field-too-short')))
              ))
              ->setDecorators(array('PrivMsgTextFieldDecorator'));
                        
		$message = new Zend_Form_Element_Textarea('privmsg_message');
		$message->setLabel($translate->_("privmsg-message"))
				->setRequired(true)
                ->setAttrib('rows', 6)
                ->setAttrib('cols', 40)
				->addValidators(array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'field-empty'))), 
				))
				->setDecorators(array('PrivMsgMessageDecorator'));
                
        $sender_id = new Zend_Form_Element_Hidden('privmsg_sender_id');
		$sender_id->setValue($data['sender_id'])
                  ->removeDecorator('DtDdWrapper');
        
        $receiver_id = new Zend_Form_Element_Hidden('privmsg_receiver_id');
		$receiver_id->setValue($data['receiver_id'])
                    ->removeDecorator('DtDdWrapper');
                
        // Form submit buttom form element
		$submit = new Zend_Form_Element_Submit('privmsg_submit');
		$submit->setLabel($translate->_("privmsg-send"))
               ->removeDecorator('DtDdWrapper');
        
        $this->addElements(array($header, $message, $sender_id, $receiver_id, $submit));
	
    }
}