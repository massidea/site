<?php

class MainNavigationController extends Oibs_Controller_CustomController
{
	function init()
	{
		parent::init();
        Zend_Layout::getMvcInstance()->setLayout('layout_public');
    }

    function indexAction()
    {
    }
}