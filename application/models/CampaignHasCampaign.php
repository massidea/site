<?php
/**
 *  CampaignHasCampaign -> CampaignHasCampaign database model for campaign has campaign link table.
 *
 * 	Copyright (c) <2010>, Mikko Korpinen
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
 *  CampaignHasCampaign - class
 *
 *  @package    models
 *  @author     Mikko Korpinen
 *  @copyright 	2010 Mikko Korpinen
 *  @license    GPL v2
 *  @version    1.0
 */ 
class Default_Model_CampaignHasCampaign extends Zend_Db_Table_Abstract
{
	// Table name
    protected $_name = 'cmp_has_cmp';
	
	// Table reference map
	protected $_referenceMap    = array(
        'ParentContent' => array(
            'columns'           => array('id_parent_cmp'),
            'refTableClass'     => 'Default_Model_Campaigns',
            'refColumns'        => array('id_cmp')
        ),  
		 'ChildContent' => array(
            'columns'           => array('id_child_cmp'),
            'refTableClass'     => 'Default_Model_Campaigns',
            'refColumns'        => array('id_cmp')
        ),
    );
	
	/**
     * addCampaignToCampaign - Add campaign to campaign
     *
     * @author Mikko Korpinen
     * @param int $id_parent_cmp
     * @param int $id_child_cmp
     */
	public function addCampaignToCampaign($id_parent_cmp = 0, $id_child_cmp = 0)
	{
		// If id values not 0
		if($id_parent_cmp != 0 && $id_child_cmp != 0)
		{
			// Create a new row
			$row = $this->createRow();
			
			// Set id values
			$row->id_parent_cmp = $id_parent_cmp;
			$row->id_child_cmp = $id_child_cmp;
			
			// Add row to database
			$row->save();
		} // end if
	}

    /**
     * getCampaignCampaigns - Get all campaign campaigns
     *
     * @param int $id_cmp
     */
    public function getCampaignCampaigns($id_cmp) {

        $result = array();  // container for final results array

        $campaignSelectParents = $this->_db->select()
                                   ->from(array('chc' => 'cmp_has_cmp'),
                                          array('id_parent_cmp', 'id_child_cmp'))
                                   ->joinLeft(array('cc' => 'campaigns_cmp'),
                                          'cc.id_cmp = chc.id_child_cmp',
                                          array('id_cmp', 'id_grp_cmp', 'name_cmp', 'ingress_cmp', 'description_cmp'))
                                   ->joinLeft(array('ugg' => 'usr_groups_grp'),
                                          'ugg.id_grp = cc.id_grp_cmp',
                                          array('id_grp', 'group_name_grp'))
                                   ->where('chc.id_parent_cmp = ?', $id_cmp)
                                   ->group('cc.id_cmp');

        $campaignSelectChilds = $this->_db->select()
                                   ->from(array('chc' => 'cmp_has_cmp'),
                                          array('id_parent_cmp', 'id_child_cmp'))
                                   ->joinLeft(array('cc' => 'campaigns_cmp'),
                                          'cc.id_cmp = chc.id_parent_cmp',
                                          array('id_cmp', 'id_grp_cmp', 'name_cmp', 'ingress_cmp', 'description_cmp'))
                                   ->joinLeft(array('ugg' => 'usr_groups_grp'),
                                          'ugg.id_grp = cc.id_grp_cmp',
                                          array('id_grp', 'group_name_grp'))
                                   ->where('chc.id_child_cmp = ?', $id_cmp)
                                   ->group('cc.id_cmp');

        $result['parents'] = $this->_db->fetchAll($campaignSelectParents);
        $result['childs'] = $this->_db->fetchAll($campaignSelectChilds);

        return $result;
    }

    /**
     * checkIfCampaignHasCampaign - Check if camppaign has specified campaign
     *
     * @author Mikko Korpinen
     * @param int $id_parent_cmp
     * @param int $id_child_cmp
     * @return boolean
     */
    public function checkIfCampaignHasCampaign($id_parent_cmp = -1, $id_child_cmp = -1) {
        if($id_parent_cmp != -1 && $id_child_cmp != -1)
        {
            $select = $this->select()
                           ->from($this, array('*'))
                           ->where('`id_parent_cmp` = ?', $id_parent_cmp)
                           ->where('`id_child_cmp` = ?', $id_child_cmp);

            $result = $this->fetchAll($select)->toArray();

            if(count($result) != 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * removeCampaignFromCampaigns - Remove campaing from all campaigns where it has been linked
     *
     * @author Mikko Korpinen
     * @param int $id_cmp
     * @return boolean
     */
    public function removeCampaignFromCampaigns($id_cmp = 0)
    {
        $parent = $this->getAdapter()->quoteInto('id_parent_cmp = ?', (int)$id_cmp);
        $child = $this->getAdapter()->quoteInto('id_child_cmp = ?', (int)$id_cmp);
        $where = "$parent OR $child";
        if ($this->delete($where)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * removeCampaignFromCampaign - Remove campaing from campaing
     *
     * @author Mikko Korpinen
     * @param int $id_parent_cmp
     * @param int $id_child_cmp
     * @return boolean
     */
    public function removeCampaignFromCampaign($id_parent_cmp = 0, $id_child_cmp = 0)
    {
        $parent = $this->getAdapter()->quoteInto('id_parent_cmp = ?', (int)$id_parent_cmp);
        $child = $this->getAdapter()->quoteInto('id_child_cmp = ?', (int)$id_child_cmp);
        $where = "$parent AND $child";
        if ($this->delete($where)) {
            return true;
        } else {
            return false;
        }
    }

    /*
    public function getContentFamilyTree($id = -1) 
    {
        $return = array();
    
        $selectParents = $this->_db->select()
                            ->from(array('cnt_has_cnt' => 'cnt_has_cnt'),
                                   array('id_parent_cnt'))
                            ->joinLeft(array('cnt' => 'contents_cnt'),
                                       'cnt.id_cnt = cnt_has_cnt.id_parent_cnt', 
                                       array())
                            ->where('id_child_cnt = ?', $id)
                            ->where('cnt.published_cnt = 1')
        ;
        $parents = $this->_db->fetchAll($selectParents);
        
        $i = 0;
        foreach ($parents as $parent) {
            $return['parents'][$i] = $parent['id_parent_cnt'];
            
            $i++;
        }

        $selectChildren = $this->_db->select()
                            ->from(array('cnt_has_cnt' => 'cnt_has_cnt'),
                                   array('id_child_cnt'))
                            ->joinLeft(array('cnt' => 'contents_cnt'),
                                       'cnt.id_cnt = cnt_has_cnt.id_child_cnt', 
                                       array())
                            ->where('id_parent_cnt = ?', $id)
                            ->where('cnt.published_cnt = 1')
        ;
        $children = $this->_db->fetchAll($selectChildren);
        
        $i = 0;
        foreach ($children as $child) {
            $return['children'][$i] = $child['id_child_cnt'];
            $i++;
        }

        return $return;
    }
    */
} // end of class
?>