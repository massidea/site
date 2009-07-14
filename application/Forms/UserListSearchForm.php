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
 *  @author 		Markus Riihelä & Mikko Sallinen
 *  @copyright 	2009 Markus Riihelä & Mikko Sallinen
 *  @license 	GPL v2
 *  @version 	1.0
 */
 class Forms_UserListSearchForm extends Zend_Form
{
    public function __construct($options = null) 
    { 
        parent::__construct($options);
		
		$translate = Zend_Registry::get('Zend_Translate'); 
		
		$this->setDisableLoadDefaultDecorators(true);
 
        $this->addDecorator('FormElements')
             ->addDecorator('HtmlTag', array('tag' => 'div'))
             ->addDecorator('Form');
		
		$this->setName('user_list_search');
		$this->addElementPrefixPath('Oibs_Decorators', 
								'Oibs/Decorators/',
								'decorator');
		
		// Username search input 
		$username = new Zend_Form_Element_Text('userlist_search_username');
		$username->setLabel('Username')
                 ->addFilter('StringtoLower')
                 ->setDecorators(array('UserListFilterUsernameDecorator'));
        
        // User content amount select
		$contentSelect = new Zend_Form_Element_Select('userlist_search_content_select');
		$contentSelect->setLabel('Users with')
				      ->addFilter('StringToLower')
				      ->setDecorators(array('UserListFilterSelectDecorator'))
				      ->setMultiOptions(array('More than', 'Less than', 'Equal to'));
        
        // User content amount search input 
		$contentAmount = new Zend_Form_Element_Text('userlist_search_content');
		$contentAmount->setLabel('Content')
                      ->addFilter('StringtoLower')
                      ->setDecorators(array('UserListFilterContentDecorator'));
        
        // User member checkbox
        $userMember = new Zend_Form_Element_Checkbox('userlist_search_user_member');
		$userMember->setLabel('Member')
                   ->addFilter('StringtoLower')
                   ->setDecorators(array('UserListFilterCheckboxDecorator'));
                
        // User admin checkbox
        $userAdmin = new Zend_Form_Element_Checkbox('userlist_search_user_admin');
		$userAdmin->setLabel('Admin')
                   ->addFilter('StringtoLower')
                   ->setDecorators(array('UserListFilterCheckboxDecorator'));
                
		// Search submit 
		$submit = new Zend_Form_Element_Submit('submit_user_filter');
		$submit->setLabel($translate->_('Filter'));
		$submit->removeDecorator('DtDdWrapper');
		
		// Add elements to form
		$this->addElements(array($username, $contentSelect, $contentAmount, $userMember, $userAdmin, $submit));
	}
}