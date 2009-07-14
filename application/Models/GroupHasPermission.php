<?php
/**
 *  GroupHasPermission -> GroupHasPermission database model for group permissions table.
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
 *  GroupHasPermission - class
 *
 *  @package 	models
 *  @author 		Markus Riihelä & Mikko Sallinen
 *  @copyright 	2009 Markus Riihelä & Mikko Sallinen
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class Models_GroupHasPermission extends Zend_Db_Table_Abstract
{
	// Table name
    protected $_name = 'grp_has_prm';
	
	// Table reference map
	protected $_referenceMap    = array(
        'PermissionGroup' => array(
            'columns'           => array('id_grp'),
            'refTableClass'     => 'Models_Group',
            'refColumns'        => array('id_grp')
        ),
		 'PermissionPermissions' => array(
            'columns'           => array('id_prm'),
            'refTableClass'     => 'Models_Permissions',
            'refColumns'        => array('id_prm')
        )

    );
} // end of class
?>