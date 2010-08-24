<?php
/**
 *  AccountCustomLayoutSettingsForm -> Form for inserting custom layout parameters
 *
 * 	Copyright (c) <2010>, Janne Vaaraniemi
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
        
        $bgimageclear = new Oibs_Form_Element_Note('clear');
        $bgimageclear->setValue('<div style="clear:both;"></div>');
        
        $testdivstart = new Oibs_Form_Element_Note('testdivstart');
        $testdivstart->setValue('<div class="backgrounddiv" style="float:right; width:410px; margin-top:-5px; padding-left:20px; border-left:1px solid silver;">');
        
        $testdivend = new Oibs_Form_Element_Note('testdivend');
        $testdivend->setValue('</div>');

        $testdiv2start = new Oibs_Form_Element_Note('testdiv2start');
        $testdiv2start->setValue('<div class="fontdiv" style="float:left; width:280px; /*border:1px solid black;*/ margin-top:-25px;">');
        
        $testdiv2end = new Oibs_Form_Element_Note('testdiv2end');
        $testdiv2end->setValue('</div>');
        
        // Headers
        $fontheader = new Oibs_Form_Element_Note('fontheader');
        $fontheader->setValue('<center><div style="font-weight:bold; font-size:1.35em; margin-top:-20px;"><label>Custom font</label></div></center>');
        
        $bgheader = new Oibs_Form_Element_Note('bgheader');
        $bgheader->setValue('<center><div style="font-weight:bold; font-size:1.35em; margin-top:-20px;"><label>Custom background</label></div></center>');
        
		// Font
		$customfont = new Zend_Form_Element_Select('customfont');
		$customfont->setLabel('Font')
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
		//$customfontsize->setLabel('Font Size')
		$customfontsize->setLabel('')
				   ->setAttrib('id', 'customfontsize')
				   ->setAttrib('style', 'float:left; text-align:left; margin-bottom:5px; margin-left:10px; margin-top:-8px;')
				   ->addMultiOptions(array('8', '10', '12', '14', '16'));

		$customsizeclear = new Oibs_Form_Element_Note('customsizeclear');
        $customsizeclear->setValue('<div class="clear"></div>');
		
        // Font color
		$customfontcolor = new Zend_Form_Element_Text('customfontcolor');
        $customfontcolor->setLabel('Color')
               ->setAttrib('id', 'customfontcolor')
               ->setAttrib('style', 'margin-top:-1px;')
               ->setValue('#000000');
        				   

        $customfontcolorclear = new Oibs_Form_Element_Note('customfontcolorlear');
        $customfontcolorclear->setValue('<div class="clear"></div>');
        
        // For font color picker script
        $customfontcolorpicker = new Oibs_Form_Element_Note('customfontcolorpicker');
        $customfontcolorpicker->setValue('<center><div id="picker" style="margin-top:0px; margin-left:10px; /*border:1px solid silver;*/"></div></center>');
        
        // 
        $backgroundimage =  new Zend_Form_Element_File('backgroundimage');
		$backgroundimage->setLabel('Image :')
				->setDestination('../www/upload')
				->addValidator('Count', false, 1)
				->addValidator('Size', false, 524288)				// Max file size from 512KB
				->addValidator('Extension', false, 'jpg,png,gif')
                ->removeDecorator('DtDdWrapper')
                ->removeDecorator('DefaultDecorator')
                //->addDecorator('HtmlTag',array('style' => 'margin-top:-11px'))
                ->addDecorator('HtmlTag',array('tag' => 'div', 'class' => 'form_addcontent_row', 'style' => '/*float:right;*/ margin-top:6px'))
                ->addDecorator('Label',array('tag' => 'div', 'class' => 'form_addcontent_row', 'style' => 'font-weight:bold; float:left; padding-right:10px; margin-top:6px; width:60px'))
                ;
               
        // Background color input
		$custombgcolor = new Zend_Form_Element_Text('custombgcolor');
		$custombgcolor->setLabel('Color')
               ->setAttrib('id', 'custombgcolor')
               ->setAttrib('style', 'margin-top:-1px;')
               ->setValue('#ffffff');

		// For background color picker script
        $custombgcolorpicker = new Oibs_Form_Element_Note('custombgcolorpicker');
        $custombgcolorpicker->setValue('<center><div id="custombgcolorpicker" style="margin-top:20px; margin-left:10px; /*border:1px solid silver;*/"></div></center>');

        // Submit button
        $submit = new Zend_Form_Element_Button('custom_layout_button');
		//$submit->setLabel($translate->_("account-register-submit"))
		$submit->setLabel('Save')
				->setAttrib('style', 'width:60px; float:right; margin-right:10px')
        		->removeDecorator('DefaultDecorator')
        		->removeDecorator('DtDdWrapper');
        $submitclear = new Oibs_Form_Element_Note('submitclear');
        $submitclear->setValue('<div style="clear:both;"></div>');
        
        $savebgimagebutton = new Zend_Form_Element_Button('savebgimagebutton');
		//$savebgimagebutton->setLabel($translate->_("account-register-submit"))
		$savebgimagebutton->setLabel('Upload')
				->setAttrib('style', 'float:right; margin-right:50px; margin-top:6px; width:60px')
        		->removeDecorator('DefaultDecorator')
        		->removeDecorator('DtDdWrapper');
        
        $bgimageinuse = new Zend_Form_Element_CheckBox('bgimage_in_use');
        $bgimageinuse->setLabel(' Use < no imagefile uploaded>');
        
        $bgcolorinuse = new Zend_Form_Element_CheckBox('bgcolor_in_use');
        $bgcolorinuse->setLabel(' Use');
		
        // Block separator
		$blockseparator = new Oibs_Form_Element_Note('blockseparator');
        $blockseparator->setValue('<center><div style="width:696px; height:1px; border-bottom:1px solid silver; margin-bottom:10px;"></div></center>');         
         
        // Add elements to form
		/*$this->addElements(array(//$customfontpreviewtext,
								 //$customfontclear,						 
								 $customfont,
								 //$clear,
								 $customfontsize,
								 $customsizeclear,
								 $customfontcolor,
								 $customfontcolorclear,
								 $customfontcolorpicker,
								 $backgroundimage));
		
		*/
         /*
         $this->addElements(array(//$customfontpreviewtext,
								 //$customfontclear,
								 $testdivstart,
								 $backgroundimage,
								 $custombgcolor,
								 $testdivend,
								 //$clear,
								 $testdiv2start,
								 $customfont,
								 //$clear,
								 $customfontsize,
								 $customsizeclear,
								 $customfontcolor,
								 $testdiv2end,
								 $customfontcolorclear,
								 $customfontcolorpicker,
								 $clear));
*/
		$this->addElements(array($testdivstart,
								 //$bgheader,
								 $savebgimagebutton,
								 $backgroundimage,
								 //$bgimageclear,
								 $bgimageinuse,
								 //$clear,
								 $custombgcolor,
								 //$bgcolorinuse,
								 //$clear,
								 $custombgcolorpicker,
								 //$clear,
								 $testdivend,
								 //$clear,
								 $testdiv2start,
								 //$fontheader,
								 $customfont,
								 //$clear,
								 $customfontsize,
								 $customsizeclear,
								 $customfontcolor,
								 //$testdiv2end,
								 $customfontcolorclear,
								 $customfontcolorpicker,
								 $testdiv2end,
								 $submitclear,
								 $blockseparator,
								 $submit
								 //$clear
								 ));
								 
		// Add decorators
		$customfont->setDecorators(array('InputDecorator2'));
		//$customfontpreviewtext->setDecorators(array(''));
		//$customfontsize->setDecorators(array('InputDecorator'));
		//$customfontcolor->setDecorators(array('ColorPickerDecorator'));
		$customfontcolor->setDecorators(array('InputDecorator2'));
		//$backgroundimage->setDecorators(array('UploadDecorator'));
		$custombgcolor->setDecorators(array('InputDecorator3'));
		$bgimageinuse->setDecorators(array('CheckBoxDecorator'));
		$bgcolorinuse->setDecorators(array('CheckBoxDecorator'));
		
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