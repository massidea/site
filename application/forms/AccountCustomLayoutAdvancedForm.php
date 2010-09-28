<?php
/**
 *  AccountCustomLayoutAdvancedForm -> Form for inserting custom layout parameters directly to css-file editor
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
 *
 *  @package 	Forms
 *  @author 	2010 Janne Vaaraniemi
 *  @copyright 	2010 Janne Vaaraniemi
 *  @license 	GPL v2
 *  @version 	1.0
 */
 class Default_Form_AccountCustomLayoutAdvancedForm extends Zend_Form
{
	public function __construct($options = null) 
	{ 
        parent::__construct();
		
        $this->setMethod('post');
        $this->setEnctype('multipart/form-data');
        //$this->setAction('');
		$this->setName('custom_layout_advanced_form');
		$this->addElementPrefixPath('Oibs_Form_Decorator',
                                'Oibs/Form/Decorator/',
                                'decorator');
        
		$clearall = new Oibs_Form_Element_Note('clearall');
        $clearall->setValue('<div style="clear:both;"></div>');
		
        $csstextarea = new Zend_Form_Element_Textarea('csscontent');
        $csstextarea->setLabel('Editable custom layout css for advanced users')
                  ->setAttrib('id', 'css_textarea')
                  ->setAttrib('style', 'width: 575px; height: 660px; margin-left: -5px; margin-right: 10px; margin-bottom: 10px;')
                  ->setValue($options['cssContent'])
                  ->addDecorator('Label',array('tag' => 'div', 'style' => '/*font-weight:bold;*/ float:left; margin-top:6px'))
                  ->addValidators(array(
                      array('StringLength',
                          false,
                      ),
		));
		
		$savecssbutton = new Zend_Form_Element_Submit('save_css_button');
		$savecssbutton->setLabel('Save')
				->setAttrib('style', 'width:60px; float:right;')
        		->removeDecorator('DefaultDecorator')
        		->removeDecorator('DtDdWrapper');
        
		$cancelcssbutton = new Zend_Form_Element_Button('cancel_css_button');
		$cancelcssbutton->setLabel('Cancel')
				->setAttrib('style', 'width:60px; float:right; margin-right:10px')
        		->removeDecorator('DefaultDecorator')
        		->removeDecorator('DtDdWrapper');
        
        $defaultcssbutton = new Zend_Form_Element_Button('default_css_button');
		$defaultcssbutton->setLabel('Default')
				->setAttrib('style', 'width:80px; float:left; margin-left: 10px; margin-top:-17px;')
        		->removeDecorator('DefaultDecorator')
        		->removeDecorator('DtDdWrapper');
        		
		$restorecssbutton = new Zend_Form_Element_Button('restore_css_button');
		$restorecssbutton->setLabel('Restore')
				->setAttrib('style', 'width:80px; float:left; margin-left: -5px; margin-top:-17px;')
        		->removeDecorator('DefaultDecorator')
        		->removeDecorator('DtDdWrapper');
        
        $defaultcsshidden = new Zend_Form_Element_Hidden('default_css');
        $defaultcsshidden->setAttrib('id', 'default_css')
        			 ->setValue($options['default_css']);
        			 
        $originalcsshidden = new Zend_Form_Element_Hidden('original_css');
        $originalcsshidden->setAttrib('id', 'original_css')
        			 ->setValue($options['cssContent']);

		$advancedhelp = new Oibs_Form_Element_Note('help_link_advanced');
		$advancedhelp->setValue('<a href="#" onClick="return false;">Help</a>');
        
        $this->addElements(array($csstextarea,
								 $savecssbutton,
								 $cancelcssbutton,
								 $advancedhelp,
								 //$clearall,
								 $restorecssbutton,
								 $defaultcssbutton,
								 $defaultcsshidden,
								 $originalcsshidden));
								 
		// Add decorators
		
		
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