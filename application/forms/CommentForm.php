<?php
/**
 *  CommentForm -> Comment form for content commenting
 *
* 	Copyright (c) <2009>, Markus Riihelä
* 	Copyright (c) <2009>, Mikko Sallinen
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
 *  CommentForm - class
 *
 *  @package 	Forms
 *  @author 		Markus Riihelä & Mikko Sallinen
 *  @copyright 	2009 Markus Riihelä & Mikko Sallinen
 *  @license 	GPL v2
 *  @version 	1.0
 */
 class Default_Form_CommentForm extends Zend_Form
{
	 public function __construct($parent = null, $options = null) 
    { 
        parent::__construct($options);
		
        $parentId = 0;
        if ($parent != null) {
            $parentId = $parent;
        }
        
		$translate = Zend_Registry::get('Zend_Translate'); 
		
		$this->setName('comment_form');
		$this->addElementPrefixPath('Oibs_Decorators', 
								'Oibs/Decorators/',
								'decorator');
		/*						
		$comment_subject = new Zend_Form_Element_Text('comment_subject');
		$comment_subject->setLabel($translate->_("content-view-comment-form-subject"))
				->setRequired(true)
				->setAttribs(array(
					'class' => 'comment_subject'))
				->addValidators(array(
					array('NotEmpty', true, array('messages' => array('isEmpty' => 'Tyhjä')))
				));
		*/
        
		$comment_message = new Zend_Form_Element_Textarea('comment_message');
		$comment_message/*->setLabel($translate->_("content-view-comment-form-message"))*/
				->setRequired(true)
				->setAttribs(array(
					'rows' => 10,
					'cols' => 58,
					'class' => 'comment_textarea'))
				->addValidators(array(
					array('NotEmpty', true, array('messages' => array('isEmpty' => 'Tyhjä')))
				));
				
		$comment_parent = new Zend_Form_Element_Hidden('comment_parent');
		$comment_parent->setValue($parentId)
                       ->setAttribs(array(
					'class' => 'comment_subject'));
				
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel($translate->_("submit"));
		
		
		$this->addElements(array(/*$comment_subject, */$comment_message, $comment_parent, $submit));
	}
}