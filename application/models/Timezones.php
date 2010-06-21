<?php
/**
 *  Timezones -> Timezones database model for timezones table.
 *
 * 	Copyright (c) <2010>, Mikko Korpinen
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
 *  @package    models
 *  @author     Mikko Korpinen
 *  @copyright 	2010 Mikko Korpinen
 *  @license    GPL v2
 *  @version    1.0
 */ 
class Default_Model_Timezones extends Zend_Db_Table_Abstract
{
	// Table name
    protected $_name = 'timezones_tmz';
	
	// Table primary key
	protected $_primary = 'id_tmz';

    /**
	*	getTimezoneById
	*
	*	Gets timezone by id.
	*
    *   @author Mikko Korpinen
    *   @param id_tmz timezone id
	*	@return array
	*/
    public function getTimezoneById($id_tmz)
    {
        $select = $this->select()
				->from($this, array('*'))
				->where('id_tmz = ?', $id_tmz);
        
		$result = $this->fetchAll($select)->toArray();
        
        return $result[0];
    }

    /**
	*	getTimezoneTextById
	*
	*	Gets timezone text by id.
	*
    *   @author Mikko Korpinen
    *   @param id_tmz timezone id
	*	@return String
	*/
    public function getTimezoneTextById($id_tmz)
    {
        $select = $this->select()
				->from($this, array('*'))
				->where('id_tmz = ?', $id_tmz);

		$result = $this->fetchAll($select)->toArray();

        return $result[0]['gmt_tmz'].' '.$result[0]['timezone_location_tmz'];
    }

    /**
	*	getTimezoneByLocation
	*
	*	Gets timezone by id.
	*
    *   @author Mikko Korpinen
    *   @param timezone_location_tmz timezone location
	*	@return array
	*/
    public function getTimezoneByLocation($timezone_location_tmz)
    {
        $select = $this->select()
				->from($this, array('*'))
				->where('timezone_location_tmz = ?', $timezone_location_tmz);

		$result = $this->fetchAll($select)->toArray();

        return $result[0];
    }

    /**
	*	getAllTimezones
	*
	*	Gets all possible timezones.
	*
    *   @author Mikko Korpinen
	*	@return array
	*/
    public function getAllTimezones()
    {
        $select = $this->select()
				->from($this, array('*'));
        
		$result = $this->fetchAll($select)->toArray();

        return $result;
    }
	
} // end of class
?>