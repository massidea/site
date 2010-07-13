<?php
/**
 *  GroupHasGroup -> GroupHasGroup database model for group has group link table.
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
 *  GroupHasGroup - class
 *
 *  @package    models
 *  @author     Mikko Korpinen
 *  @copyright 	2010 Mikko Korpinen
 *  @license    GPL v2
 *  @version    1.0
 */ 
class Default_Model_GroupHasGroup extends Zend_Db_Table_Abstract
{
	// Table name
    protected $_name = 'grp_has_grp';
	
	// Table reference map
	protected $_referenceMap    = array(
        'ParentContent' => array(
            'columns'           => array('id_parent_grp'),
            'refTableClass'     => 'Default_Model_Groups',
            'refColumns'        => array('id_grp')
        ),  
		 'ChildContent' => array(
            'columns'           => array('id_child_grp'),
            'refTableClass'     => 'Default_Model_Groups',
            'refColumns'        => array('id_grp')
        ),
    );
	
	/**
     * addGroupToGroup - Add group to group
     *
     * @author Mikko Korpinen
     * @param int $id_parent_grp
     * @param int $id_child_grp
     */
	public function addGroupToGroup($id_parent_grp = 0, $id_child_grp = 0)
	{
		// If id values not 0
		if($id_parent_grp != 0 && $id_child_grp != 0)
		{
			// Create a new row
			$row = $this->createRow();
			
			// Set id values
			$row->id_parent_grp = $id_parent_grp;
			$row->id_child_grp = $id_child_grp;
			
			// Add row to database
			$row->save();
		} // end if
	}

    /**
     * getGroupGroups - Get all group groups
     *
     * @param int $id_grp
     */
    public function getGroupGroups($id_grp) {

        $result = array();  // container for final results array

        $groupSelectParents = $this->_db->select()
                                   ->from(array('ghg' => 'grp_has_grp'),
                                          array('id_parent_grp', 'id_child_grp'))
                                   ->joinLeft(array('ugg' => 'usr_groups_grp'),
                                          'ugg.id_grp = ghg.id_child_grp',
                                          array('id_grp', 'group_name_grp', 'description_grp', 'body_grp'))
                                   ->joinLeft(array('ghau' => 'grp_has_admin_usr'),
                                           'ghau.id_grp = ugg.id_grp',
                                           array('id_usr'))
                                   ->joinLeft(array('uu' => 'users_usr'),
                                           'uu.id_usr = ghau.id_usr',
                                           array('id_usr', 'login_name_usr'))
                                   ->where('ghg.id_parent_grp = ?', $id_grp)
                                   ->group('ugg.id_grp');

        $groupSelectChilds = $this->_db->select()
                                   ->from(array('ghg' => 'grp_has_grp'),
                                          array('id_parent_grp', 'id_child_grp'))
                                   ->joinLeft(array('ugg' => 'usr_groups_grp'),
                                          'ugg.id_grp = ghg.id_parent_grp',
                                          array('id_grp', 'group_name_grp', 'description_grp', 'body_grp'))
                                   ->joinLeft(array('ghau' => 'grp_has_admin_usr'),
                                           'ghau.id_grp = ugg.id_grp',
                                           array('id_usr'))
                                   ->joinLeft(array('uu' => 'users_usr'),
                                           'uu.id_usr = ghau.id_usr',
                                           array('id_usr', 'login_name_usr'))
                                   ->where('ghg.id_child_grp = ?', $id_grp)
                                   ->group('ugg.id_grp');

        $result['parents'] = $this->_db->fetchAll($groupSelectParents);
        $result['childs'] = $this->_db->fetchAll($groupSelectChilds);

        return $result;
    }

    /**
     * checkIfGroupHasGroup - Check if group has specified group
     *
     * @author Mikko Korpinen
     * @param int $id_parent_grp
     * @param int $id_child_grp
     * @return boolean
     */
    public function checkIfGroupHasGroup($id_parent_grp = -1, $id_child_grp = -1) {
        if($id_parent_grp != -1 && $id_child_grp != -1)
        {
            $select = $this->select()
                           ->from($this, array('*'))
                           ->where('`id_parent_grp` = ?', $id_parent_grp)
                           ->where('`id_child_grp` = ?', $id_child_grp);

            $result = $this->fetchAll($select)->toArray();

            if(count($result) != 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * removeGroupFromGroups - Remove group from all groups where it has been linked
     *
     * @param int $id_grp
     * @return boolean
     */
    public function removeGroupFromGroups($id_grp = 0)
    {
        $parent = $this->getAdapter()->quoteInto('id_parent_grp = ?', (int)$id_grp);
        $child = $this->getAdapter()->quoteInto('id_child_grp = ?', (int)$id_grp);
        $where = "$parent OR $child";
        if ($this->delete($where)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * removeGroupFromGroup - Remove group from group
     *
     * @param int $id_parent_grp
     * @param int $id_child_grp
     * @return boolean
     */
    public function removeGroupFromGroup($id_parent_grp = 0, $id_child_grp = 0)
    {
        $parent = $this->getAdapter()->quoteInto('id_parent_grp = ?', (int)$id_parent_grp);
        $child = $this->getAdapter()->quoteInto('id_child_grp = ?', (int)$id_child_grp);
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