<?php
/**
 *  Languages -> Languages database model for languages table.
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
 *  Languages - class
 *
 *  @package 	models
 *  @author 		Markus Riihelä & Mikko Sallinen
 *  @copyright 	2009 Markus Riihelä & Mikko Sallinen
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class Default_Model_Languages extends Zend_Db_Table_Abstract
{
	// Table name
    protected $_name = 'languages_lng';
	
	// Table primary key
	protected $_primary = 'id_lng';
	
	// Table dependet tables
	protected $_dependentTables = array('Default_Model_User', 'Default_Model_Industries');
	
	/*
	protected $_referenceMap    = array(
        'Languages' => array(
            'columns'           => array('id_lng'),
            'refTableClass'     => 'Default_Model_Content',
            'refColumns'        => array('id_lng_usr')
        )
    );
	*/
    
    public function getLangIdByLangName($lang)
    {
        $select = $this->select()
				->from($this, array('id_lng'))
				->where("`iso6391_lng` = '$lang'");
        
		$result = $this->fetchAll($select)->toArray();
        
        return $result[0]['id_lng'];
    }
    
    public function getLangNameByLangId($id_lng)
    {
        $select = $this->select()
				->from($this, array('iso6391_lng'))
				->where("`id_lng` = '$id_lng'");
        
		$result = $this->fetchAll($select)->toArray();
        
        return $result[0]['iso6391_lng'];
    }
    
    /**
	*	getAllNamesAndIds
	*
	*	Gets all possible language names and ids.
	*
	*	@return array
	*/
	public function getAllNamesAndIds()
	{
		$select = $this->select()
                       ->from($this, array('id_lng', 'name_lng'));
                                    
		$result = $this->fetchAll($select)->toArray();
		
		return $result;
	} // end of getAllNamesAndIds
	
	/**
	*	getAllNamesAndCodes
	*
	*	Gets all possible language names and codes
	*
	*	@return array
	*/
	public function getAllNamesAndCodes()
	{
		$select = $this->select()
                       ->from($this, array('iso6391_lng', 'name_lng'));
                                    
		$result = $this->fetchAll($select)->toArray();
		
		return $result;
	} // end of getAllNamesAndIds
} // end of class
?>