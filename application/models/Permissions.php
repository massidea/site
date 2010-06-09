<?php
/**
 *  Permissions -> Permissions database model for permissions table.
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
 *  Permissions - class
 *
 *  @package 	models
 *  @author 		Markus Riihelä & Mikko Sallinen
 *  @copyright 	2009 Markus Riihelä & Mikko Sallinen
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class Default_Model_Permissions extends Zend_Db_Table_Abstract
{
	// Table name
    protected $_name = 'permissions_prm';
    
	// Table primary key
	protected $_primary = 'id_prm';
	
	// Table dependet tables
	protected $_dependentTables = array('Default_Model_GroupHasPermission');
	/*
	protected $_referenceMap    = array(
        'Group' => array(
            'columns'           => array('id_cnt'),
            'refTableClass'     => 'Default_Model_Content',
            'refColumns'        => array('id_cnt')
        )
    );
	*/
} // end of class
?>