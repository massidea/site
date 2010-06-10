<?php
/**
 *  InnovationTypes -> InnovationTypes database model for innovation types table.
 *
* 	Copyright (c) <2009>, Pekka Piispanen
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
 *  @author 	Pekka Piispanen
 *  @copyright 	2009 Pekka Piispanen
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class Default_Model_FutureinfoClasses extends Zend_Db_Table_Abstract
{
	// Table name
    protected $_name = 'futureinfo_classes_fic';
    
	// Table primary key
	protected $_primary = 'id_fic';
	
	// Table dependet tables
	protected $_dependentTables = array('Default_Model_ContentHasFutureinfoClasses');

	/**
	*	getAllNamesAndIds
	*
	*	Get all future info class names and id values.
	*
	*	@return array
	*/
	public function getAllNamesAndIds()
	{
		$select = $this->select()->from($this, array('id_fic', 'name_fic'));
		$result = $this->fetchAll($select)->toArray();
		
		return $result;
	} // end of getAllNamesAndIds
} // end of class
?>