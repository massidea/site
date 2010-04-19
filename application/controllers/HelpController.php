<?php
 /**
 *  HelpController -> 
 *
 *
 *  This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License 
 *  as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied  
 *  warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for  
 *  more details.
 * 
 *  You should have received a copy of the GNU General Public License along with this program; if not, write to the Free 
 *  Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 *  License text found in /license/
 */
 
/**
 *  HelpController - class
 *
 *  @package        controllers
 *  @author         
 *  @copyright      
 *  @license        GPL v2
 *  @version        1.0
 */
 class HelpController extends Oibs_Controller_CustomController
 {
 	public function init()
    {
        parent::init();
        
        $this->view->title = 'account-title';
    } // end of init()
    
 	public function indexAction()
 	{
 		/* Not needed anymore... am I right?
 		$data = array();
 		
 		$params = $this->getRequest()->getParams();
        
        $language = $params['language'];
        
       //  echo $username; die;
 		
 		$help = new Default_Model_Help();
 		
 		$data = $help->getAllHelp($language);//->toArray();
 		
 		$this->view->help = $data;
 		*/
 	}

	public function aboutAction()
	{
		// I can hear the wind blow...
	}
    
    public function ideaAction() 
    {
    
    }
    
    public function workingAction()
    {
    
    }
    
    public function benefitsAction()
    {
    
    }
    
    public function builtAction()
    {
    
    }
    
    public function contactAction()
    {
    
    }
    
    public function bannerAction()
    {
    
    }

    public function guidelinesAction()
    {
    
    }
 }
