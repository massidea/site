<?php
/**
 *  ErrorController -> 
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
 *  ErrorController - class
 *
 *  @package        controllers
 *  @author         
 *  @copyright      
 *  @license        GPL v2
 *  @version        1.0
 */
class ErrorController extends Oibs_Controller_CustomController 
{
	public function errorAction() 
    { 
        // Ensure the default view suffix is used so we always return good 
        // content
        $this->_helper->viewRenderer->setViewSuffix('phtml');

        // Grab the error object from the request
        $errors = $this->_getParam('error_handler'); 

        // $errors will be an object set as a parameter of the request object, 
        // type is a property
        switch ($errors->type) { 
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER: 
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION: 

                // 404 error -- controller or action not found 
                $this->getResponse()->setHttpResponseCode(404); 
                $this->view->message = 'Page not found'; 
                break; 
            default: 
                // application error 
                $this->getResponse()->setHttpResponseCode(500); 
                $this->view->message = 'Application error'; 
                break; 
        }

        if ($this->getInvokeArg('env') == 'development') {
	        $logger = Zend_Registry::get('logs');
	        if(isset($logger['errors'])) {
	            $message = sprintf(
	                "FROM: %s. \nURI: %s\nMESSAGE: %s. \nSTACK TRACE:\n%s", 
	                $_SERVER['REMOTE_ADDR'],
					$errors->request->getRequestUri(),
	                $errors->exception->getMessage(),
	                $errors->exception->getTraceAsString()
	            );
	            
	            $logger['errors']->notice($message);
	            //$logger['errors']->debug($errors->exception->getMessage() . "\n" . );
	        }
        } else {
                if($errors->type == Zend_Controller_Plugin_ErrorHandler::EXCEPTION_OTHER) {
                        $message = sprintf(
                                "FROM: %s<br />URI: %s<br />MESSAGE: %s<br /><br />STACK TRACE: <br />%s",
                                $_SERVER['REMOTE_ADDR'],
                                                $errors->request->getRequestUri(),
                                $errors->exception->getMessage(),
                                nl2br($errors->exception->getTraceAsString())
                        );
                                $mail = new Zend_Mail();
                                $subject = "Massidea.org - ErrorController";

                        $mail->setBodyHtml($message);
                        $mail->setFrom('no-reply@massidea.org', 'Massidea.org');
                        $mail->addTo('main@massidea.flowdock.com');
                        $mail->setSubject($subject);
                        $mail->send();
                }
        }
        
        // pass the environment to the view script so we can conditionally 
        // display more/less information
        $this->view->env       = $this->getInvokeArg('env'); 
        
        // pass the actual exception object to the view
        $this->view->exception = $errors->exception; 
        
        // pass the request to the view
        $this->view->request   = $errors->request; 
    } 
}
