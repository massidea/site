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
        $this->addElementPrefixPath('Oibs_Decorators', 
                    'Oibs/Decorators/',
                    'decorator');

        $this->addElement('text', 'campaign_name', array(
            'label'      => 'Campaign name:',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('NotEmpty', true, array('messages' => array('isEmpty' => 'field-empty'))),
                new Oibs_Validators_CampaignExists('campaign_name')
            ),
            'decorators' => array('SettingsTextDecorator')
        ));
        
        $this->addElement('text', 'campaign_ingress', array(
            'label'      => 'Ingress:',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'decorators' => array('SettingsTextDecorator')
        ));

        $this->addElement('text', 'campaign_desc', array(
            'label'      => 'Description:',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'decorators' => array('SettingsTextDecorator')
        ));

        $this->addElement('text', 'campaign_start', array(
            'id'             => 'campaign_start',
            'label'          => 'Start date:',
            'required'       => true,
            'invalidMessage' => 'Invalid date specified.',
            'formatLength'   => 'long',
            'validators'     => array(
                new Zend_Validate_Date('campaign_start')
            )
        ));

        $this->addElement('text', 'campaign_end', array(
            'id'             => 'campaign_end',
            'label'          => 'End date:',
            'required'       => false,
            'invalidMessage' => 'Invalid date specified.',
            'formatLength'   => 'long',
            'validators'     => array(
                new Zend_Validate_Date('campaign_end')
            )
        ));
        
        $this->addElement('submit', 'submit', array(
            'ignore' => true,
            'label'  => 'Create campaign'
        ));
        
        $this->addElement('hash', 'csrf', array(
            'ignore' => true
        ));
    }
}