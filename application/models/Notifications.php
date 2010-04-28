<?php
/**
 *  Notifications -> Notifications database model for notifications table.
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
class Default_Model_Notifications extends Zend_Db_Table_Abstract
{
	// Name of table
    protected $_name = 'notifications_ntf';
    
	// Primary key of table
	protected $_primary = 'id_ntf';
	
	// Tables model depends on
	protected $_dependentTables = array('Default_Model_UserHasNotifications');

    public function getNotificationsById($usrId)
    {
    	$notifications = array();
        $select = $this->select()
        			   ->from(array('notifications_ntf', array('notification_ntf')))
        			   ->join(array('usr_has_ntf'), 'usr_has_ntf.id_ntf = notifications_ntf.id_ntf', array())
        			   ->where('usr_has_ntf.id_usr = ?', $usrId);
        $result = $this->fetchAll($select);
        foreach ($result as $row) {
        	array_push($notifications, $row->notification_ntf);
        }   
    	return $notifications;
    }
    
	public function getAll()
	{		
		$select = $this->select()
                  	->from($this, array('*'));

        $result = $this->fetchAll($select)->toArray();
		return $result;
	}	
} // end of class
?>