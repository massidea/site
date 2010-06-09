<?php
/**
 *  LimitTagsForm -> Select limits form for limiting tags
 *
* 	Copyright (c) <2009>, Juhani Jaakkola
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
 *  LimitTagsForm - class
 *
 *  @package 	Forms
 *  @author 	Juhani Jaakkola
 *  @copyright 	2009 Juhani Jaakkola
 *  @license 	GPL v2
 *  @version 	1.0
 */

//Limit tags form
class Default_Form_LimitTagsForm extends Zend_Form
{
	public function __construct($options = null) 
    { 
        parent::__construct($options);
        
        $translate = Zend_Registry::get('Zend_Translate');
      
        $this->setName('limit_tags_form')
        	->removeDecorator('HtmlTag');
        
        $show = new Zend_Form_Element_Select('show');
        $show->setLabel($translate->_("tag-cloud-number"))
        	->removeDecorator('Label')
			->addDecorator('Label')
        	->removeDecorator('HtmlTag')
        	
        	//Number of shown tags
        	->setMultiOptions(array(0=>$translate->_("tag-cloud-show-all"), 20=>20, 50=>50, 100=>100, 150=>150, 200=>200));
        	
        $selection = new Zend_Form_Element_Select('selection');
        $selection->setLabel($translate->_("tag-cloud-sortby"))
        	->removeDecorator('HtmlTag')
        	->removeDecorator('Label')
			->addDecorator('Label')
        	
        	->setMultiOptions(array('none'=>'-', 'name'=>$translate->_("tag-cloud-selection-name"), 'popularity'=>$translate->_("tag-cloud-selection-popularity"), 'creation'=>$translate->_("tag-cloud-selection-creation"), 'modified' =>$translate->_("tag-cloud-selection-modified"),'length' =>$translate->_("tag-cloud-selection-length"), 'ID'=>'ID'));
        	
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel($translate->_("tag-cloud-show"))
        	->removeDecorator('DtDdWrapper');
        
        
 
        $this->addElements(array($show, $selection, $submit));
      
    }	
}