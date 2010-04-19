<?php
/**
 *  SimpleSearchForm -> Simple search form creation
 *
* 	Copyright (c) <2009>, Markus Riihelä
* 	Copyright (c) <2009>, Mikko Sallinen
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
 *  SimpleSearchForm - class
 *
 *  @package 	Forms
 *  @author 		Markus Riihelä & Mikko Sallinen
 *  @copyright 	2009 Markus Riihelä & Mikko Sallinen
 *  @license 	GPL v2
 *  @version 	1.0
 */
class Default_Form_SimpleSearchForm extends Zend_Form
{
    public function __construct($options = null) 
    { 
        parent::__construct($options);
		
		$translate = Zend_Registry::get('Zend_Translate'); 
		
		$this->setDisableLoadDefaultDecorators(true);
 
        $this->addDecorator('FormElements')
             ->addDecorator('HtmlTag', array('tag' => 'div'))
             ->addDecorator('Form');
		
		$this->setName('simple_search_form');
		$this->addElementPrefixPath('Oibs_Decorators', 
								'Oibs/Decorators/',
								'decorator');
		
		// Search input 
		$q = new Zend_Form_Element_Text('q');
		$q->addFilter('StringtoLower')
				->addValidators(array(
					array('NotEmpty', true, array('messages' => array('isEmpty' => 'Tyhjä'))), 
				))
				->setDecorators(array('SimpleSearchDecorator'));
		
		// Search submit 
		$submit = new Zend_Form_Element_Submit('submitsearch');
        $submit->setLabel($translate->_("search-search"));
        //$submit->setLabel('Moi');
		$submit->removeDecorator('DtDdWrapper');
		
		// Add elements to form
		$this->addElements(array($q, $submit));
	}
}