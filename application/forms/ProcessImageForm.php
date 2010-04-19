<?php
/**
 *  ProcessImageForm -> Image processing form creation
 *
 * 	Copyright (c) <2009>, Jaakko Paukamainen
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
 *  ProcessImageForm - class
 *
 *  @package 	Forms
 *  @author 	Jaakko Paukamainen
 *  @copyright 	2009 Jaakko Paukamainen
 *  @license 	GPL v2
 *  @version 	1.0
 */
class Default_Form_ProcessImageForm extends Zend_Form
{
    public function __construct($options = null) 
    { 
        parent::__construct($options);
		
		$translate = Zend_Registry::get('Zend_Translate'); 
		
		$this->setName('process_image_form');
		//$this->setAction($actionUrl);
		/*
		$this->addElementPrefixPath('Oibs_Decorators', 
								'Oibs/Decorators/',
								'decorator');
		*/
		$this->removeDecorator('DtDdWrapper');
		
		// Hidden form element elements (values will be set with javascript on processimage.phtml)
		$c_x = new Zend_Form_Element_Hidden('c_x');
		$c_x->setValue('0')
		              ->removeDecorator('label')
		              ->removeDecorator('HtmlTag');
		              
		$c_y = new Zend_Form_Element_Hidden('c_y');
		$c_y->setValue('0')
		              ->removeDecorator('label')
		              ->removeDecorator('HtmlTag');
		              
		$c_w = new Zend_Form_Element_Hidden('c_w');
		$c_w->setValue('0')
		              ->removeDecorator('label')
		              ->removeDecorator('HtmlTag');
		              
		$c_h = new Zend_Form_Element_Hidden('c_h');
		$c_h->setValue('0')
		              ->removeDecorator('label')
		              ->removeDecorator('HtmlTag');
		               
		// Form submit buttom element
		$submit = new Zend_Form_Element_Submit('sendcoords');
		$submit->setLabel($translate->_("account-processimage-save"));
		
		// Add elements to form
		$this->addElements(array($c_x, $c_y, $c_w, $c_h, $submit));
	}
}