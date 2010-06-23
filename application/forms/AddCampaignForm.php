<?php
/**
 *  AddCampaignForm -> Form for adding campaigns.
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
 *  @package    Forms
 *  @author     Mikko Aatola
 *  @copyright  2009 Mikko Aatola
 *  @license    GPL v2
 *  @version    1.0
 */
class Default_Form_AddCampaignForm extends Zend_Form
{
    public function __construct($parent = null, $options = null)
    {
        parent::__construct($options);
    
        $parentId = 0;
        if ($parent != null) {
            $parentId = $parent;
        }

        $translate = Zend_Registry::get('Zend_Translate'); 
        
        $this->setName('Create a campaign');
        $this->addElementPrefixPath('Oibs_Form_Decorator',
                    'Oibs/Form/Decorator/',
                    'decorator');

        // Clear div
        $clear = '<div class="clear"></div>';

        // Campaign name
        $campaignname = new Zend_Form_Element_Text('campaign_name');
        $campaignname
            ->setLabel('Name')
            ->setRequired(true)
            ->setFilters(array('StringTrim'))
            ->setValidators(array(
                array('NotEmpty', true, array('messages' => array('isEmpty' => 'field-empty'))),
                new Oibs_Validators_CampaignExists('campaign_name'),
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
                '<div id="progressbar_campaign_name" class="limit ok"></div>')
            ->setDecorators(array('FieldDecorator'));

        $campaignname_clear = new Oibs_Form_Element_Note('campaignname_clear');
        $campaignname_clear
            ->setValue($clear)
            ->setDecorators(array('ViewHelper'));

        // Ingress
        $campaigningress = new Zend_Form_Element_Textarea('campaign_ingress');
        $campaigningress
            ->setAttrib('cols', '45')
            ->setAttrib('rows', '6')
            ->setLabel('Ingress')
            ->setRequired(true)
            ->setFilters(array('StringTrim'))
            ->setValidators(array(
                array('NotEmpty', true, array('messages' => array('isEmpty' => "Ingress can't be empty."))),
                array(
                    'StringLength',
                    false,
                    array(
                        1,
                        320,
                        'messages' =>
                            array('stringLengthTooLong' => 'Ingress too long.')))
            ))
            ->setDescription(
                '<div id="progressbar_campaign_ingress" class="limit ok"></div>')
            ->setDecorators(array('FieldDecorator'));

        $campaigningress_clear = new Oibs_Form_Element_Note('campaigningress_clear');
        $campaigningress_clear
            ->setValue($clear)
            ->setDecorators(array('ViewHelper'));

        // Description
        $campaigndesc = new Zend_Form_Element_Textarea('campaign_desc');
        $campaigndesc
            ->setAttrib('cols', '45')
            ->setAttrib('rows', '20')
            ->setLabel('Body text')
            ->setRequired(true)
            ->setFilters(array('StringTrim'))
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
                '<div id="progressbar_campaign_desc" class="limit ok"></div>')
            ->setDecorators(array('FieldDecorator'));
        
        $campaigndesc_clear = new Oibs_Form_Element_Note('campaigndesc_clear');
        $campaigndesc_clear
            ->setValue($clear)
            ->setDecorators(array('ViewHelper'));

        // Start date
        $campaignstart = new Zend_Form_Element_Text('campaign_start');
        $campaignstart
            ->setAttrib('id', 'campaign_start')
            ->setAttrib('name', 'campaign_start')
            ->setLabel('Start date')
            ->setRequired(true)
            ->setAttrib('invalidMessage', 'Invalid date specified')
            ->setAttrib('formalLength', 'long')
            ->setValidators(array(new Zend_Validate_Date('campaign_start')))
            ->setDecorators(array('FieldDecorator'));
        
        $campaignstart_clear = new Oibs_Form_Element_Note('campaignstart_clear');
        $campaignstart_clear
            ->setValue($clear)
            ->setDecorators(array('ViewHelper'));

        // End date
        $campaignend = new Zend_Form_Element_Text('campaign_end');
        $campaignend
            ->setAttrib('id', 'campaign_end')
            ->setLabel('End date')
            ->setRequired(true)
            ->setAttrib('invalidMessage', 'Invalid date specified')
            ->setAttrib('formalLength', 'long')
            ->setValidators(array(new Zend_Validate_Date('campaign_end')))
            ->setDecorators(array('FieldDecorator'));
        
        $campaignend_clear = new Oibs_Form_Element_Note('campaignend_clear');
        $campaignend_clear
            ->setValue($clear)
            ->setDecorators(array('ViewHelper'));

        $save = new Zend_Form_Element_Submit('save');
        $save->setLabel('Create')
             ->setAttrib('id', 'publish')
             ->setAttrib('class', 'submit-button')
             ->setAttrib('style', 'float: none;');

        $cancel = new Zend_Form_Element_Submit('cancel');
        $cancel->setLabel('Cancel')
              ->setAttrib('id', 'preview')
              ->setAttrib('class', 'submit-button')
              ->setAttrib('style', 'float: none;');

        $this->addElements(array(
            $campaignname,
            $campaignname_clear,
            $campaigningress,
            $campaigningress_clear,
            $campaigndesc,
            $campaigndesc_clear,
//            $campaignstart,
//            $campaignstart_clear,
//            $campaignend,
//            $campaignend_clear,
            $save,
            //$cancel
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
        $cancel->setDecorators(array(
            'ViewHelper',
            array('HtmlTag', array('tag' => 'div', 'closeOnly' => true)),
        ));
    }
}