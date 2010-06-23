<?php
/**
 *  Countries -> Countries database model for countries table.
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
class Default_Model_Countries extends Zend_Db_Table_Abstract
{
	// Table name
    protected $_name = 'countries_ctr';
	
	// Table primary key
	protected $_primary = 'iso_ctr';

    /**
	*	getCountryByIso
	*
	*	Gets country by iso code.
	*
    *   @author Mikko Korpinen
    *   @param string iso_ctr
	*	@return array
	*/
    public function getCountryByIso($iso_ctr)
    {
        $select = $this->select()
				->from($this, array('*'))
				->where('iso_ctr = ?', $iso_ctr);
        
		$result = $this->fetchAll($select)->toArray();
        
        return $result[0];
    }

    /**
	*	getCountryByPrintableName
	*
	*	Gets country by printable name.
	*
    *   @author Mikko Korpinen
    *   @param string printable_name_ctr
	*	@return array
	*/
    public function getCountryByPrintableName($printable_name_ctr)
    {
        $select = $this->select()
				->from($this, array('*'))
				->where('printable_name_ctr = ?', $printable_name_ctr);
 
		$result = $this->fetchAll($select)->toArray();

        return $result[0];
    }

    /**
	*	getCountryPrintableNameByIso
	*
	*	Gets country printable name by iso code.
	*
    *   @author Mikko Korpinen
    *   @param string iso_ctr
	*	@return String
	*/
    public function getCountryPrintableNameByIso($iso_ctr)
    {
        $select = $this->select()
				->from($this, array('*'))
				->where('iso_ctr = ?', $iso_ctr);

		$result = $this->fetchAll($select)->toArray();

        return $result[0]['printable_name_ctr'];
    }

    /**
	*	getAllCountries
	*
	*	Gets all possible timezones.
	*
    *   @author Mikko Korpinen
	*	@return array
	*/
    public function getAllCountries()
    {
        $select = $this->select()
				->from($this, array('*'));
        
		$result = $this->fetchAll($select)->toArray();

        return $result;
    }
	
} // end of class
?>