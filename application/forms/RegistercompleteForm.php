<?php
/**
 * RegistrationForm
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
 *  RegistrationForm - class
 *
 *  @package 	Forms
 *  @author 	Joel Peltonen
 *  @license 	GPL v2
 *  @version 	1.0
 */
class Default_Form_RegistercompleteForm extends Zend_Form
{
    public function __construct($options = null) 
    { 
        parent::__construct($options);
		$translate = Zend_Registry::get('Zend_Translate'); 

		$this->setName('registercomplete_form');
		$this->setAttrib('id', 'registercomplete_form');
		$this->addElementPrefixPath('Oibs_Decorators', 'Oibs/Decorators/', 'decorator');
		$this->addElementPrefixPath('Oibs_Validators', 'OIBS/Validators/', 'validate');
        $this->removeDecorator('DtDdWrapper');
    
        $firstname = new Zend_Form_Element_Text('firstname');
        $firstname->setLabel($translate->_("account-register-firstname"))
                ->setRequired(false)
                ->removeDecorator('DtDdWrapper')
                ->setAttrib('id', 'registercomplete_firstname')
                ->setDecorators(array('ViewHelper',
                                      array('HtmlTag', array('tag' => 'div', 'class' => 'registrationcomplete_firstname'))
                ))
				->setDecorators(array('RegistercompleteDecorator'));

        $surname = new Zend_Form_Element_Text('surname');
        $surname->setLabel($translate->_("account-register-surname"))
                ->setRequired(false)
                ->removeDecorator('DtDdWrapper')
                ->setDecorators(array('ViewHelper',
                                      array('HtmlTag', array('tag' => 'div', 'class' => 'registrationcomplete_surname'))
                ))
                ->setAttrib('id', 'registercomplete_surname')
				->setDecorators(array('RegistercompleteDecorator')); 
                
        $image =  new Zend_Form_Element_File('image');
		$image->setLabel($translate->_("account-registercomplete-add-image"))
				->setDestination('../www/upload')
				->addValidator('Count', false, 1)
				->addValidator('Size', false, 25600)
				->addValidator('Extension', false, 'jpg,png,gif')
                ->removeDecorator('DtDdWrapper')
                ->setAttrib('id', 'registercomplete_image')
                ->removeDecorator('DefaultDecorator')
                ->addDecorator('HtmlTag',array('tag' => 'div', 'id' => 'form_registercomplete_image', 'style' => 'display:block'))
                ->addDecorator('Label',array('tag' => 'div', 'id' => 'form_registercomplete_image_label', 'style' => 'display:block'));

        $bio = new Zend_Form_Element_Textarea('bio');
        $bio->setLabel($translate->_("account-register-bio"))
            ->setRequired(false)
            ->removeDecorator('DtDdWrapper')
            ->setAttrib('id', 'registercomplete_bio')
            ->setAttribs(array(
					"onkeydown" => "textCounter(this,'progressbar_bio',0,160,".$translate->getLocale().");",
					"onkeyup" => "textCounter(this,'progressbar_bio',0,160,".$translate->getLocale().");"
					))
            ->setDescription($translate->_('account-register-bio-desc'))
			->setDecorators(array('RegistercompleteDecorator')); 
   
        // $bio_counter here...
        
        $select_str = $translate->_("account-register-select");

        $country = new Zend_Form_Element_Select('country');
        $country->setLabel($translate->_("account-register-country"))
                ->setRequired(false)
                ->removeDecorator('DtDdWrapper')
                ->setMultiOptions(array($select_str,
                                        'fi'=>'Finland'))
                ->setAttrib('id', 'registercomplete_country')
                ->setDecorators(array('ViewHelper',
                                      array('HtmlTag', array('tag' => 'div', 'class' => 'registrationcomplete_country'))
                ))
				->setDecorators(array('RegistercompleteDecorator')); 

        $deflang = new Zend_Form_Element_Select('deflang');
        $deflang->setLabel($translate->_("account-register-deflang"))
                ->setMultiOptions(array($select_str,
                                        'en'=>'English',
                                        'fi'=>'Finnish'))
                ->addFilter('StringToLower')
                ->setRequired(false)
                ->removeDecorator('DtDdWrapper')
                ->setDecorators(array('ViewHelper',
                                      array('HtmlTag', array('tag' => 'div', 'class' => 'registrationcomplete_deflang'))
                ))
                ->setAttrib('id', 'registercomplete_deflang')
                ->setDecorators(array('RegistercompleteDecorator'));

        $submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel($translate->_("account-register-submit"))
               ->removeDecorator('DtDdWrapper')
               ->setAttrib('id', 'registercomplete_submit')
               ->setDecorators(array('ViewHelper',
                                     array('HtmlTag', array('tag' => 'div', 'class' => 'registrationcomplete_submit_div'))
                                ))
               ->setAttrib('class', 'registrationcomplete_form_submit_' . $translate->getLocale())
               ->setAttrib('disabled', 'true')
               ;
       
       $this->addElements(array($firstname, $surname, $image, $bio, $country, $deflang, $submit));
       
       $this->setAttrib('enctype', 'multipart/form-data');
       $this->addDisplayGroup(array('firstname', 'surname', 'image', 'bio', 'country', 'deflang', 'submit'),'allFields');
       $this->allFields->removeDecorator('DtDdWrapper');
       $this->allFields->setLegend('register-confirmations');       
    }
}
