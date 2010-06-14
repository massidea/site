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

        $campaignname = new Zend_Form_Element_Text('campaign_name');
        $campaignname
            ->setLabel('Name')
            ->setRequired(true)
            ->setFilters(array('StringTrim'))
            ->setValidators(array(
                array('NotEmpty', true, array('messages' => array('isEmpty' => 'field-empty'))),
                new Oibs_Validators_CampaignExists('campaign_name')
            ))
            ->setDescription(
                '<div id="progressbar_campaign_name" class="limit ok"></div>');

        $campaignname_clear = new Oibs_Form_Element_Note('campaignname_clear');
        $campaignname_clear->setValue($clear);

        $campaigningress = new Zend_Form_Element_TextArea('campaign_ingress');
        $campaigningress
            ->setLabel('Ingress')
            ->setRequired(true)
            ->setFilters(array('StringTrim'))
            ->setDescription(
                '<div id="progressbar_campaign_ingress" class="limit ok"></div>');

        $campaigningress_clear = new Oibs_Form_Element_Note('campaigningress_clear');
        $campaigningress_clear->setValue($clear);

        $campaigndesc = new Zend_Form_Element_TextArea('campaign_desc');
        $campaigndesc
            ->setLabel('Description')
            ->setRequired(true)
            ->setFilters(array('StringTrim'))
            ->setDescription(
                '<div id="progressbar_campaign_desc" class="limit ok"></div>');
        
        $campaigndesc_clear = new Oibs_Form_Element_Note('campaigndesc_clear');
        $campaigndesc_clear->setValue($clear);

        $campaignstart = new Zend_Form_Element_Text('campaign_start');
        $campaignstart
            ->setAttrib('id', 'campaign_start')
            ->setAttrib('name', 'campaign_start')
            ->setLabel('Start date')
            ->setRequired(true)
            ->setAttrib('invalidMessage', 'Invalid date specified')
            ->setAttrib('formalLength', 'long')
            ->setValidators(array(new Zend_Validate_Date('campaign_start')));
        
        $campaignstart_clear = new Oibs_Form_Element_Note('campaignstart_clear');
        $campaignstart_clear->setValue($clear);
        
        $campaignend = new Zend_Form_Element_Text('campaign_end');
        $campaignend
            ->setAttrib('id', 'campaign_end')
            ->setLabel('End date')
            ->setRequired(true)
            ->setAttrib('invalidMessage', 'Invalid date specified')
            ->setAttrib('formalLength', 'long')
            ->setValidators(array(new Zend_Validate_Date('campaign_end')));
        
        $campaignend_clear = new Oibs_Form_Element_Note('campaignend_clear');
        $campaignend_clear->setValue($clear);

        $save = new Zend_Form_Element_Submit('save');
        $save->setLabel('Create campaign')
             ->setAttrib('id', 'save-campaign')
             ->setAttrib('class', 'submit-button');

        $cancel = new Zend_Form_Element_Submit('cancel');
        $cancel->setLabel('Cancel')
              ->setAttrib('id', 'cancel')
              ->setAttrib('class', 'submit-button');

        $this->addElements(array(
            $campaignname,
            $campaignname_clear,
            $campaigningress,
            $campaigingress_clear,
            $campaigndesc,
            $campaigndesc_clear,
            $campaignstart,
            $campaignstart_clear,
            $campaignend,
            $campaignend_clear,
            $save,
            $cancel
        ));

        $campaignname->setDecorators(array('FieldDecorator'));
        $campaignname_clear->setDecorators(array('ViewHelper'));
        $campaigningress->setDecorators(array('FieldDecorator'));
        $campaigningress_clear->setDecorators(array('ViewHelper'));
        $campaigndesc->setDecorators(array('FieldDecorator'));
        $campaigndesc_clear->setDecorators(array('ViewHelper'));
        $campaignstart->setDecorators(array('FieldDecorator'));
        $campaignstart_clear->setDecorators(array('ViewHelper'));
        $campaignend->setDecorators(array('FieldDecorator'));
        $campaignend_clear->setDecorators(array('ViewHelper'));

        $save->setDecorators(array(
            'ViewHelper',
            array('HtmlTag', array('tag' => 'div', 'openOnly' => true, 'id' => 'save_changes')),
        ));
        $cancel->setDecorators(array(
            'ViewHelper',
            array('HtmlTag', array('tag' => 'div', 'closeOnly' => true)),
        ));
    }
}