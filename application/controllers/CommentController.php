<?php

class CommentController extends Oibs_Controller_CustomController
{
	public function init()
	{
		parent::init();
		
		$this->view->title = 'comment-title';
	}
	
	function indexAction()
	{
	}
	/*
	function listAction()
	{
		$params = $this->getRequest()->getParams();
	
		$model = new Models_Comments();
		$data = $model->getByContent((int)$params['id_cnt']);
		
		$this->view->comments = $data;
	}
	
	function viewAction()
	{
		$params = $this->getRequest()->getParams();
	
		$model = new Models_Comments();
		$data = $model->getById((int)$params['id']);
		
		$this->view->comment = $data;
	
	}
	
	function addAction()
	{
	}
*/

	
}
?>