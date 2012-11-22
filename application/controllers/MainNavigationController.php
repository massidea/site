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

        $userGroups = null;
        $categories = null;

        $categoryModel = null;
        $userModel = null;

        if($this->id != -1){
            $userModel = new Default_Model_User();
            $categoryModel = new Default_Model_Category();

            $userGroups = $userModel->getUserGroups($this->id);
            $categories = $categoryModel->getCategories();
        }
        $this->view->groups = $userGroups;
        $this->view->categories = $categories;
        /*$userCompaigns = null;
        if($this->id != -1){
            $userModel = new Default_Model_User();
            $userCompaigns = $userModel->getUserCampaigns($this->id);
        }
        $this->view->campaigns = $userCompaigns;
        */
    }

    function indexAction()
    {
    }
}