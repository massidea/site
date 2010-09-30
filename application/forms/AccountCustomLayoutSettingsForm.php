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
		
        $this->setMethod('post');
        $this->setEnctype('multipart/form-data');
		$this->setName('custom_layout_settings_form');
		$this->addElementPrefixPath('Oibs_Form_Decorator',
                                'Oibs/Form/Decorator/',
                                'decorator');
		
		$clear = new Oibs_Form_Element_Note('clear');
        $clear->setValue('<div class="clear"></div>');
        
        $clearall = new Oibs_Form_Element_Note('clearall');
        $clearall->setValue('<div style="clear:both;"></div>');
        
        $bgimageclear = new Oibs_Form_Element_Note('clear');
        $bgimageclear->setValue('<div style="clear:both;"></div>');
        
        $testdivstart = new Oibs_Form_Element_Note('testdivstart');
        $testdivstart->setValue('<div class="backgrounddiv" style="float:right; min-width:410px; width:auto !important; min-height:150px; height:auto !important; height:300px; margin-top:-5px; padding-left:20px; border-left:1px solid silver;">');

        $testdivend = new Oibs_Form_Element_Note('testdivend');
        $testdivend->setValue('</div> <!-- end fontdiv -->');

        $testdiv2start = new Oibs_Form_Element_Note('testdiv2start');
        $testdiv2start->setValue('<div class="fontdiv" style="float:left; min-width:280px; width:auto !important; min-height:150px; height:auto !important; height:300px; margin-top:-25px;">');
        
        $testdiv2end = new Oibs_Form_Element_Note('testdiv2end');
        $testdiv2end->setValue('</div> <!-- end fontdiv -->');
        
        // Headers
        $fontheader = new Oibs_Form_Element_Note('fontheader');
        $fontheader->setValue('<label style="float:left; width:270px; font-weight:bold; font-size:1.15em; margin-top:-8px; margin-left:70px;">Font</label>');
        
        $bgheader = new Oibs_Form_Element_Note('bgheader');
        $bgheader->setValue('<label style="width:290px; font-weight:bold; font-size:1.15em; margin-top:-8px;">Custom background</label>');
        
		// Font
		/*$customfontfamilylist = array('sans-serif'=> 'Arial', 'sans-serif' => '\"Arial Black\"', 'cursive' => '\"Comic Sans MS\"', 'monospace' => '\"Courier New\"', 'serif' => 'Georgia',
								'sans-serif' => 'Impact', 'sans-serif' => 'Tahoma', 'serif' => '\"Times New Roman\"', 'sans-serif' => '\"Trebuchet MS\"', 'sans-serif' => 'Verdana');*/
		$customfontlist = array('Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Georgia', 'Impact', 'Tahoma', 'Times New Roman', 'Trebuchet MS', 'Verdana');
		$selectionValue = array_search($options['cssFontType'], $customfontlist);
		$customfont = new Zend_Form_Element_Select('customfont');
		$customfont->setLabel('Font')
				   ->setAttrib('id', 'customfont')
				   //->setAttrib('style', '/*margin-top:-1px;*/')
				   ->addMultiOptions($customfontlist)
				   //->addMultiOptions($customfontfamilylist)
				   ->setValue($options['cssFontType']);
		
		$customfontclear = new Oibs_Form_Element_Note('customfontclear');
        $customfontclear->setValue('<div style="clear:both;"></div>');
				   
		// Font size
		//$customfontsizes = array('8', '9', '10', '11', '12', '13', '14');
		//$customfontsizes = array('0' => '8', '1' => '9', '2' => '10', '3' => '11', '4' => '12', '5' => '13', '6' => '14');
		$customfontsizes = array('8px' => '8', '9px' => '9', '10px' => '10', '11px' => '11', '12px' => '12', '13px' => '13', '14px' => '14');
		$customfontsize = new Zend_Form_Element_Select('customfontsize');
		//$customfontsize->setLabel('Font Size')
		$customfontsize->setLabel('')
				   ->setAttrib('id', 'customfontsize')
				   ->setAttrib('style', 'float:left; text-align:left; margin-left:10px; margin-right: 10px; margin-top:-7px;')
				   ->addMultiOptions($customfontsizes)
				   ->setValue($options['cssFontSize']);

		$customsizeclear = new Oibs_Form_Element_Note('customsizeclear');
        $customsizeclear->setValue('<div class="clear"></div>');
		
        // Font color
		$customfontcolor = new Zend_Form_Element_Text('customfontcolor');
        $customfontcolor->setLabel('Color')
               ->setAttrib('id', 'customfontcolor')
               ->setAttrib('style', 'margin-top:-15px')
               ->setValue($options['cssFontColor']);
        				   

        $customfontcolorclear = new Oibs_Form_Element_Note('customfontcolorlear');
        $customfontcolorclear->setValue('<div class="clear"></div>');
        
        // For font color picker script
        $customfontcolorpicker = new Oibs_Form_Element_Note('customfontcolorpicker');
        $customfontcolorpicker->setValue('<div id="picker" style="float:left; width:245px; margin-top:25px; margin-left:55px; border-right:1px solid silver;"></div>');
        
        // 
        $backgroundimage =  new Zend_Form_Element_File('backgroundimage');
		$backgroundimage->setLabel('Image :')
				->setDestination('../www/upload')
				->addValidator('Count', false, 1)
				->addValidator('Size', false, 524288)				// Max file size from 512KB
				->addValidator('Extension', false, 'jpg,png,gif')
                ->removeDecorator('DtDdWrapper')
                ->removeDecorator('DefaultDecorator')
                ->addDecorator('HtmlTag',array('tag' => 'div', 'style' => '/*float:right;*/ margin-top:-7px'))
                ->addDecorator('Label',array('tag' => 'div', 'style' => 'font-weight:bold; float:left; padding-right:10px; margin-top:-7px; margin-left: 10px; width:60px; padding-left:20px; border-left:1px solid silver;'))
                ;
               
        // Background color input
		$custombgcolor = new Zend_Form_Element_Text('custombgcolor');
		$custombgcolor->setLabel('Color')
               ->setAttrib('id', 'custombgcolor')
               ->setAttrib('style', 'margin-top:-15px')
               /*->removeDecorator('DtDdWrapper')
               ->removeDecorator('DefaultDecorator')*/
               ->setValue($options['cssBackgroundColor']);

		// For background color picker script
        $custombgcolorpicker = new Oibs_Form_Element_Note('custombgcolorpicker');
        $custombgcolorpicker->setValue('<div id="custombgcolorpicker" style="float:right; margin-top:25px; margin-right:120px;"></div>');
									   //'<div id="picker" style="float:left; width:245px; margin-top:30px; margin-left:45px; border-right:1px solid silver;"></div>'
        // Submit button
        $submit = new Zend_Form_Element_Submit('custom_layout_button');
		//$submit->setLabel($translate->_("account-register-submit"))
		$submit->setLabel('Save')
				->setAttrib('style', 'width:60px; float:right; margin-right:20px')
        		->removeDecorator('DefaultDecorator')
        		->removeDecorator('DtDdWrapper');
        $submitclear = new Oibs_Form_Element_Note('submitclear');
        $submitclear->setValue('<div style="clear:both;"></div>');
        
        $savebgimagebutton = new Zend_Form_Element_Submit('savebgimagebutton');
		//$savebgimagebutton->setLabel($translate->_("account-register-submit"))
		$savebgimagebutton->setLabel('Upload')
				->setAttrib('style', 'float:right; margin-right:20px; margin-top:-22px; width:60px')
        		->removeDecorator('DefaultDecorator')
        		->removeDecorator('DtDdWrapper');
        
        $bgimageinuse = new Zend_Form_Element_Checkbox('bgimage_in_use');
        $bgimageinuse->setLabel(' Use < '.$options['cssBackgroundImage'].' >');
        
        if(strlen($options['cssBackgroundImage'])) {
	        $setbgimage = new Oibs_Form_Element_Note('setbgimage');
	        $setbgimage->setLabel($options['cssBackgroundImage']);
	        
	        $removesetbgimage = new Oibs_Form_Element_Note('removesetbgimage');
	        $removesetbgimage->setValue('<img src="/images/icon_red_cross.png"/>');
	        				 //->setAttrib('type', 'submit');
	        
	        $backgroundimagehidden = new Zend_Form_Element_Hidden('backgroundimagehidden');
        	$backgroundimagehidden->setAttrib('id', 'backgroundimagehidden')
        			 			  ->setValue($options['cssBackgroundImage']);
			
			$backgroundimageoldhidden = new Zend_Form_Element_Hidden('backgroundimageoldhidden');
        	$backgroundimageoldhidden->setAttrib('id', 'backgroundimageoldhidden')
        			 			  ->setValue($options['cssBackgroundImage']);
        }
        
        // Block separator
		$blockseparator = new Oibs_Form_Element_Note('blockseparator');
        $blockseparator->setValue('<center><div style="width:676px; height:1px; border-bottom:1px solid silver; margin-bottom:10px;"></div></center>');

        $advancedbutton = new Zend_Form_Element_Button('advencedsettingsbutton');
		//$savebgimagebutton->setLabel($translate->_("account-register-submit"))
		$advancedbutton->setLabel('Advanced')
				->setAttrib('style', 'float:left; margin-left:20px; width:80px')
        		->removeDecorator('DefaultDecorator')
        		->removeDecorator('DtDdWrapper');
         
        // Help
        $help = new Oibs_Form_Element_Note('help_link_basic');
        $help->setValue('<a href="#" onClick="return false;">Help</a>');
        		
        // Add elements to form
		$this->addElements(array(/*$bgheader,
								 $fontheader,
								 $clearall,*/
								 $customfont,
							 	 $customfontsize,
							 	 $backgroundimage,
								 $savebgimagebutton,
								 //$clearall,
								 $removesetbgimage,
								 $setbgimage,
								 $clearall,
							 	 $customfontcolor,
							 	 //$bgimageinuse,
							 	 $clearall,
							 	 $custombgcolor,
							 	 $clearall,
							 	 $custombgcolorpicker,
							 	 $customfontcolorpicker,
								 $submitclear,
								 $blockseparator,
								 $submit,
								 $advancedbutton,
								 $clearall,
								 $help,
								 //$backgroundimageoldhidden,
								 $backgroundimagehidden
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
		
		$this->setDecorators(array(
            'FormElements',
            'Form'
        ));
	}
}

