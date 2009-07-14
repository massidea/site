<?php
/**
 *  ContentController -> Viewing content
 *
* 	Copyright (c) <2009>, Joel Peltonen <joel.peltonen@cs.tamk.fi>
* 	Copyright (c) <2009>, Pekka Piispanen <pekka.piispanen@cs.tamk.fi>
*
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License 
 * as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 *  Copyright (c) <2009>, Joel Peltonen <joel.peltonen@cs.tamk.fi>
 *  Copyright (c) <2009>, Pekka Piispanen <pekka.piispanen@cs.tamk.fi>
 *
 *  This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License 
 *  as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied  
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for  
 * more details.
 *  This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied  
 *  warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for  
 *  more details.
 * 
 * You should have received a copy of the GNU General Public License along with this program; if not, write to the Free 
 * Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *  You should have received a copy of the GNU General Public License along with this program; if not, write to the Free 
 *  Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * License text found in /license/
 *  License text found in /license/
 */
 
/**
 *  ContentController - class
 *
 *  @package 	controllers
 *  @author 		Joel Peltonen & Pekka Piispanen
 *  @copyright 	2009 Joel Peltonen & Pekka Piispanen
 *  @license 	GPL v2
 *  @version 	1.0
 *  @package    controllers
 *  @author     Joel Peltonen & Pekka Piispanen
 *  @copyright  2009 Joel Peltonen & Pekka Piispanen
 *  @license    GPL v2
 *  @version    1.1
 */ 
class Oibs_Controller_CustomController extends Zend_Controller_Action
{
	public $db;
	public $breadcrumbs;
		
	protected $_redirector;
	protected $_flashMessenger;
		
	/**
	*	init
	*
	*	Class initialization
	*
	*/
	public function init()
	{
		// Load languages to view
		$this->view->languages = Zend_Registry::get('Available_Translations');
		$this->view->language = Zend_Registry::get('Zend_Locale');
		
		//$this->db = Zend_Registry::get('db');
		$this->breadcrumbs = new Oibs_Controller_Plugin_BreadCrumbs();
		
		// Add the root step to breadcrumbs
		$this->breadcrumbs->addStep('OIBS Home', '/');
		
		// Flash message
		$this->_flashMessenger 	= $this->_helper->getHelper('FlashMessenger');
		
		// Redirector
		$this->_redirector = $this->_helper->getHelper('Redirector');
		
		// Set database
		$this->db = Zend_Registry::get('db');
		
		// Get users name
		$auth = Zend_Auth::getInstance();
		
		if(isset($auth->getIdentity()->username))
        {
			$this->view->username = $auth->getIdentity()->username;
            $this->view->userid = $auth->getIdentity()->user_id;
            
            $models_privmsg = New Models_PrivateMessages();
            $unread_privmsgs = $models_privmsg->getCountOfUnreadPrivMsgs($auth->getIdentity()->user_id);
            $this->view->unread_privmsgs = $unread_privmsgs;
        }
			
        $params = $this->getRequest()->getParams();
        $this->view->controller = $params['controller'];
        $this->view->action = $params['action'];
            
		// Search form 
		$simpleSearchForm = new Forms_SimpleSearchForm();
		
		// url helper
		$urlHelper = $this->_helper->getHelper('url');
		// $params = $this->getRequest()->getParams();
		
		$url = $urlHelper->url(array('controller' => 'search', 'action' => 'result', 'language' => $this->view->language), 'lang_default', true); 
		$simpleSearchForm ->setAction($url)
			->setMethod('get');
			
		$this->view->searchForm = $simpleSearchForm;
		
		/*
		echo '<pre>';
		print_r($params);
		echo '</pre>';
		*/
		
	} // end of init
		
	/**
	 *	getUrl
	 * 
	 *	returns the url for the action and controller given as
	 *	parameters.
	 *
	 *	@param string action the name of the action
	 * 	@param string controller the name of the controller
	 *	@return string
	 */
	public function getUrl($action = null, $controller = null)
	{
		$url = $this->_helper->url->simple($action, $controller);
		return $url;
	} // end of getUrl
		
	/**
	 *	preDispatch
	 *
	 *	receives a user request before the front controller dispatches 
	 *	the request to the respective action
	 */
	public function preDispatch()
	{
		$auth = Zend_Auth::getInstance();
		if ($auth->hasIdentity()) 
		{
			$this->view->authenticated = true;
			$this->view->identity = $auth->getIdentity();
		}
		else
		{
			$this->view->authenticated = false;
		}
		$this->view->title = $this->breadcrumbs->getTitle();
		
		$this->setMessages();
		
				//(see boostrap) this hack is used in bypassing flashmessenger one hop limiter.
				if (isset($_SESSION["msg"])) {
					//$_SESSION["FlashMessenger"]["default"][1] = $_SESSION["msg"];
					$_SESSION["FlashMessenger"]["default"][0] = $_SESSION["msg"];
				}
				//hax end
		
	} // end of preDispatch
		
	/**
	 * 	postDispatch
	 *
	 *	executes once a controller action has completed, prior to the view
	 *	renderer displaying the view.
	 */
	public function postDispatch()
	{
		$this->setMessages();
		parent::postDispatch();
        $this->_flashMessenger->addMessage("");
	} // end of postDispatch
	
	/**
	 *	flash
	 */		
	protected function flash($message,$to)
	{
		$this->_flashMessenger->addMessage($this->view->translate($message));
		//echo "<pre>"; print_r($_SESSION); echo "</pre>"; die;
		$this->_redirector->gotoUrl($to);
	} // end of flash
		
	/**
	 *	setMessages
	 */
	protected function setMessages()
	{
		$this->view->messages = join("",$this->_flashMessenger->getMessages());
	} // end of setMessages
} // end of class
?>
