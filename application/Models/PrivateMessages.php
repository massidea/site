<?php
/**
 *  PrivateMessages -> PrivateMessages database model for private messages table.
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
 *  PrivateMessages - class
 *
 *  @package 	models
 *  @author 		Markus Riihelä & Mikko Sallinen
 *  @copyright 	2009 Markus Riihelä & Mikko Sallinen
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class Models_PrivateMessages extends Zend_Db_Table_Abstract
{
	// Table name
    protected $_name = 'private_messages_pmg';
    
	// Table primary key
	protected $_primary = 'id_pmg';
	
	// Table reference map
	protected $_referenceMap    = array(
        'SenderUser' => array(
            'columns'           => array('id_sender_usr_pmg'),
            'refTableClass'     => 'Models_User',
            'refColumns'        => array('id_usr')
        ),
		'ReceiverUser' => array(
			'columns'			=> array('id_receiver_usr_pmg'),
			'refTableClass'		=> array('Models_User'),
			'refColumns'		=> array('id_usr')
		)
    );	
    
    public function addMessage($data)
	{
        $return = true;
        
		// Create a new row
		$message = $this->createRow();
		
		// Set data to row
		$message->id_sender_pmg = $data['privmsg_sender_id'];
		$message->id_receiver_pmg = $data['privmsg_receiver_id'];
		$message->header_pmg = strip_tags($data['privmsg_header']);
		$message->message_body_pmg = strip_tags($data['privmsg_message']);
        $message->sender_email_pmg = strip_tags($data['privmsg_email']);
		
        $message->read_pmg = 0;
		$message->created_pmg = new Zend_Db_Expr('NOW()');
		$message->modified_pmg = new Zend_Db_Expr('NOW()');
		
		// Add row to database
		if(!$message->save())
        {
            $return = false;
        }
        
        return $return;
    } // end of addMessage
    
    public function getPrivateMessagesByUserId($id_usr)
    {	
        $result = false;
    
		if($id_usr != 0)
		{
			$select = $this->select()
					->from($this, array('*'))
					->where("`id_receiver_pmg` = $id_usr")
                    ->order('created_pmg DESC');

			$result = $this->fetchAll($select)->toArray();   
        }
        
        return $result;
    } // end of GetPrivateMessagesByUserId
    
    public function getCountOfUnreadPrivMsgs($id_usr)
    {
        $select = $this->select()
				->from($this, array('*'))
				->where("`id_receiver_pmg` = $id_usr")
                ->where("`read_pmg` = 0");

		$result = $this->fetchAll($select)->toArray();
        
        return count($result);
    } // end of getCountOfUnreadPrivMsgs
    
    public function markUnreadMessagesAsRead($id_usr)
    {
        $data = array('read_pmg' => 1);			
		$where = $this->getAdapter()->quoteInto('id_receiver_pmg = ?', $id_usr);
		$this->update($data, $where);
    }
} // end of class
?>