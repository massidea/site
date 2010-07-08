<?php


// This model is not used any more?



/**
 *  UserCountry -> 
 *
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
 *  UserCountry - class
 *
 *  @package     models
 *  @author        
 *  @copyright    
 *  @license     GPL v2
 *  @version     1.0
 */
class Default_Model_UserCountry extends Zend_Db_Table_Abstract
{
	// Name of table
    protected $_name = 'countries_ctr';
	
	// Primary key of table
	protected $_primary = 'iso_ctr';
    
    /**
    *   Get country name by id.
    *
    *   @param id int country id
    *   @return result string country name
    */
    public function getCountryNameById($id = -1)
    {
        if ($id != -1 && $id != "" && $id != NULL) {
            $select = $this->select()
                                ->from($this, array('name_ctr'))
                                ->where('iso_ctr = ?', $id)
                                ->limit(1);
                                
            $result = $this->fetchAll($select)->current();
            
            return $result['name_ctr'];
        } else {
            return "";
        }
    }

    /**
    *   getCountryList
    *
    *   Gets country listing.
    *   
    *   @return array
    */
    public function getCountryList()
    {
        $select = $this->select()->from($this, array('name_ctr', 'iso_ctr'))
                                 ->order('name_ctr');
        
        $result = $this->_db->fetchAll($select);
        
        return $result;
    }
}	
?>
