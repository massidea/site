<?php
/**
 *  AddGroupForm -> Form for adding groups.
 *
 * 	Copyright (c) <2009>, Mikko Aatola
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
 *  AddGroupForm - class
 *
 *  @package 	Forms
 *  @author 		Mikko Aatola
 *  @copyright 	2009 Mikko Aatola
 *  @license 	GPL v2
 *  @version 	1.0
 */
class Default_Form_AddGroupForm extends Zend_Form
{
    public function __construct($parent = null, $options = null)
    {
        parent::__construct($options);
    
        $parentId = 0;
        if ($parent != null) {
            $parentId = $parent;
        }
        
        $translate = Zend_Registry::get('Zend_Translate'); 
        
        $this->setName('add_group_form');
        $this->addElementPrefixPath('Oibs_Decorators', 
                    'Oibs/Decorators/',
                    'decorator');

        // Group name (must be unique).
        $groupname = new Zend_Form_Element_Text('groupname');
        $groupname
            ->setLabel($translate->_('groups-new_group_name'))
            ->setRequired(true)
            ->setFilters(array('StringTrim'))
            ->setValidators(array(
                array('NotEmpty', true, array('messages' => array('isEmpty' => 'field-empty'))),
                new Oibs_Validators_GroupExists('groupname')
            ))
            ->setDecorators(array('SettingsTextDecorator'));

        // Description.
        $groupdesc = new Zend_Form_Element_Text('groupdesc');
        $groupdesc
            ->setLabel($translate->_('groups-new_group_description'))
            ->setRequired(true)
            ->setFilters(array('StringTrim'))
            ->setDecorators(array('SettingsTextDecorator'));

        // Body text.
        $groupbody = new Zend_Form_Element_Text('groupbody');
        $groupbody
            ->setLabel('Body text')
            ->setFilters(array('StringTrim'))
            ->setDecorators(array('SettingsTextDecorator'));

        // Submit button.
        $submit = new Zend_Form_Element_Submit('submit');
        $submit
            ->setIgnore(true)
            ->setLabel($translate->_('groups-btn_create'));

        $this->addElements(array(
            $groupname,
            $groupdesc,
            $groupbody,
            $submit));
    }
}