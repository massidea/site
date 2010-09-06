<?php
/**
 *  UserApiKey -> User database model for user api key table.
 *
 *	Copyright (c) <2010>, Jaakko Paukamainen
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
 *  User - class
 *
 *  @package	models
 *  @author		Jaakko Paukamainen
 *  @copyright	2010 Jaakko Paukamainen
 *  @license	GPL v2
 *  @version	1.0
 */ 
class Default_Model_UserApiKey extends Zend_Db_Table_Abstract
{
    // Table name
    protected $_name = 'usr_apikeys_uak';
    
    // Primary key of table
    protected $_primary = 'id_uak';
    
    // Tables model depends on
    protected $_dependentTables = array('Default_Model_User');
    /*
    protected $_dependentTables = array('Default_Model_UserProfiles', 'Default_Model_UserImages',
                                        'Default_Model_PrivateMessages', 'Default_Model_CommentRatings', 
                                        'Default_Model_Comments', 'Default_Model_ContentPublishTimes', 
                                        'Default_Model_ContentHasUser', 'Default_Model_UserHasGroup', 
                                        'Default_Model_Links', 'Default_Model_Files',
                                        'Default_Model_ContentRatings','Default_Model_UserHasFavourites',
                                        'Default_Model_UserHasNotifications','Default_Model_UserFavourites');
	*/
        
    // Table references  to other tables
    /*
    protected $_referenceMap    = array(
        'UserLanguage' => array(
            'columns'            => array('id_lng_usr'),
            'refTableClass'        => 'Default_Model_Languages',
            'refColumns'        =>    array('id_lng')        
        ),
        'CommentUser' => array(
            'columns'            => array('id_usr'),
            'refTableClass'        => 'Default_Model_Comments',
            'refColumns'        => array('id_usr_cmt')
        )
    );
    */
    protected $_id = 0;
    protected $_data = null;
    
    public function hasApiKey($id = -1)
    {
    	$return = false;
    	if($id)
    	{
            // Create query
            $select = $this->_db->select()
                                ->from('usr_apikeys_uak', 
                                       array('apikey_uak'))
                                ->where('id_usr_uak = ?', $id);
            
            // Fetch data from database
            $result = $this->_db->fetchAll($select);
            $return = (!empty($result)) ? true:false;
    	}
    	return $return;
    }
    
    public function getApiKeyById($id = -1)
    {
    	$result = false;
    	if($id)
    	{
            // Create query
            $select = $this->_db->select()
                                ->from('usr_apikeys_uak', 
                                       array('apikey_uak'))
                                ->where('id_usr_uak = ?', $id);
            
            // Fetch data from database
            $result = $this->_db->fetchAll($select);
            $result = empty($result) ? false : $result[0]['apikey_uak'];
    	}
    	return $result;
    }
    
    public function getUserIdByApiKey($key = -1)
    {
    	$result = false;
    	if($key)
    	{
    		$select = $this->_db->select()
    							->from('usr_apikeys_uak',
    									array('id_uak'))
    							->where('apikey_uak = ?', $key);
    		$result = $this->_db->fetchOne($select);
    	}
    	return $result;
    }
    
    private function _generateApiKey($id)
    {
    	$hash = md5(microtime().$id);
    	return $hash;
    }
    
	public function addApiKey($id = -1, $returnKey = false)
	{
		$return = false;
		if(!$this->hasApiKey($id))
		{
	        $row = $this->createRow();
	        $row->id_usr_uak = $id;
	        $row->apikey_uak = $this->_generateApiKey($id);
	        $row->created_uak = new Zend_Db_Expr('NOW()');
	        $row->modified_uak = new Zend_Db_Expr('NOW()');
			$result = $row->save();
	        $return = ($returnKey) ? $row->apikey_uak : $result;
		}
		
		return $return;
	}
}