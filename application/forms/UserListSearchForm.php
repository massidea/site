<?php
/**
 *  UserListSearchForm -> Form for searching users.
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
 *  UserListSearchForm - class
 *
 *  @package 	Forms
 *  @author 	Markus Riihelä & Mikko Sallinen
 *  @copyright 	2009 Markus Riihelä & Mikko Sallinen
 *  @license 	GPL v2
 *  @version 	1.0
 */
 class Default_Form_UserListSearchForm extends Zend_Form
{
    public function __construct($options = null, $formData) 
    { 
        parent::__construct($options);
		
		$translate = Zend_Registry::get('Zend_Translate'); 
		
		$this->setDisableLoadDefaultDecorators(true);
 
        $this->addDecorator('FormElements')
             ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'userlist_filter'))
             ->addDecorator('Form');
		
		$this->setName('user_list_search');
		$this->addElementPrefixPath('Oibs_Decorators', 
								'Oibs/Decorators/',
								'decorator');
		
		// Username search input 
		$username = new Zend_Form_Element_Text('username');
		$username->setLabel($translate->_('userlist-filter-username'))
                 ->addFilter('StringtoLower')
                 ->setDecorators(array('UserListFilterElementDecorator'))
                 ->setValue(isset($formData['username']) ? $formData['username'] : '');
        
        /*
        // User content amount select
		$contentSelect = new Zend_Form_Element_Select('counttype');
		$contentSelect->setLabel($translate->_('userlist-filter-action-label'))
				      ->addFilter('StringToLower')
				      ->setDecorators(array('UserListFilterSelectDecorator'))
				      ->setMultiOptions(array($translate->_('userlist-filter-more-than'), 
                                              $translate->_('userlist-filter-less-than'), 
                                              $translate->_('userlist-filter-equal')))
                      ->setValue(isset($formData['counttype']) ? $formData['counttype'] : '');
        
        // User content amount search input 
		$contentAmount = new Zend_Form_Element_Text('contentlimit');
		$contentAmount->setLabel($translate->_('userlist-filter-content-count-label'))
                      ->addFilter('StringtoLower')
                      ->setDecorators(array('UserListFilterContentDecorator'))
                      ->setValue(isset($formData['contentlimit']) ? $formData['contentlimit'] : '');
         
        
        // User country select
		$countrySelect = new Zend_Form_Element_Select('country');
		$countrySelect->setLabel($translate->_('userlist-filter-country-label'))
				      ->addFilter('StringToLower')
				      ->setDecorators(array('UserListFilterElementDecorator'))
				      ->setMultiOptions($formData['countryList'])
                      ->setValue(isset($formData['country']) ? $formData['country'] : '');
                      
        */
        // User city input
		$city = new Zend_Form_Element_Text('city');
		$city->setLabel($translate->_('userlist-filter-city-label'))
              ->addFilter('StringToLower')
              ->setDecorators(array('UserListFilterElementDecorator'))
              ->setValue(isset($formData['city']) ? $formData['city'] : '');
           
		// Search submit 
		$submit = new Zend_Form_Element_Submit('filter');
		$submit->setLabel($translate->_('userlist-filter-submit'));
		$submit->removeDecorator('DtDdWrapper');
		
		// Add elements to form
		$this->addElements(array($username, /*$contentSelect, $contentAmount, $countrySelect,*/ $city, $submit));	
	}
}