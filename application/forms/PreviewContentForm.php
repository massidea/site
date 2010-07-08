<?php
/**
 *  PreviewContentForm -> Form for content previewing
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
 *  AddContentForm - class
 *
 *  @package 	Forms
 *  @author 	Jaakko Paukamainen
 *  @copyright 	2010 Jaakko Paukamainen
 *  @license 	GPL v2
 *  @version 	1.0
 */
class Default_Form_PreviewContentForm extends Zend_Form
{
    public function __construct($options = null) 
    { 
        parent::__construct($options);
        
		$translate = Zend_Registry::get('Zend_Translate'); 
		$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
		$this->setDisableLoadDefaultDecorators(true);
 
        $this->addDecorator('FormElements')
             ->addDecorator('HtmlTag', array('tag' => 'div'))
             ->addDecorator('Form')
             ->removeDecorator('DtDdWrapper');

		
		$this->setName('preview_content_form');

        //$this->setAction($baseUrl.'/'.$lang.'/content/add/'.$contentType);
		
        $this->addElementPrefixPath('Oibs_Decorators', 
						'Oibs/Decorators/',
						'decorator');
		
        $this->setAttrib('enctype', 'multipart/form-data');
        
        // Form buttons
        
        $publish = new Zend_Form_Element_Submit('content_publish');
        $publish->setLabel($translate->_("content-add-publish"))
                ->removeDecorator('DtDdWrapper');
        
        $save = new Zend_Form_Element_Submit('content_save');
        $save->setLabel($translate->_("content-add-save"))
             ->removeDecorator('DtDdWrapper');
        
        $edit = new Zend_Form_Element_Submit('content_edit');
        $edit->setLabel($translate->_("content-add-edit"))
                ->removeDecorator('DtDdWrapper');
        
        // Set custom form layout
        /*
        $this->setDecorators(array(array('ViewScript', array(
            'viewScript' => 'forms/contentAddForm.phtml',
            'placement' => false
        ))));*/

	// Add generic elements to form
        $this->addElements(array(
            //$publish,
            //$save,
            $edit
        ));

    }
}
