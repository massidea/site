<?php
/**
 *  IndexController
 *
 *  @package 	controllers
 *  @license 	GPL v2
 *  @version 	2.0
 */

/**
 *  IndexController
 *
 *  @package 	controllers
 *  @license 	GPL v2
 *  @version 	2.0
 */
class IndexController extends Oibs_Controller_CustomController
{
	function init()
	{
		parent::init();
        if ($this->hasIdentity())
            Zend_Layout::getMvcInstance()->setLayout('layout');
        else
            Zend_Layout::getMvcInstance()->setLayout('layout_public');
		$this->view->title = "index-home";
    }

    function indexAction()
    {
        if ($this->hasIdentity()) {
            $this->_forward('list', 'content');
        }
    }

}
