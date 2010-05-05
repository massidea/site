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
		// Get user identity
		$auth = Zend_Auth::getInstance();

		if ($auth->hasIdentity()) {
			$Default_Model_privmsg = New Default_Model_PrivateMessages();

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
		} else {
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

		// If user has identity
		if ($auth->hasIdentity()) {
			// Get requests
			$params = $this->getRequest()->getParams();

			// Get content type
			$receiver = isset($params['username'])
			? $params['username'] : '';

			$model_user = New Default_Model_User();

			$url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index', 
                                                'language' => $this->view->language), 
                                          'lang_default', true);

			if(!$model_user->usernameExists($receiver)) {
				// If not logged, redirecting to system message page
				$message = 'privmsg-send-invalid-receiver';
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

				// Add a private message
				$Default_Model_privmsg = new Default_Model_PrivateMessages();

				if($Default_Model_privmsg->addMessage($data)) {
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
					
					$senderId = $data['privmsg_sender_id'];
					$receiverEmail = $model_user->getUserEmail($receiverId);
					$receiverUsername = $model_user->getUserNameById($receiverId);
					$senderUsername = $model_user->getUserNameById($senderId);

					$bodyText = "You have received a new private message at Massidea.org\n\n"
							.$senderUsername." sent you a private message\n\n"
							.$data['privmsg_header'].": ".$data['privmsg_message'];

					$bodyHtml = "Your have received a new private message at ".'<a href="'.$baseUrl.'/">Massidea.org</a><br /><br />'
							.'<a href="'.$baseUrl."/".$this->view->language.'/account/view/user/'.$senderUsername.'">'.$senderUsername.'</a>'
							.' sent you a private message <br /><br />'
							.'<a href="'.$baseUrl."/".$this->view->language.'/privmsg/'.$id.'">'.$data['privmsg_header'].'</a><br /> '.str_replace("\n", "<br />", $data['privmsg_message']);

					$mail = new Zend_Mail();
					$mail->setBodyText($bodyText);
					$mail->setBodyHtml($bodyHtml);
					$mail->setFrom('no-reply@massidea.org', 'Massidea.org');
					$mail->addTo($receiverEmail, $receiverUsername);
					$mail->setSubject('Massidea.org: You have a new private message');
					$mail->send();
				}
				$this->flash($message, $url);
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