<?php
/**
 *  Campaigns -> Campaigns database model for Campaigns table.
 *
 *  Copyright (c) <2009>, Pekka Piispanen
 *
 *  This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License 
 *  as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied  
 *  warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for  
 *  more details.
 * 
 *  You should have received a copy of the GNU General Public License along with this program; if not, write to the Free 
 *  Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * License text found in /license/
 */

/**
 *  Campaigns - class
 *
 *  @package    models
 *  @author     Pekka Piispanen
 *  @license    GPL v2
 *  @version    1.0
 */ 
class Default_Model_Campaigns extends Zend_Db_Table_Abstract
{
    // Table name
    protected $_name = 'campaigns_cmp';

    // Table primary key
    protected $_primary = 'id_cmp';

    // Table dependet tables
    protected $_dependentTables = array('Default_Model_ContentHasCampaign');

    /**
    *   campaignExists
    *
    *   Check if campaign exists in database.
    *
    *   @param string $campaign name of campaign to be checked
    *   @return boolean
    */
    public function campaignExists($campaign)
    {
        // Select campaign with given name
        $select = $this->select()->where('name_cmp = ?', $campaign);
        
        // Find all matching campaigns
        $result = $this->fetchAll($select)->toArray();
        
        return empty($result);
    } // end of campaignExists

    /**
    *   getCampaign
    *
    *   Get campaign info and return an array containing campaign data
    *
    *   @param string $campaign name of campaign to be fetched
    *   @return array
    */
    public function getCampaign($campaign)
    {
        // Select campaign by name
        $select = $this->select()->where('name_cmp = ?', $campaign)
                                 ->limit(1);
                                 
        $row = $this->fetchAll($select)->current();
        
        return $row;
    } // end of getCampaign

    /**
    *   createCampaign
    *
    *   Adds a given campaign to database and returns the created row
    *
    *   @param string $campaign name of campaign that will be created
    *   @return array
    */
    public function createCampaign($campaign)
    {
        // Create new empty row
        $row = $this->createRow();
        
        // Set campaign data
        $row->name_cmp = $campaign;
        
        $row->created_cmp = new Zend_Db_Expr('NOW()');
        $row->modified_cmp = new Zend_Db_Expr('NOW()');
        
        // Save data to database
        $row->save();
        
        return $row;
    } // end of createCampaign

    /**
    *   getContentByCampaignId
    *
    *   Gets content by campaign id value.
    *
    *   @param integer $id
    *   @return array
    */
    public function getContentByCampaignId($id = 0)
    {
        $data = array();
        
        if ($id != 0)
        {
            // Find content row by id
            $rowset = $this->find((int)$id)->current();
        
            $data = $rowset->findManyToManyRowset('Default_Model_Content', 'Default_Model_ContentHasCampaign')->toArray();
            /*
            echo '<pre>';
            print_r($data);
            echo '</pre>';
            */
        } // end if
        
        return $data;
    } // end of getContentByCampaignId

    /**
    *   getAll
    *
    *   Gets all campaigns
    *
    *   @return array
    */
    public function getAll()
    {
        return $this->fetchAll();
    } // end of getAll
    
    /** 
    *   removeCampaign
    *   Removes the campaign from the database
    *   The campaign is removed when it is not used with any content
    *   
    *   @param int id_cmp
    *   @author Pekka Piispanen
    */
    public function removeCampaign($id_cmp = 0)
    {
        $where = $this->getAdapter()->quoteInto('id_cmp = ?', $id_cmp);
        $this->delete($where);
    } // end of removeCampaign
        
    /**
    * addCampaignsToContent
    *
    * @param int contentId
    * @param array campaignArray
    * @param array campaignExisting
    */
    public function addCampaignsToContent($contentId = -1, array $campaignArray = array(), $campaignExisting = null)
    {
        $result = false;
    
        if(!empty($campaignArray) && $contentId != -1) {
            $chcModel = new Default_Model_ContentHasCampaign();
            
            foreach($campaignArray as $id => $campaign) {
                $cmpFound = false;
                
                // Check if campaign is already added for this content
                if($campaignExisting != null) {
                    foreach($campaignExisting as $existingCmp) {
                        if($campaign == $existingCmp['name_cmp']) {
                            $cmpFound = true;
                        }
                    }
                }
                
                // If campaign is not already associated with current content
                if(!$cmpFound) {
                    // Check if given keyword does not exists in database
                    if($this->campaignExists($campaign)) {
                        // Create new keyword
                        $campaign = $this->createCampaign($campaign);
                    } else {
                        // Get keyword
                        $campaign = $this->getCampaign($campaign);
                    } // end else
                    
                    // Add keywords to content
                    $chcModel->addCampaignToContent($campaign->id_cmp, $contentId);
                }
            } // end foreach 
        
            $result = true;
        }
        
        return $result;
    }
} // end of class