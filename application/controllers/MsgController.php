<?php

class MsgController extends Oibs_Controller_CustomController 
{
	public function init()
	{		
		$this->view->title = 'msg-index-title';
		
		parent::init();
	}
	
	public function indexAction()
	{
		// This message-format has been moved to postDispatch in CustomController. Now it formats the message in every controller!
		// $this->_flashMessenger->addMessage("");
	}
}