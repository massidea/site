<?php
/**
 *  Groups -> Groups database model for groups table.
 *
 *  Copyright (c) <2010>, Mikko Aatola
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
 *  License text found in /license/
 */

/**
 *  UserRoles - class
 *
 *  @package    models
 *  @author     Mikko Aatola
 *  @copyright  2010 Mikko Aatola
 *  @license    GPL v2
 *  @version    1.0
 */ 
class Default_Model_Groups extends Zend_Db_Table_Abstract
{
    // Table name
    protected $_name = 'usr_groups_grp';
    
    // Table primary key
    protected $_primary = 'id_grp';

    /**
     * Returns data from the user groups table by group id.
     *
     * @author Mikko Aatola
     * @param id_grp id of the group
     * @return array of data of the group specified by id_grp
     */
    public function getGroupData($id_grp)
    {
        $data = $this->_db->select()
            ->from('usr_groups_grp', array('*'))
            ->where('id_grp = ?', $id_grp);
            
        $result = $this->_db->fetchAll($data);
        
        return $result[0];
    }
    
    /**
     * Returns all data from the user group table.
     *
     * @author Mikko Aatola
     * @return array of data of every group in groups table
     */
    public function getAllGroups()
    {
        $data = $this->_db->select()
            ->from('usr_groups_grp', array('*'));
            
        $result = $this->_db->fetchAll($data);
        
        return $result;
    }

    /**
     * getRecent
     *
     * Gets the specified number of the most recently created groups.
     *
     * @param int $limit
     * @return array
     */
    public function getRecent($limit)
    {
        if (!isset($limit)) $limit = 10;

        $select = $this->select()
                ->order('id_grp DESC')
                ->limit($limit);
        return $this->fetchAll($select)->toArray();
    }
    
    /**
     * Adds a new group to the db.
     *
     * @author Mikko Aatola
     * @param groupname string
     * @return id of the new group
     */
    public function createGroup($name, $description = "", $body = "")
    {
        // Create new empty row.
        $row = $this->createRow();
        
        // Set group data.
        $row->group_name_grp = $name;
        $row->description_grp = $description;
        $row->body_grp = $body;
        $row->created_grp = new Zend_Db_Expr('NOW()');
        $row->modified_grp = new Zend_Db_Expr('NOW()');
        
        // Save data to database
        $row->save();
        
        return $row->id_grp;
    }

    public function editGroup($id, $name, $description, $body)
    {
		$data = array(
            'group_name_grp' => $name,
            'description_grp' => $description,
            'body_grp' => $body,
        );
		$where = $this->getAdapter()->quoteInto('id_grp = ?', $id);
		$this->update($data, $where);
    }

    /**
    *   removeGroup
    *   Removes the group from the database
    *
    *   @param int id_grp
    *   @author Mikko Aatola
    */
    public function removeGroup($id_grp = 0)
    {
        if (!$id_grp) return false;

        // Delete the group's campaigns.
        $data = $this->_db->select()
            ->from('campaigns_cmp', 'id_cmp')
            ->where('id_grp_cmp = ?', $id_grp);
        $campaigns = $this->_db->fetchAll($data);
        $cmpModel = new Default_Model_Campaigns();
        foreach ($campaigns as $cmp)
            $cmpModel->removeCampaign($cmp['id_cmp']);

        // Delete group weblinks
        $grpWeblinksModel = new Default_Model_GroupWeblinks();
        $grpWeblinksModel->removeGroupWeblinks($id_grp);

        // Delete group-admin links from grp_has_admin_usr.
        $grpAdm = new Default_Model_GroupAdmins();
        $grpAdm->removeAdminsFromGroup($id_grp);
        
        // Delete group.
        $where = $this->getAdapter()->quoteInto('id_grp = ?', $id_grp);
        $this->delete($where);
    } // end of removeCampaign
    
    /**
     * Checks if a group exists in db.
     *
     * @author Mikko Aatola
     * @param groupname string
     */
    public function groupExists($groupname)
    {
        $select = $this->_db->select()
                        ->from('usr_groups_grp', array('group_name_grp'))
                        ->where('group_name_grp = ?', $groupname);
        $result = $this->_db->fetchAll($select);

        if (!isset($result[0])) {
            return false;
        } else {
            return true;
        }
    }
}