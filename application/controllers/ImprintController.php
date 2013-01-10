<?php
class ImprintController extends Oibs_Controller_CustomController
{
    function init()
    {
        parent::init();
        if ($this->hasIdentity())
            Zend_Layout::getMvcInstance()->setLayout('layout');
        else
            Zend_Layout::getMvcInstance()->setLayout('layout_public');
    }
    public function viewAction()
    {
    }
}
