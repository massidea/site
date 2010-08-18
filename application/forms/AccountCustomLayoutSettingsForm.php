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
 class Default_Form_AccountCustomLayoutSettingsForm extends Zend_Form
{
	public function __construct($options = null) 
	{ 
        parent::__construct();
		
		$this->setName('custom_layout_settings_form');
		$this->addElementPrefixPath('Oibs_Form_Decorator',
                                'Oibs/Form/Decorator/',
                                'decorator');
		
		$clear = new Oibs_Form_Element_Note('clear');
        $clear->setValue('<div class="clear"></div>');

		// Font
		$customfont = new Zend_Form_Element_Select('customfont');
		$customfont->setLabel('Custom Font')
				   ->setAttrib('id', 'customfont')
				   ->setAttrib('style', 'margin-top:-1px;')
				   ->addMultiOptions(array('Arial', 'Castellar', 'Times New Roman', 'Microsoft Sans Serif', 'Harrington'));
		
		$customfontclear = new Oibs_Form_Element_Note('customfontclear');
        $customfontclear->setValue('<div style="clear:both;"></div>');
		
		// Custom font preview text
		$customfontpreviewtext = new Oibs_Form_Element_Note('custompreviewtext');
		$customfontpreviewtext->setValue('<div style="float:right; width:250px; display:block; padding-right:50px;">
											Aa Bb Cc Dd Ee Ff Gg Hh Ii Jj Kk Ll Mm Nn Oo Pp Qq Rr Ss Tt Uu Vv Ww Xx Yy Zz</div>');
				   
		// Font size
		$customfontsize = new Zend_Form_Element_Select('customfontsize');
		$customfontsize->setLabel('Font Size')
				   ->setAttrib('id', 'customfontsize')
				   ->setAttrib('style', 'margin-top:-1px;')
				   ->addMultiOptions(array('8', '10', '12', '14', '16'));

		$customsizeclear = new Oibs_Form_Element_Note('customsizeclear');
        $customsizeclear->setValue('<div class="clear"></div>');
		
        // Font color
		$customfontcolor = new Zend_Form_Element_Text('customfontcolor');
        $customfontcolor->setLabel('Font Color')
               ->setAttrib('id', 'customfontcolor')
               ->setAttrib('style', 'margin-top:-1px;')
               ->setValue('#000000');
        				   

        $customfontcolorclear = new Oibs_Form_Element_Note('customfontcolorlear');
        $customfontcolorclear->setValue('<div class="clear"></div>');
        
        // Font color picker script
        $customfontcolorpicker = new Oibs_Form_Element_Note('customfontcolorpicker');
        $customfontcolorpicker->setValue('<div id="picker" style="margin-top:-20px; margin-left:10px; /*border:1px solid silver;*/"></div>');
        
        // Add elements to form
		$this->addElements(array(//$customfontpreviewtext,
								 //$customfontclear,						 
								 $customfont,
								 $clear,
								 $customfontsize,
								 $customsizeclear,
								 $customfontcolor,
								 $customfontcolorclear,
								 $customfontcolorpicker));
		
		
		// Add decorators
		$customfont->setDecorators(array('InputDecorator'));
		//$customfontpreviewtext->setDecorators(array(''));
		$customfontsize->setDecorators(array('InputDecorator'));
		//$customfontcolor->setDecorators(array('ColorPickerDecorator'));
		$customfontcolor->setDecorators(array('InputDecorator'));
		
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