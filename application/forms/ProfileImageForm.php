<?php
/**
 *  ProfileImageForm -> Form for uploading new profile images
 *
* 	Copyright (c) <2009>, Tuomas Valtanen
* 	
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
 *  ProfileImageForm - class
 *
 *  @package 	Forms
 *  @author 	Tuomas Valtanen
 *  @copyright 	2009 Tuomas Valtanen
 *  @license 	GPL v2
 *  @version 	1.0
 */
 
class Default_Form_ProfileImageForm extends Zend_Form
{
    public function __construct($options = null) 
    { 
        parent::__construct($options);

		$translate = Zend_Registry::get('Zend_Translate'); 
		
		$this->setName('account_profile_image');
		$this->addElementPrefixPath('Oibs_Decorators', 
								'Oibs/Decorators/',
								'decorator');
                                
       
        $image =  new Zend_Form_Element_File('image');
		$image->setLabel('You can also add a new image:')
				->setDestination('../www/upload')
				->addValidator('Count', false, 1)
				->addValidator('Size', false, 262144)				// Increased max file size from 25KB to 256KB
				->addValidator('Extension', false, 'jpg,png,gif')
                // I made manual decoration for the file element, I have no idea why this borgs up in the custom decorator...
                ->removeDecorator('DtDdWrapper')
                ->removeDecorator('DefaultDecorator')
                ->addDecorator('HtmlTag',array('tag' => 'div', 'class' => 'form_addcontent_row', 'style' => 'display:block'))
                ->addDecorator('Label',array('tag' => 'div', 'class' => 'form_addcontent_row', 'style' => 'display:block'));
                
                // Form submit buttom form element		
		$submit = new Zend_Form_Element_Submit('account_profile_image_submit');
		$submit->setLabel($translate->_("account-register-submit"))
        ->removeDecorator('DefaultDecorator')
         ->removeDecorator('DtDdWrapper');
         
		
		// Add elements to form
		$this->setAttrib('enctype', 'multipart/form-data');
		$this->addElements(array($image, $submit));
     }
}