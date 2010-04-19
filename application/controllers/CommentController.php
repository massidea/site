<?php
/**
 *  CommentController -> 
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
 *  CommentController - class
 *
 *  @package     models
 *  @author        
 *  @copyright    
 *  @license     GPL v2
 *  @version     1.0
 */
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
	
		$model = new Default_Model_Comments();
		$data = $model->getByContent((int)$params['id_cnt']);
		
		$this->view->comments = $data;
	}
	
	function viewAction()
	{
		$params = $this->getRequest()->getParams();
	
		$model = new Default_Model_Comments();
		$data = $model->getById((int)$params['id']);
		
		$this->view->comment = $data;
	
	}
	
	public function addAction()
	{
	}
*/

	public function flagAction()
	{
        // Set an empty layout for view
        $this->_helper->layout()->setLayout('empty');
        
        // Get requests
        $params = $this->getRequest()->getParams();
        $flaggedId = $params['flaggedid'];
        
        // Models for the job
        $auth = Zend_Auth::getInstance()->getIdentity();
        $userId = $auth->user_id;
        $flagmodel = new Default_Model_CommentFlags();
        $flagExists = $flagmodel->flagExists($flaggedId, $userId);
        $commentmodel = new Default_Model_Comments();
        $commentExists = $commentmodel->commentExists($flaggedId);
		if($commentExists == true)
		{
	        if($flagExists == true)
	        {
	        	$success = 0;
	        }
	        elseif($flagExists == false)
	        {
	        	$success = 1;
	        	$flagmodel->addFlag($flaggedId,$userId);
	        }
		}
		elseif($commentExists == false)
		{
			$success = 0;
		}
        $this->view->success = $success;
	}
	
}
?>