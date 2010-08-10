<?php
/**
 *  UserHasGroupWaiting -> UserHasGroupWaiting database model for usr_has_grp_waiting table.
 *
 *     Copyright (c) <2010>, Mikko Korpinen
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
 *  UserHasGroupWaiting - class
 *
 *  @package     models
 *  @author      Mikko Korpinen
 *  @copyright   2010 Mikko Korpinen
 *  @license     GPL v2
 *  @version     1.0
 */ 
class Default_Model_UserHasGroupWaiting extends Zend_Db_Table_Abstract
{
    // Name of table
    protected $_name = 'usr_has_grp_waiting';
    
    // Tables reference map
    protected $_referenceMap    = array(
        'UserUser' => array(
            'columns'           => array('id_usr'),
            'refTableClass'     => 'Default_Model_User',
            'refColumns'        => array('id_usr')
        ),
         'UserGroup' => array(
            'columns'           => array('id_grp'),
            'refTableClass'     => 'Default_Model_Groups',
            'refColumns'        => array('id_grp')
        )

    );
    
    /**
     * addUserWaitingToGroup - Adds a user waiting to a group
     *
     * @author Mikko Korpinen
     * @param id_grp
     * @param id_usr
     */
    public function addUserWaitingToGroup($id_grp = 0, $id_usr = 0)
    {
        if ($id_grp != 0 && $id_usr != 0) {
            // Create a new row.
            $row = $this->createRow();
            
            // Set values.
            $row->id_grp = $id_grp;
            $row->id_usr = $id_usr;
            
            // Add row to db.
            $row->save();
        }
    }
    
    /*
     * removeUserWaitingFromGroup - Removes a user from a group
     *
     * @author Mikko Korpinen
     * @param id_grp
     * @param id_usr
     */
    public function removeUserWaitingFromGroup($id_grp = 0, $id_usr = 0)
    {
        $return = false;
    
        $where = $this->getAdapter()->quoteInto('id_grp = ?', $id_grp);
        $where = $this->getAdapter()->quoteInto(
            "$where AND id_usr = ?", $id_usr);
        if($this->delete($where)) {
            $return = true;
        }
        
        return $return;
    }
    
    /**
     * getAllWaitingUsersInGroup - Returns all waiting users in the specified group
     *
     * @author Mikko Korpinen
     * @param id_grp
     * @return array
     */
    public function getAllWaitingUsersInGroup($id_grp)
    {
        $data = $this->_db->select()
            ->from(array('uhgw' => 'usr_has_grp_waiting'),
                   array('id_usr'))
            ->join(array('usr' =>'users_usr'),
                   'uhgw.id_usr = usr.id_usr',
                   array('login_name_usr'))
            ->join('usr_profiles_usp',
                   'usr.id_usr = usr_profiles_usp.id_usr_usp',
                   array('city' => 'usr_profiles_usp.profile_value_usp'))
            ->joinLeft('cnt_has_usr',
                    'cnt_has_usr.id_usr = uhgw.id_usr',
                    array('count' => 'count(*)'))
            ->where('uhgw.id_grp = ?', $id_grp)
            ->group('uhgw.id_usr')
            ->where('usr_profiles_usp.profile_key_usp = "city"');

        $result = $this->_db->fetchAll($data);
        
        return $result;
    }

    /**
     * getUserCountByGroup - Returns how many user is waiting to join into group
     *
     * @author Mikko Korpinen
     * @param id_grp
     * @return array
     */
    public function getUserCountByGroup($id_grp)
    {
        $data = $this->_db->select()
            ->from(array('uhgw' => 'usr_has_grp_waiting'),
                   array('id_usr'))
            ->where('uhgw.id_grp = ?', $id_grp);

        $result = $this->_db->fetchAll($data);

        return count($result);
    }

    /**
     * getGroupsByWaitingUserId - Returns waiting user groups from the user group table.
     *
     * @author Mikko Korpinen
     * @param id_usr
     * @return array
     */
    public function getGroupsByWaitingUserId($id_usr)
    {
        $data = $this->_db->select()
            ->from(array('uhgw' => 'usr_has_grp_waiting'),
                   array('id_grp'))
            ->join(array('ugg' =>'usr_groups_grp'),
                   'uhgw.id_grp = ugg.id_grp',
                   array('*'))
            ->where('id_usr = ?', $id_usr);

        $result = $this->_db->fetchAll($data);

        return $result;
    }
    
    /**
     * userWaitingGroup - Checks if a user is waiting to join in a group.
     *
     * @author Mikko Korpinen
     * @param id_grp
     * @param id_usr
     * @return boolean
     */
    public function userWaitingGroup($id_grp, $id_usr)
    {
        $select = $this->_db->select()
                        ->from('usr_has_grp_waiting', array('id_grp', 'id_usr'))
                        ->where('id_grp = ?', $id_grp)
                        ->where('id_usr = ?', $id_usr);
        $result = $this->_db->fetchAll($select);

        if (!isset($result[0])) {
            return false;
        } else {
            return true;
        }
    }

} // end of class
?>