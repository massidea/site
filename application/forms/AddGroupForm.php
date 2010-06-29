<?php
/**
 *  AddGroupForm -> Form for adding groups.
 *
 * 	Copyright (c) <2010>, Mikko Aatola
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
 *  @author 	Mikko Aatola
 *  @copyright 	2010 Mikko Aatola
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
        $this->addElementPrefixPath('Oibs_Form_Decorator',
                    'Oibs/Form/Decorator/',
                    'decorator');

        $clear = '<div class="clear"></div>';

        // Group name (must be unique).
        $groupname = new Zend_Form_Element_Text('groupname');
        $groupname
            //->setLabel($translate->_('groups-new_group_name'))
            ->setLabel('Name')
            ->setRequired(true)
            ->setFilters(array('StringTrim'))
            ->setValidators(array(
                array('NotEmpty', true, array('messages' => array('isEmpty' => 'field-empty'))),
                new Oibs_Validators_GroupExists('groupname'),
                array(
                    'StringLength',
                    false,
                    array(
                        1,
                        140,
                        'messages' =>
                            array('stringLengthTooLong' => 'Name too long.')))
            ))
            ->setDescription(
                '<div id="progressbar_groupname" class="limit ok"></div>')
            ->setDecorators(array('FieldDecorator'));

        $groupname_clear = new Oibs_Form_Element_Note('groupname_clear');
        $groupname_clear
            ->setValue($clear)
            ->setDecorators(array('ViewHelper'));

        // Lead paragraph (description)
        $groupdesc = new Zend_Form_Element_Textarea('groupdesc');
        $groupdesc
            ->setAttrib('cols', '45')
            ->setAttrib('rows', '6')
            ->setLabel('Lead paragraph')
            ->setRequired(true)
            ->setValidators(array(
                array('NotEmpty', true, array('messages' => array('isEmpty' => "Description can't be empty."))),
                array(
                    'StringLength',
                    false,
                    array(
                        1,
                        320,
                        'messages' =>
                            array('stringLengthTooLong' => 'Description too long.')))
            ))
            ->setDescription(
                '<div id="progressbar_groupdesc" class="limit ok"></div>')
            ->setDecorators(array('FieldDecorator'));

        $groupdesc_clear = new Oibs_Form_Element_Note('groupdesc_clear');
        $groupdesc_clear
            ->setValue($clear)
            ->setDecorators(array('ViewHelper'));

        // Body text.
        $groupbody = new Zend_Form_Element_Textarea('groupbody');
        $groupbody
            ->setAttrib('cols', '45')
            ->setAttrib('rows', '20')
            ->setLabel('Body')
            ->setValidators(array(
                array('NotEmpty', true, array('messages' => array('isEmpty' => "Body text can't be empty."))),
                array(
                    'StringLength',
                    false,
                    array(
                        1,
                        4000,
                        'messages' =>
                            array('stringLengthTooLong' => 'Body text too long.')))
            ))
            ->setDescription(
                '<div id="progressbar_groupbody" class="limit ok"></div>')
            ->setDecorators(array('FieldDecorator'));

        $groupbody_clear = new Oibs_Form_Element_Note('groupbody_clear');
        $groupbody_clear
            ->setValue($clear)
            ->setDecorators(array('ViewHelper'));

        $save = new Zend_Form_Element_Submit('save');
        if ($options == 'edit')
            $save->setLabel('Save');
        else
            $save->setLabel('Create');
        $save->setAttrib('id', 'publish')
            ->setAttrib('class', 'submit-button')
            ->setAttrib('style', 'float: none;');

        if ($options != 'edit') {
            $this->addElements(array(
                $groupname,
                $groupname_clear
            ));
        }
        $this->addElements(array(
            $groupdesc,
            $groupdesc_clear,
            $groupbody,
            $groupbody_clear,
            $save,
        ));

        $save->setDecorators(array(
            'ViewHelper',
            array('HtmlTag', array(
                'tag' => 'div',
                'openOnly' => true,
                'id' => 'submit',
                'style' => 'clear: both;',
            )),
        ));
    }
}