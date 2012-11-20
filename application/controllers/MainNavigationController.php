<?php

class MainNavigationController extends Oibs_Controller_CustomController
{
    private $id = -1;
	function init()
	{
		parent::init();
        Zend_Layout::getMvcInstance()->setLayout('layout_public');
        if (Zend_Controller_Action_HelperBroker::hasHelper('redirector')) {
            $redirector = Zend_Controller_Action_HelperBroker::getExistingHelper('redirector');
        }
        $auth = Zend_Auth::getInstance();
        if($auth->hasIdentity()){
            $identity = $auth->getIdentity();
            $this->id = $identity->user_id;
        } else {
            $target = $this->_urlHelper->url(array('controller' => 'index',
                    'action' => 'index',
                    'language' => $this->view->language),
                'lang_default', true);
            $redirector->gotoUrl($target);
        }
    }

    function indexAction()
    {
    }

    function groupAction(){
        if($this->id != -1){
            $userModel = new Default_Model_User();
            $userGroups = $userModel->getUserGroups($this->id);
var_dump($userGroups); exit;
        }
    }
}