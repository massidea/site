<?php
/**
 *  PrivmsgController ->
 *
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
 *  PrivmsgController - class
 *
 *  @package     models
 *  @author
 *  @copyright
 *  @license     GPL v2
 *  @version     1.0
 */
class PrivmsgController extends Oibs_Controller_CustomController
{
	public function init()
	{
		$this->view->title = 'privmsg-index-title';

		parent::init();
	}

	public function indexAction()
	{
        $action = $this->getRequest()->getPost('delete_privmsg');
        
		// Get user identity
		$auth = Zend_Auth::getInstance();
		
		if ($auth->hasIdentity()) {
			$Default_Model_privmsg = New Default_Model_PrivateMessages();
			
			// Delete button was pressed
			if (isset($action)) {
				if (substr($action, 0, 11) == 'delete_one_') {
					// Separate the id from the value of 'delete_privmsg'
					$deleteMsgId = (int)substr($action, 11);
					
					// Delete the pointed message
					$Default_Model_privmsg->getAdapter()->delete('private_messages_pmg', 'id_pmg = '.$deleteMsgId);
				}
				else if (substr($action, 0, 15) == 'delete_selected') {
					// Get the IDs of the first and last selected message
					$firstMsgId = $this->getRequest()->getPost('delete_first');
					$lastMsgId = $this->getRequest()->getPost('delete_last');
					
					// Gather an array of all the selected message IDs
					$selectedMsgs = array();
        			for ($i = $firstMsgId; $i > ($firstMsgId - $lastMsgId); $i--) {
        				if ($this->getRequest()->getPost('select_'.$i) == 'on') {
        					$selectedMsgs[] = $i;
        				}
        			}
        			
					// Go through the messages and delete them
					for ($i = 0; $i < count($selectedMsgs); $i++) {
						$Default_Model_privmsg->getAdapter()->delete('private_messages_pmg', 'id_pmg = '.$selectedMsgs[$i]);
					}
				}
			}

			$privmsgs = $Default_Model_privmsg->getPrivateMessagesByUserId($auth->getIdentity()->user_id);

			$Default_Model_user = New Default_Model_User();

			$i = 0;

			while($i < count($privmsgs)) {
				$privmsgs[$i]['header_pmg'] = $privmsgs[$i]['header_pmg'];
				$privmsgs[$i]['message_body_pmg'] = $privmsgs[$i]['message_body_pmg'];
				$privmsgs[$i]['username_pmg'] = $Default_Model_user->getUserNameById($privmsgs[$i]['id_sender_pmg']);
				$privmsgs[$i]['user_has_image'] = $Default_Model_user->userHasProfileImage($privmsgs[$i]['id_sender_pmg']);
				$i++;
			}

			$this->view->privmsgs = $privmsgs;

			$Default_Model_privmsg->markUnreadMessagesAsRead($auth->getIdentity()->user_id);
		}
		else {
			// If not logged, redirecting to system message page
			$message = 'privmsg-view-not-logged';

			$url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index', 
                                                'language' => $this->view->language), 
                                          'lang_default', true);

			$this->flash($message, $url);
		}
	}

	public function sendAction()
	{

		// Get authentication
		$auth = Zend_Auth::getInstance();
		$absoluteBaseUrl = strtolower(trim(array_shift(explode('/', $_SERVER['SERVER_PROTOCOL'])))) . 
    						'://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getBaseUrl();
		

		// If user has identity
		if ($auth->hasIdentity()) {
			// Get requests
			$params = $this->getRequest()->getParams();

			// Get content type
			$receiver = isset($params['username']) ? $params['username'] : '';

			$model_user = New Default_Model_User();

			$url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index', 
                                                'language' => $this->view->language), 
                                          		'lang_default', true);

			if(!$model_user->usernameExists($receiver)) {
				// If not logged, redirecting to system message page
				$message = 'privmsg-send-invalid-receiver';
				$this->flash($message, $url);
			} else if($model_user->getIdByUsername($receiver) == $auth->getIdentity()->user_id) {
				$message = 'privmsg-send-own-account';
				$this->flash($message, $url);
			}

			// Receiver's username to view
			$this->view->receiver = $receiver;

			// Sender's username to view
			$this->view->sender = $auth->getIdentity()->username;

			// Creating data array for form's hidden fields
			$data = array();

			$data['sender_id'] = $auth->getIdentity()->user_id;
			$data['receiver_id'] = $model_user->getIdByUsername($receiver);

			$form = new Default_Form_PrivMsgForm(null, $data);
			$this->view->form = $form;

			// If private message is posted
			if($this->getRequest()->isPost()) {
				// Get private message data
				$data = $this->getRequest()->getPost();
				 
				if ($form->isValid($data)) {
					// Add a private message
					$Default_Model_privmsg = new Default_Model_PrivateMessages();
	
					if($Default_Model_privmsg->addMessage($data) && $data['sender_id'] != $data['receiver_id']){
						$message = 'privmsg-add-successful';
					} else {
						$message = 'privmsg-add-not-successful';
					}
	
					// Send email to user about new private message
					// if user allows private message notifications
					$receiverId = $data['privmsg_receiver_id'];
					$notificationsModel = new Default_Model_Notifications();
					$notifications = $notificationsModel->getNotificationsById($receiverId);
	
					if (in_array('privmsg', $notifications)) {
						
						$senderName = $auth->getIdentity()->username; 
						$receiverUsername = $model_user->getUserNameById($receiverId);
						
						$emailNotification = new Oibs_Controller_Plugin_Email();
		                $emailNotification->setNotificationType('privmsg')
		                    			   ->setSenderId($auth->getIdentity()->user_id)
		                    			   ->setReceiverId($receiverId)
		                    			   ->setParameter('URL', $absoluteBaseUrl."/en")
		                    			   ->setParameter('SENDER-NAME', $senderName)
		                    			   ->setParameter('MESSAGE-TITLE', $data['privmsg_header'])
		                    			   ->setParameter('MESSAGE-BODY', nl2br($data['privmsg_message']));
			           	if ($emailNotification->isValid()) {
		            		$emailNotification->send();
		            	} else {
							//echo $emailNotification->getErrorMessage(); die;
		            	}
					}	
					$this->flash($message, $url);
				}
			} // end if
		} else {
			// If not logged, redirecting to system message page
			$message = 'privmsg-send-not-logged';

			$url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index', 
                                                'language' => $this->view->language), 
                                          	 	'lang_default', true);

			$this->flash($message, $url);
		}
	}
}