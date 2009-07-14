<?php

class PrivmsgController extends Oibs_Controller_CustomController 
{
	public function init()
	{		
		$this->view->title = 'privmsg-index-title';
		
		parent::init();
	}
	
	public function indexAction()
	{
		//  Get user identity
		$auth = Zend_Auth::getInstance();
		if ($auth->hasIdentity()) 
		{
			$models_privmsg = New Models_PrivateMessages();
            
            $privmsgs = $models_privmsg->getPrivateMessagesByUserId($auth->getIdentity()->user_id);
            
            $models_user = New Models_User();
            
            $i = 0;
            while($i < count($privmsgs))
            {
                $privmsgs[$i]['header_pmg'] = $privmsgs[$i]['header_pmg'];
                $privmsgs[$i]['message_body_pmg'] = $privmsgs[$i]['message_body_pmg'];
                $privmsgs[$i]['username_pmg'] = $models_user->getUserNameById($privmsgs[$i]['id_sender_pmg']);
                $privmsgs[$i]['user_has_image'] = $models_user->userHasProfileImage($privmsgs[$i]['id_sender_pmg']);
                $i++;
            }
            $this->view->privmsgs = $privmsgs;
            
            $models_privmsg->markUnreadMessagesAsRead($auth->getIdentity()->user_id);
		}
        else
        {
            // If not logged, redirecting to system message page
            $message = 'privmsg-view-not-logged';
            $this->flash($message, '/'.$this->view->language.'/msg/');
        }
	}
    
    public function sendAction()
    {
        // Get authentication
        $auth = Zend_Auth::getInstance();
        // If user has identity
        if ($auth->hasIdentity())
        {
            // Get requests
            $params = $this->getRequest()->getParams();
            
            // Get content type
            $receiver = isset($params['username']) 
                                ? $params['username'] : '';

            $model_user = New Models_User();
            
            if(!$model_user->usernameExists($receiver))
            {
                // If not logged, redirecting to system message page
                $message = 'privmsg-send-invalid-receiver';
                $this->flash($message, '/'.$this->view->language.'/msg/');
            }
                                
            // Receiver's username to view
            $this->view->receiver = $receiver;
            
            // Sender's username to view
            $this->view->sender = $auth->getIdentity()->username;
            
            // Creating data array for form's hidden fields
            $data = array();
            $data['sender_id'] = $auth->getIdentity()->user_id;
            $data['receiver_id'] = $model_user->getIdByUsername($receiver);
            
            $form = new Forms_PrivMsgForm(null, $data);
            $this->view->form = $form;
            
            // If private message is posted
            if($this->getRequest()->isPost())
            {
                // Get private message data
                $data = $this->getRequest()->getPost();
                
                // Add a private message
                $models_privmsg = new Models_PrivateMessages();
                
                if($models_privmsg->addMessage($data))
                {
                    $message = 'privmsg-add-successful';
                }
                else
                {
                    $message = 'privmsg-add-not-successful';
                }
                
                $this->flash($message, '/'.$this->view->language.'/msg/');
            } // end if
        }
        else
        {
            // If not logged, redirecting to system message page
            $message = 'privmsg-send-not-logged';
            $this->flash($message, '/'.$this->view->language.'/msg/');
        }
    }
}