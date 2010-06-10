<?php
/**
 * GroupAdmins database model for group admins table.
 *
 * Copyright (c) <2010>, Mikko Aatola
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
 *  GroupAdmins - class
 *
 *  @package      models
 *  @author       Mikko Aatola
 *  @copyright    2010 Mikko Aatola
 *  @license      GPL v2
 *  @version      1.0
 */ 
class Default_Model_GroupAdmins extends Zend_Db_Table_Abstract
{
    // Table name
    protected $_name = 'grp_has_admin_usr';

    // Table reference map
    protected $_referenceMap    = array(
      'UserGroup' => array(
            'columns'           => array('id_grp'),
            'refTableClass'     => 'Default_Model_Groups',
            'refColumns'        => array('id_grp')
        ),
        'UserUser' => array(
            'columns'           => array('id_usr'),
            'refTableClass'     => 'Default_Model_User',
            'refColumns'        => array('id_usr')
        )
    );
    
    /**
     *	Adds an admin to a group.
     *
     *	@param integer $id_cnt
     *	@param integer $id_usr
     */
    public function addAdminToGroup($id_grp = 0, $id_usr = 0)
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
     * Returns a list of all admins for the specified group.
     *
     * @author Mikko Aatola
     * @param id_grp group id
     * @return array of users
     */
    public function getGroupAdmins($id_grp = 0)
    {
        if ($id_grp != 0) {
            $data = $this->_db->select()
                ->from(array('gad' => 'grp_has_admin_usr'),
                       array('id_usr'))
                ->join(array('usr' =>'users_usr'),
                       'gad.id_usr = usr.id_usr',
                       array('login_name_usr'))
                ->where('gad.id_grp = ?', $id_grp);
                
            $result = $this->_db->fetchAll($data);
            
            return $result;
        }
    }
    
    /*
     * Checks if a user is an admin in a group.
     *
     * @author Mikko Aatola
     * @param id_grp group id
     * @param id_usr user id
     * @return true if is, false if not
     */
    public function userIsAdmin($id_grp, $id_usr)
    {
        $select = $this->_db->select()
                        ->from('grp_has_admin_usr', array('id_grp', 'id_usr'))
                        ->where('id_grp = ?', $id_grp)
                        ->where('id_usr = ?', $id_usr);
        $result = $this->_db->fetchAll($select);

        if (!isset($result[0])) {
            return false;
        } else {
            return true;
        }
    }

}
?>