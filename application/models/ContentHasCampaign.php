<?php
/**
 *  ContentHasCampaign -> ContentHasCampaign database model for table cnt_has_cmp
 *
 * 	Copyright (c) <2009>, Pekka Piispanen
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
 *  ContentHasCampaign - class
 *
 *  @package 	models
 *  @author 	Pekka Piispanen
 *  @copyright 	2009 Pekka Piispanen
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class Default_Model_ContentHasCampaign extends Zend_Db_Table_Abstract
{
	// Table name
    protected $_name = 'cnt_has_cmp';
	
	// Table reference map
	protected $_referenceMap    = array(
        'CampaignContent' => array(
            'columns'           => array('id_cnt'),
            'refTableClass'     => 'Default_Model_Content',
            'refColumns'        => array('id_cnt')
        ),
		'CampaignCampaign' => array(
            'columns'           => array('id_cmp'),
            'refTableClass'     => 'Default_Model_Campaigns',
            'refColumns'        => array('id_cmp')
        )
    );
	
	/**
	*	addCampaignToContent
	*
	*	Add specified campaign to specified content.
	*
	*	@param integer $id_cmp
	*	@param integer $id_cnt
	*/
	public function addCampaignToContent($id_cmp = 0, $id_cnt = 0)
	{
		// If campaign id and content id is not 0
		if($id_cmp != 0 && $id_cnt != 0) {
			// Create a new row
			$row = $this->createRow();
			
			// Set id values
			$row->id_cnt = $id_cnt;
			$row->id_cmp = $id_cmp;
			
			// Add row to database
			$row->save();
		} // end if
	} // end of addCampaignToContent
    
    public function getContentCampaigns($id_cnt = 0)
    {
        $select =  $this->_db->select()
                        ->from(array('cnt_has_cmp' => 'cnt_has_cmp'), array('id_cmp'))
                        ->where('id_cnt = ?', $id_cnt)
                        ->join(array('campaigns_cmp' => 'campaigns_cmp'), 'campaigns_cmp.id_cmp = cnt_has_cmp.id_cmp', array('name_cmp'))
        ;
        $result = $this->_db->fetchAll($select);

        return $result;
    } // end of getContentCampaigns
    
    /**
	*	checkIfOtherContentHasCampaign
	*
	*	This function checks, if other content(s) have specified campaign when
    *   deleting content. If no other content uses the specified campaign, the 
    *   entire campaign can be deleted from the system
	*
    *   @param int $id_cmp The campaign to check
	*	@param int $id_cnt The content which is going to be deleted
    *   @return bool
    *   @author Pekka Piispanen
	*/
    public function checkIfOtherContentHasCampaign($id_cmp = 0, $id_cnt = 0)
    {
        $return = false;
        
        $select = $this->select()
                        ->where('id_cmp = ?', $id_cmp)
                        ->where('id_cnt != ?', $id_cnt);
                        
        $result = $this->fetchAll($select)->toArray();
        
        if(count($result) != 0)
        {
            $return = true;
        }
        
        return $return;
    }
    
    /**
	*	removeContentCampaigns
	*
	*	Remove campaigns from specified content
	*
	*	@param integer $id_cnt
    *   @return bool $return
    *   @author Pekka Piispanen
	*/
	public function removeContentCampaigns($id_cnt = 0)
    {
        $return = false;
        
        $where = $this->getAdapter()->quoteInto('id_cnt = ?', $id_cnt);
        if($this->delete($where))
        {
            $return = true;
        }
        
        return $return;
    } // end of removeContentCampaigns
    
    /**
    *
    *
    */
    public function deleteCampaignFromContent($id_cmp = 0, $id_cnt = 0)
    {
        $return = false;
        
        $where = array();
        
        $where[] = $this->getAdapter()->quoteInto('id_cmp = ?', $id_cmp);
        $where[] = $this->getAdapter()->quoteInto('id_cnt = ?', $id_cnt);

        if($this->delete($where))
        {
            $return = true;
        }
        
        return $return;
    }
    
    /**
    *
    *
    */
    public function checkExistingCampaigns($contentId = -1, array $campaigns = array())
    {
        $result = null;
    
        if($contentId != -1 && !empty($campaigns)) {
            // Go through all existing campaigns
            $existingCmps = $this->getContentCampaigns($contentId);
            
            foreach($existingCmps as $id => $campaign) {
                // If some of the existing campaigns aren't found in sent campaigns,
                // that campaign is deleted the from content and maybe even from the
                // database
                if(!in_array($campaign['name_cmp'], $campaigns)) {
                    // Removing campaign from content
                    $this->deleteCampaignFromContent($existingCmp['id_cmp'], $contentId);
                    
                    // If other content(s) doesn't have this campaign, the whole
                    // campaign is going to be removed from the database
                    if(!$this->checkIfOtherContentHasCampaign($existingCmp['id_cmp'], $contentId)) {
                        $modelCmps = new Default_Model_Campaigns();
                        $modelCmps->removeCampaign($existingCmp['id_cmp']);
                    }
                    
                    // Remove campaign from existingCmps array
                    unset($existingCmps[$id]);
                }
            }
            
            $result = $existingCmps;
        }
        
        return $result;
    }
} // end of class