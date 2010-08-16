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
                array('StringLength',
                    false,
                    array(
                        1,
                        140,
                        'messages' =>
                            array('stringLengthTooLong' => 'Name too long.'))),
                array(new Oibs_Validators_GroupExists($options), false),
            ))
            ->setDescription(
                '<div id="progressbar_groupname" class="limit ok"></div>')
            ->setDecorators(array('FieldDecorator'));

        $groupname_clear = new Oibs_Form_Element_Note('groupname_clear');
        $groupname_clear
            ->setValue($clear)
            ->setDecorators(array('ViewHelper'));

        // Group type
        $groupTypes_model = new Default_Model_GroupTypes();
        $groupTypes = $groupTypes_model->getAllTypes();
        $grouptype = new Zend_Form_Element_Select('grouptype');
        $grouptype->setLabel('Type')
                  ->setAttrib('id', 'grouptype');
        foreach ($groupTypes as $type) {
            $grouptype->addMultiOption($type['id_gtp'], $type['name_gtp']);
        }
        $grouptype->setDecorators(array('FieldDecorator'));

        $grouptype_clear = new Oibs_Form_Element_Note('grouptype_clear');
        $grouptype_clear
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
                array(
                    'StringLength',
                    false,
                    array(
                        1,
                        4000,
                        'messages' =>
                            array('stringLengthTooLong' => 'Body text too long.')))
            ))
            ->setDecorators(array('FieldDecorator'));

        $groupbody_clear = new Oibs_Form_Element_Note('groupbody_clear');
        $groupbody_clear
            ->setValue($clear)
            ->setDecorators(array('ViewHelper'));

        $weblinks_websites = new Oibs_Form_Element_Note('weblinks_websites');
        $weblinks_websites->setValue('<div class="input-column-website1"><label><strong>Links to websites:</strong></label></div>');
        $weblinks_name = new Oibs_Form_Element_Note('weblinks_name');
        $weblinks_name->setValue('<div class="input-column-website2">Name</div>');
        $weblinks_url = new Oibs_Form_Element_Note('weblinks_url');
        $weblinks_url->setValue('<div class="input-column-website3">Url</div><div class="clear"></div>');

        $nameTooLongText = 'Name too long (max 45)';
        $urlTooLongText = 'URL too long (max 150)';

        $weblinks_name_site1 = new Zend_Form_Element_Text('weblinks_name_site1');
        $weblinks_name_site1->setLabel('Web site 1')
                            ->setAttrib('id', 'website1-name')
                            ->addValidators(array(
                                array('StringLength',
                                    false,
                                    array(0, 45, 'messages' => array('stringLengthTooLong'  => $nameTooLongText))
                                ),
                            ));
        $weblinks_url_site1 = new Zend_Form_Element_Text('weblinks_url_site1');
        $weblinks_url_site1->setAttrib('id', 'website1-url')
                           ->addValidators(array(
                               new Oibs_Validators_UrlValidator(),
                               array('StringLength',
                                   false,
                                   array(0, 150, 'messages' => array('stringLengthTooLong'  => $urlTooLongText))
                               )
                           ));

        $weblinks_name_site2 = new Zend_Form_Element_Text('weblinks_name_site2');
        $weblinks_name_site2->setLabel('Web site 2')
                            ->setAttrib('id', 'website2-name')
                            ->addValidators(array(
                                array('StringLength',
                                    false,
                                    array(0, 45, 'messages' => array('stringLengthTooLong'  => $nameTooLongText))
                                ),
                            ));
        $weblinks_url_site2 = new Zend_Form_Element_Text('weblinks_url_site2');
        $weblinks_url_site2->setAttrib('id', 'website2-url')
                           ->addValidators(array(
                               new Oibs_Validators_UrlValidator(),
                               array('StringLength',
                                   false,
                                   array(0, 150, 'messages' => array('stringLengthTooLong'  => $urlTooLongText))
                               )
                           ));

        $weblinks_name_site3 = new Zend_Form_Element_Text('weblinks_name_site3');
        $weblinks_name_site3->setLabel('Web site 3')
                            ->setAttrib('id', 'website3-name')
                            ->setAttrib('id', 'website2-name')
                            ->addValidators(array(
                                array('StringLength',
                                    false,
                                    array(0, 45, 'messages' => array('stringLengthTooLong'  => $nameTooLongText))
                                ),
                            ));
        $weblinks_url_site3 = new Zend_Form_Element_Text('weblinks_url_site3');
        $weblinks_url_site3->setAttrib('id', 'website3-url')
                           ->addValidators(array(
                               new Oibs_Validators_UrlValidator(),
                               array('StringLength',
                                   false,
                                   array(0, 150, 'messages' => array('stringLengthTooLong'  => $urlTooLongText))
                               )
                           ));

        $weblinks_name_site4 = new Zend_Form_Element_Text('weblinks_name_site4');
        $weblinks_name_site4->setLabel('Web site 4')
                            ->setAttrib('id', 'website4-name')
                            ->setAttrib('id', 'website2-name')
                            ->addValidators(array(
                                array('StringLength',
                                    false,
                                    array(0, 45, 'messages' => array('stringLengthTooLong'  => $nameTooLongText))
                                ),
                            ));
        $weblinks_url_site4 = new Zend_Form_Element_Text('weblinks_url_site4');
        $weblinks_url_site4->setAttrib('id', 'website4-url')
                           ->addValidators(array(
                               new Oibs_Validators_UrlValidator(),
                               array('StringLength',
                                   false,
                                   array(0, 150, 'messages' => array('stringLengthTooLong'  => $urlTooLongText))
                               )
                           ));

        $weblinks_name_site5 = new Zend_Form_Element_Text('weblinks_name_site5');
        $weblinks_name_site5->setLabel('Web site 5')
                            ->setAttrib('id', 'website5-name')
                            ->setAttrib('id', 'website2-name')
                            ->addValidators(array(
                                array('StringLength',
                                    false,
                                    array(0, 45, 'messages' => array('stringLengthTooLong'  => $nameTooLongText))
                                ),
                            ));
        $weblinks_url_site5 = new Zend_Form_Element_Text('weblinks_url_site5');
        $weblinks_url_site5->setAttrib('id', 'website5-url')
                           ->addValidators(array(
                               new Oibs_Validators_UrlValidator(),
                               array('StringLength',
                                   false,
                                   array(0, 150, 'messages' => array('stringLengthTooLong'  => $urlTooLongText))
                               )
                           ));

        $weblinks_websites->setDecorators(array('ViewHelper'));
        $weblinks_name->setDecorators(array('ViewHelper'));
        $weblinks_url->setDecorators(array('ViewHelper'));
        $weblinks_name_site1->setDecorators(array('InputWebsiteNameDecorator'));
        $weblinks_url_site1->setDecorators(array('InputWebsiteUrlDecorator'));
        $weblinks_name_site2->setDecorators(array('InputWebsiteNameDecorator'));
        $weblinks_url_site2->setDecorators(array('InputWebsiteUrlDecorator'));
        $weblinks_name_site3->setDecorators(array('InputWebsiteNameDecorator'));
        $weblinks_url_site3->setDecorators(array('InputWebsiteUrlDecorator'));
        $weblinks_name_site4->setDecorators(array('InputWebsiteNameDecorator'));
        $weblinks_url_site4->setDecorators(array('InputWebsiteUrlDecorator'));
        $weblinks_name_site5->setDecorators(array('InputWebsiteNameDecorator'));
        $weblinks_url_site5->setDecorators(array('InputWebsiteUrlDecorator'));

        $save = new Zend_Form_Element_Submit('save');
        if (is_array($options) && $options['mode'] == 'edit')
            $save->setLabel('Save');
        else
            $save->setLabel('Create');
        $save->setAttrib('id', 'publish')
            ->setAttrib('class', 'submit-button')
            ->setAttrib('style', 'float: none;');

        $this->addElements(array(
            $groupname,
            $groupname_clear,
            $grouptype,
            $grouptype_clear,
            $groupdesc,
            $groupdesc_clear,
            $groupbody,
            $groupbody_clear,
            $weblinks_websites,
            $weblinks_name,
            $weblinks_url,
            $weblinks_name_site1,
            $weblinks_url_site1,
            $weblinks_name_site2,
            $weblinks_url_site2,
            $weblinks_name_site3,
            $weblinks_url_site3,
            $weblinks_name_site4,
            $weblinks_url_site4,
            $weblinks_name_site5,
            $weblinks_url_site5,
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