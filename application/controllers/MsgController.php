<?php
/**
 *  MsgController -> 
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
 *  MsgController - class
 *
 *  @package     models
 *  @author      
 *  @copyright    
 *  @license     GPL v2
 *  @version     1.0
 */
class MsgController extends Oibs_Controller_CustomController 
{
	public function init()
	{		
		$this->view->title = 'msg-index-title';
		
		parent::init();
	}
	
	public function indexAction()
	{
		// Message-format has been moved to postDispatch in CustomController. 
        // Now it formats the message in every controller!
		// $this->_flashMessenger->addMessage("");
		
		
		// if empty message redirect to index
		if(empty($this->view->messages)) {
			$url = $this->_urlHelper->url(array('controller' => 'index',
                                                'action' => 'index', 
                                                'language' => $this->view->language), 
                                          		'lang_default', true);
			$this->_redirector->gotoUrl($url);
		}
	}
}