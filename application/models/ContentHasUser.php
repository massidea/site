<?php
/**
 *  ContentHasUser -> ContentHasInnovationTypes database model for content has userlink table.
 *
* 	Copyright (c) <2009>, Markus Riihel�
* 	Copyright (c) <2009>, Mikko Sallinen
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
 *  ContentHasUser - class
 *
 *  @package 	models
 *  @author 		Markus Riihel� & Mikko Sallinen
 *  @copyright 	2009 Markus Riihel� & Mikko Sallinen
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class Default_Model_ContentHasUser extends Zend_Db_Table_Abstract
{
	// Table name
    protected $_name = 'cnt_has_usr';
	
	// Table reference map
	protected $_referenceMap    = array(
        'UserContent' => array(
            'columns'           => array('id_cnt'),
            'refTableClass'     => 'Default_Model_Content',
            'refColumns'        => array('id_cnt')
        ),
		 'UserUser' => array(
            'columns'           => array('id_usr'),
            'refTableClass'     => 'Default_Model_User',
            'refColumns'        => array('id_usr')
        )
    );
    
    /**
    *   getMostActive gets the users with most content
    *   @todo make this work
    *   @author joel peltonen
    *   @param limit - how many to return
    */
    public function getMostActive($limit = 10)
    {
        $contentSelect = $this->_db->select()
                               ->from(array('chu' => 'cnt_has_usr'), 
                                      array('id_usr', 'id_cnt', 'count'=>'COUNT(chu.id_usr)'))
                               ->joinRight(array('usr' => 'users_usr'),
                                          'chu.id_usr = usr.id_usr',
                                          array('id_usr', 'login_name_usr'))
                               ->group('chu.id_usr')
                               ->order('count DESC')
                               ->limit($limit);
        
        $result = $this->_db->fetchAll($contentSelect);

        return $result;
   }
	
	/**
	*	addUserToContent
	*
	*	Add specified user to specified content.
	*
	*	@param integer $id_cnt
	*	@param integer $id_usr
	*	@param boolean $owner
	*/
	public function addUserToContent($id_cnt = 0, $id_usr = 0, $owner = 0)
	{
		// If id values not 0
		if($id_cnt != 0 && $id_usr != 0)
		{
			// Create a new row
			$row = $this->createRow();
			
			// Set id values
			$row->id_cnt = $id_cnt;
			$row->id_usr = $id_usr;
			$row->owner_cnt_usr = $owner;
			
			// Add row to database
			$row->save();
		} // end if
	} // end of addUserToContent
    
    public function getContentOwners($id = 0)
    {
        $select = $this->select()
                        ->from(array('cnt_has_usr' => 'cnt_has_usr'), array('id_usr'))
                        ->where('id_cnt = ?', $id);
        $result = $this->_db->fetchAll($select);

        return $result[0];
    }
    
    /** 
    *   removeUserFromContent
    *   Removes relations between content and user
    *   
    *   @param int id_cnt Id of the content
    *   @return bool $return
    *   @author Pekka Piispanen
    */
    public function removeUserFromContent($id_cnt)
    {
        $return = false;
    
        $where = $this->getAdapter()->quoteInto('id_cnt = ?', $id_cnt);
        if($this->delete($where)) {
            $return = true;
        }
        
        return $return;
    }
    
    /**
    *   contentHasOwner
    *
    *   Check if user is contents owner.
    *
    *   @param int $userId Id of user
    *   @param int $contentId Id of content
    *   @return boolean
    */
    public function contentHasOwner(&$userId = -1, &$contentId = -1) 
    {
        $owner = false;
        
        if ($userId != -1 && $contentId != -1) {
            $select = $this->select()
                           ->from($this, array('id_usr'))
                           ->where('id_usr = ?', $userId)
                           ->where('id_cnt = ?', $contentId)
                           ->where('owner_cnt_usr = 1')
                           ->limit(1);
        
            $result = $this->_db->fetchAll($select);
            
            if(isset($result[0]) && !empty($result[0])) {
                $owner = true;
            }
        }
        
        return $owner;
    }
} // end of class
?>