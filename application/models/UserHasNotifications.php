<?php
/**
 *  UserHasNotifications -> User Has Notifications database model for usr_has_ntf table.
 *
 * 	Copyright (c) <2010> Sami Suuriniemi
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
 *  Notifications - class
 *
 *  @package    models
 *  @author     Sami Suuriniemi
 *  @copyright  2010 Sami Suuriniemi
 *  @license    GPL v2
 *  @version    1.0
 */ 
class Default_Model_UserHasNotifications extends Zend_Db_Table_Abstract
{
	// Name of table
    protected $_name = 'usr_has_ntf';
    
	// Primary key of table
	//protected $_primary = 'id_ntf';
	
	// Tables model depends on
	protected $_dependentTables = array('Default_Model_Notifications', 'Users_usr');

	protected $_referenceMap	= array(
			'User' 			=> array(
							'columns'			=>	array('id_usr'),
							'refTableClass'		=>	'Default_Model_User',
							'refColumns'		=>	array('id_usr')
			),
			'Notification' => array(
							'columns'			=> array('id_ntf'),
							'refTableClass'		=> 'Default_Model_Notifications',
							'refColumns'		=> array('id_ntf')
			)
		);
	
	
} // end of class
?>