<?php
/**
 *  MiscController -> Controller for miscellaneous functions
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
 *  MiscController - class
 *
 *  @package     controllers
 *  @author      Jaakko Paukamainen
 *  @copyright   2010 Jaakko Paukamainen 
 *  @license     GPL v2
 *  @version     1.0
 */
class MiscController extends Oibs_Controller_CustomController
{
	public function init()
	{
		parent::init();
		// Disable layout to be rendered
		$this->_helper->layout->disableLayout();
		
		// Set variables available for access in all actions in this class.
		$this->params = $this->getRequest()->getParams(); 
        //$this->id = isset($this->params['id']) ? (int)$this->params['id'] : 0;
        
	}
	
	private function _redirectBack()
	{
		$this->_redirect($_SERVER['HTTP_REFERER']);
	}
	
	function indexAction()
	{
		echo "Move along people, there's nothing to see here! <br />";
	}
	
	function changelangAction()
	{
		$translateSession = new Zend_Session_Namespace('translate');
		$translateSession->translateTo = $this->params['translation_select'];
		$this->_redirectBack();
	}
}