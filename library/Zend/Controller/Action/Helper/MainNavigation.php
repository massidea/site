<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jürgen
 * Date: 27.11.12
 * Time: 16:27
 * To change this template use File | Settings | File Templates.
 */

class Zend_Controller_Action_Helper_MainNavigation extends Zend_Controller_Action_Helper_Abstract
{
    protected  $id = -1;
    public function init()
    {

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
        //$this->view->groups = $userGroups;
       // $this->view->categories = $categories;
    }

    public function viewRenderer(){
        $view    = new Zend_View(array('encoding' => 'UTF-8'));
        $view->setScriptPath("/" . APPLICATION_PATH . "/views/scripts");
        $options = array('noController' => true, 'neverRender' => false);
        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer($view, $options);
        $viewRenderer->setView($view)
            ->setViewSuffix('php');
        //$viewRenderer->render();

//        Zend_Layout::getMvcInstance()->setLayout('layout_public');
       /* $path = "../../../../application/views/scripts/mainnavigation";
        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer($path);
        $view = new
        $viewRenderer->setView('MainNavigation')->renderScript()
            ->setViewSuffix('php');*/
    }
}