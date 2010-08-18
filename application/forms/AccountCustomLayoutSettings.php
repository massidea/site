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
 class Default_Form_AccountCustomLayoutSettings extends Zend_Form
{
	public function __construct($options = null) 
	{ 
        parent::__construct();
		
		$this->setName('custom_layout_settings_form');
		$this->addElementPrefixPath('Oibs_Form_Decorator',
                                'Oibs/Form/Decorator/',
                                'decorator');
		
		//echo '<div class="clear"></div>';
		$clear = new Oibs_Form_Element_Note('clear');
        $clear->setValue('<div class="clear"></div>');

		// Font
		$customfont = new Zend_Form_Element_Select('customfont');
		$customfont->setLabel('Custom Font')
				   ->setAttrib('id', 'customfont')
				   ->addMultiOptions(array('Arial', 'Castellar', 'Times New Roman', 'Microsoft Sans Serif', 'Harrington'));
		
		// Font size
		$customfontsize = new Zend_Form_Element_Select('customfontsize');
		$customfontsize->setLabel('Font Size')
				   ->setAttrib('id', 'customfontsize')
				   ->addMultiOptions(array('8', '10', '12', '14', '16'));

		$customsizeclear = new Oibs_Form_Element_Note('customsizeclear');
        $customsizeclear->setValue('<div class="clear"></div>');
		
        // Font color
		$customfontcolor = $openid = new Zend_Form_Element_Text('customfontcolor');
        $customfontcolor->setLabel('Font Color')
               ->setAttrib('id', 'customfontcolor')
               ->setValue('#000000');
        				   
		// Add elements to form
		$this->addElements(array($customfont,
								 $clear,
								 $customfontsize,
								 //$customsizeclear,
								 $customfontcolor));
		
		
		// Add decorators
		$customfont->setDecorators(array('InputDecorator'));
		$customfontsize->setDecorators(array('InputDecorator'));
		$customfontcolor->setDecorators(array('ColorPickerDecorator'));
		
		$this->setDecorators(array(
            'FormElements',
            'Form'
        ));
	}
	
/*
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
	*/
}