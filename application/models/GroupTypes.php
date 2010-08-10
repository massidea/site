<?php
/**
 *  GroupTypes -> GroupTypes database model for group types table.
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
 *  GroupTypes - class
 *
 *  @package    models
 *  @author     Mikko Korpinen
 *  @copyright  2010 Mikko Korpinen
 *  @license    GPL v2
 *  @version    1.0
 */ 
class Default_Model_GroupTypes extends Zend_Db_Table_Abstract
{
	// Table name
    protected $_name = 'group_types_gtp';
    
	// Table primary key
	protected $_primary = 'id_gtp';
	
	// Table dependet tables
	protected $_dependentTables = array('Default_Model_Groups');
	
	// Table reference map
	protected $_referenceMap    = array(
        'ContentType' => array(
            'columns'           => array('id_gtp'),
            'refTableClass'     => 'Default_Model_Groups',
            'refColumns'        => array('id_type_grp')
        )
    );
	
	/**
	 * getAllTypes - Gets all possible group types, names and id values.
	 *
     * @author Mikko Korpinen
	 * @return array
	 */
	public function getAllTypes()
	{
		$select = $this->select()
                       ->from($this, 
                              array('id_gtp',
                                    'name_gtp',
                                    'key_gtp'));
                                    
		$result = $this->fetchAll($select)->toArray();
		
		return $result;
	}

    /**
     * getIdByTypeKey - Get group type id by group type key
     *
     * @author Mikko Korpinen
     * @param string $key
     * @return int
     */
	public function getIdByTypeKey($key)
	{
		$select = $this->select()
                       ->from($this, array('id_gtp'))
                       ->where('`key_gtp` = ?', $key);

		$result = $this->fetchAll($select)->toArray();
		
		return $result[0]['id_gtp'];
	}

    /**
     * getIdByName - Get group type id by group type name
     *
     * @author Mikko Korpinen
     * @param string $name
     * @return int
     */
	public function getIdByTypeName($name)
	{
		$select = $this->select()
                       ->from($this, array('id_gtp'))
                       ->where('`name_gtp` = ?', $name);

		$result = $this->fetchAll($select)->toArray();

		return $result[0]['id_gtp'];
	}
	
    /**
     * getTypeKeyById - Get group type key by group type id
     *
     * @author Mikko Korpinen
     * @param $id_gtp int group type id number
     * @return string
     */
	public function getTypeKeyById($id_gtp = 0)
	{
		$select = $this->select()
				->from($this, array('key_gtp'))
				->where('`id_gtp` = ?', $id_gtp);

		$result = $this->fetchAll($select)->toArray();
		
		return $result[0]['key_gtp'];
	}

    /**
     * getTypeNameById - Get group type name by group type id
     *
     * @author Mikko Korpinen
     * @param int $id_gtp
     * @return string
     */
    public function getTypeNameById($id_gtp = 0)
    {
        $select = $this->select()
				->from($this, array('name_gtp'))
				->where('`id_gtp` = ?', $id_gtp);

		$result = $this->fetchAll($select)->toArray();

		return $result[0]['name_gtp'];
    }

    /**
     * groupTypeExists - Check if group type exists in database.
     *
     * @author Mikko Korpinen
     * @param string $id
     * @return boolean
     */
    public function groupTypeExists($id = 0)
    {
        $exists = false;

        if ($id != 0) {
            $select = $this->select()
                            ->from($this, array('id_gtp'))
                            ->where('id_gtp = ?', $id)
                            ->limit(1);

            $result = $this->fetchAll($select)->toArray();

            if(isset($result[0]) && !empty($result[0])) {
                $exists = true;
            }
        }

        return $exists;
    }
    
    /**
     * groupTypeKeyExists - Check if group type key exists in database.
     *
     * @author Mikko Korpinen
     * @param string $key
     * @return boolean
     */
    public function groupTypeKeyExists($key = null)
    {
        $exists = false;
        
        if ($key != null) {
            $select = $this->select()
                            ->from($this, array('id_gtp'))
                            ->where('key_gtp = ?', $key)
                            ->limit(1);
            
            $result = $this->fetchAll($select)->toArray();
            
            if(isset($result[0]) && !empty($result[0])) {
                $exists = true;
            }
        }
        
        return $exists;
    }

    /**
     * groupTypeNameExists - Check if group type name exists in database.
     *
     * @author Mikko Korpinen
     * @param string $name
     * @return boolean
     */
    public function groupTypeNameExists($name = null)
    {
        $exists = false;

        if ($name != null) {
            $select = $this->select()
                            ->from($this, array('id_gtp'))
                            ->where('name_gtp = ?', $name)
                            ->limit(1);

            $result = $this->fetchAll($select)->toArray();

            if(isset($result[0]) && !empty($result[0])) {
                $exists = true;
            }
        }

        return $exists;
    }
    
    /**
     * isOpen - Check if param type id means that group is open
     *
     * @author Mikko Korpinen
     * @param int $id_gtp
     * @return boolean
     */
    public function isOpen($id_gtp)
    {
        $select = $this->select()
                        ->from($this, array('id_gtp', 'key_gtp'))
                        ->where('id_gtp = ?', $id)
                        ->limit(1);

        $result = $this->fetchAll($select)->toArray();

        if(isset($result[0]) && !empty($result[0]) && $result[0]['key_gtp'] === 'open_grp') {
            return true;
        }
        
        return false;
    }
    
    /**
     * isClosed - Check if param type id means that group is closed
     *
     * @author Mikko Korpinen
     * @param int $id_gtp
     * @return boolean
     */
    public function isClosed($id_gtp)
    {
        $select = $this->select()
                        ->from($this, array('id_gtp', 'key_gtp'))
                        ->where('id_gtp = ?', $id_gtp)
                        ->limit(1);

        $result = $this->fetchAll($select)->toArray();

        if(isset($result[0]) && !empty($result[0]) && $result[0]['key_gtp'] === 'closed_grp') {
            return true;
        }
        
        return false;
    }

} // end of class
?>