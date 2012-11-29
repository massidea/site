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

class Default_Form_AddGroupForm extends Twitter_Bootstrap_Form_Horizontal
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setName('create-group-title')
            ->setAttrib('id', 'create-group-form')
            ->addElementPrefixPath('Oibs_Validators', 'OIBS/Validators/', 'validate', 'decorate')
            ->addElementPrefixPath('Oibs_Decorators', 'Oibs/Decorators/', 'decorator');

        $this->addElement('text', 'create-group-form-groupname', array(
            'label'       => 'create-group-form-groupname',
            'required'    => true,
            'filters'     => array('StringTrim'),
            'validators'  => array(
                array('NotEmpty', true, array('messages' => array('isEmpty' => 'error-field-empty'))),
                  array('StringLength',
                    false,
                    array(
                        1,
                        140,
                        'encoding' => 'UTF-8',
                        'messages' =>
                        array('stringLengthTooLong' => 'Name too long.'))))
        ));

        $this->addElement('select', 'create-group-form-category', array(
            'label'        => 'create-group-form-category',
            'required'     => true,
            'multiOptions' => $this->getCategories(),
            'validators'   => array(
                array('NotEmpty', true, array('messages' => array('isEmpty' => 'error-selection-empty')))
            ),
        ));

        $this->addElement('text', 'create-group-form-description', array(
                'label'       => 'create-group-form-description',
                'cols'        => '45',
                'rows'        => '20',
                'required'    => true,
                'validators'  => array(
                    array(
                        'StringLength',
                        false,
                        array(
                            1,
                            4000,
                            'encoding' => 'UTF-8',
                            'messages' =>
                            array('stringLengthTooLong' => 'Body text too long.'))),
                ))
        );

        $this->addElement('text', 'create-group-form-keyword', array(
            'label'       => 'create-group-form-keyword',
        ));


        $this->addElement('text', 'create-group-form-keyword', array(
            'label'       => 'create-group-form-keyword',
        ));

        $this->addElement('radio', 'create-group-form-privacy', array(
            'label'      => 'create-group-form-privacy',
            'multiOptions' => $this->getPrivacies(),
        ));

        $this->addElement('radio', 'create-group-form-image', array(
            'label' => 'create-group-form-image',
            'decorators' => array(array('CreateGroupImageDecorator'))

        ));



        parent::init();
    }


    protected function getCategories()
    {
        return array(
            ''                 => '',
            'Category1'   => 'Category1',
            'Category2'    => 'Category2',
            'Category3' => 'Category3',
        );
    }

    protected function getPrivacies()
    {
        return array(
            'Public'      => 'create-group-form-public',
            'Private'   => 'create-group-form-private',
        );
    }

}
