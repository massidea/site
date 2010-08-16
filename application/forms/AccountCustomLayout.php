<?php
/**
 *  ChangeTranslationLanguageForm -> Form for changing translation language
 *
 * 	Copyright (c) <2010>, Jaakko Paukamainen
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
 *  @author 	2010 Janne Vaaraniemi
 *  @copyright 	2010 Janne Vaaraniemi
 *  @license 	GPL v2
 *  @version 	1.0
 */
 class Default_Form_AccountCustomLayout extends Zend_Form
{
	public function __construct($options = null) 
	{ 
        parent::__construct();
		
		$this->setName('layout_form');
		$this->addElementPrefixPath('Oibs_Decorators', 
								'Oibs/Decorators/',
								'decorator');
		$this->removeDecorator('Errors');
		$this->removeDecorator('HtmlTag');
		$this->removeDecorator('Label');

		//$this->setAction($this->_generateActionUrl());
        
		$layout_select = new Zend_Form_Element_Select('layout_select');
		$layout_select->setAttrib('onchange', '$("#submit").click();');
		$layout_select->removeDecorator('Errors');
		$layout_select->removeDecorator('Label');
		$layout_select->removeDecorator('HtmlTag');
		
		/*foreach($options as $language)
			$translation_select->addMultiOption($language['iso6391_lng'], $language['name_lng']);
		*/
		$layout_select->addMultiOption('default', 'Default');
		$layout_select->addMultiOption('custom', 'Custom');
		
		//$layout_select->setValue($this->_getCurrentLayoutSelect());
		$layout_select->setValue('default');
		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->removeDecorator('DtDdWrapper');
		$submit->setAttrib('style', 'display: none;');
				
		$this->addElements(array($layout_select, $submit));
	}
	
	private function _generateActionUrl()
	{
		$urlHelper = new Zend_View_Helper_Url();
		return $urlHelper->url(array('controller' => 'misc',
									 'action' => 'changelayout',
									 'language' => $this->view->language),
									 'lang_default', true);
	}
	
	private function _getCurrentLayoutSelect()
	{
		$layoutSession = new Zend_Session_Namespace('layout');
		return $layoutSession->translateTo;
	}
}