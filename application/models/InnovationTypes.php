<?php
/**
 *  InnovationTypes -> InnovationTypes database model for innovation types table.
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
 *  InnovationTypes - class
 *
 *  @package 	models
 *  @author 		Markus Riihelä & Mikko Sallinen
 *  @copyright 	2009 Markus Riihelä & Mikko Sallinen
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class Default_Model_InnovationTypes extends Zend_Db_Table_Abstract
{
	// Table name
    protected $_name = 'innovation_types_ivt';
    
	// Table primary key
	protected $_primary = 'id_ivt';
	
	// Table dependet tables
	protected $_dependentTables = array('Default_Model_ContentHasInnovationTypes');
/*	
	protected $_referenceMap    = array(
        'ContentInnovationTypes' => array(
            'columns'           => array('id_cnt'),
            'refTableClass'     => 'Default_Model_Content',
            'refColumns'        => array('id_cnt')
        )
    );
*/

	/**
	*	getAllNamesAndIds
	*
	*	Get all innovation types name and id values.
	*
	*	@return array
	*/
	public function getAllNamesAndIds()
	{
		$select = $this->select()->from($this, array('id_ivt', 'name_ivt'));
		$result = $this->fetchAll($select)->toArray();
		
		return $result;
	} // end of getAllNamesAndIds
} // end of class
?>