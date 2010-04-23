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

        $this->addElement('text', 'groupname', array(
            'label'      => $translate->_('groups-new_group_name') . ":",
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('NotEmpty', true, array('messages' => array('isEmpty' => 'field-empty'))),
                new Oibs_Validators_GroupExists('groupname')
            ),
            'decorators' => array('SettingsTextDecorator')
        ));
        
        $this->addElement('text', 'groupdesc', array(
            'label'      => $translate->_('groups-new_group_description') . ":",
            'required'   => true,
            'filters'    => array('StringTrim'),
            'decorators' => array('SettingsTextDecorator')
        ));
        
        $this->addElement('submit', 'submit', array(
            'ignore' => true,
            'label'  => $translate->_('groups-btn_create')
        ));
        
        $this->addElement('hash', 'csrf', array(
            'ignore' => true
        ));
    }
}