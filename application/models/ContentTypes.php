<?php
/**
 *  ContentTypes -> ContentTypes database model for content types table.
 *
* 	Copyright (c) <2009>, Markus Riihelä
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
 *  ContentTypes - class
 *
 *  @package 	models
 *  @author 		Markus Riihelä & Mikko Sallinen
 *  @copyright 	2009 Markus Riihelä & Mikko Sallinen
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class Default_Model_ContentTypes extends Zend_Db_Table_Abstract
{
	// Table name
    protected $_name = 'content_types_cty';
    
	// Table primary key
	protected $_primary = 'id_cty';
	
	// Table dependet tables
	protected $_dependentTables = array('Default_Model_Content');
	
	// Table reference map
	protected $_referenceMap    = array(
        'ContentType' => array(
            'columns'           => array('id_cty'),
            'refTableClass'     => 'Default_Model_Content',
            'refColumns'        => array('id_cty_cnt')
        )
    );
	
	/**
	*	getAllNamesAndIds
	*
	*	Gets all possible content types, names and id values.
	*
	*	@return array
	*/
	public function getAllNamesAndIds()
	{
		$select = $this->select()
                       ->from($this, 
                              array('id_cty', 
                                    'name_cty', 
                                    'key_cty'));
                                    
		$result = $this->fetchAll($select)->toArray();
		
		return $result;
	} // end of getAllNamesAndIds
	
	public function getIdByType($type)
	{
		$select = $this->select()
                       ->from($this, array('id_cty'))
                       ->where('`key_cty` = ?', $type);

		$result = $this->fetchAll($select)->toArray();
		
		return $result[0]['id_cty'];
	} // end of getIdByType
	
    /**
    *   Get content type by contet type id
    *
    *   @param $id_cty int content type id number
    *   @return string 
    */
	public function getTypeById($id_cty = 0)
	{
		$select = $this->select()
				->from($this, array('key_cty'))
				->where('`id_cty` = ?', $id_cty);

		$result = $this->fetchAll($select)->toArray();
		
		return $result[0]['key_cty'];
	} // end of getIdByType
    
    /**
    *   contentTypeExists
    *
    *   Check if content type exists in database.
    *
    *   @param $type string Content type name
    *   @return boolean
    */
    public function contentTypeExists(&$type = null)
    {
        $exists = false;
        
        if ($type != null) {
            $select = $this->select()
                            ->from($this, array('id_cty'))
                            ->where('key_cty = ?', $type)
                            ->limit(1);
            
            $result = $this->fetchAll($select)->toArray();
            
            if(isset($result[0]) && !empty($result[0])) {
                $exists = true;
            }
        }
        
        return $exists;
    }
} // end of class
?>