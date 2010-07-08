<?php
/**
 *  LoginController -> User profile information , login, logout and registration
 *
* 	Copyright (c) <2009>, Joel Peltonen <joel.peltonen@cs.tamk.fi>
* 	Copyright (c) <2009>, Pekka Piispanen <pekka.Piispanen@cs.tamk.fi>
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
 *  LoginController - class
 *
 *  @package 	controllers
 *  @author 		Joel Peltonen & Pekka Piispanen
 *  @copyright 	2009 Joel Peltonen & Pekka Piispanen
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class LoginController extends Oibs_Controller_CustomController
{

	public function init()
	{
		parent::init();
		
		$this->view->title = 'account-login-title';
	}
	
	/**
	 *	index page: Contains users login form page
	 */
	function indexAction()
	{

		// check if user if logged in
		$auth = Zend_Auth::getInstance();
		
		// if user is already logged in redirect to account page
		if ($auth->hasIdentity())
		{
			$this->redirect('/');
		}

		$request = $this->getRequest();
		
		// determine the page the user was originally trying to request
		$redirect = $request->getPost('redirect');
		if (strlen($redirect) == 0)
		{
			//$redirect = $request->getServer('REQUEST_URI');
		}
		if (strlen($redirect) == 0)
		{
			//$redirect = '/account';
		}

		// initialize errors
		$errors = array();
		
		// process login if request method is post
		if ($request->isPost()) 
		{
			// fetch login details from form and validate them
			$username = $request->getPost('username');
			$password = $request->getPost('password');
			
			if (strlen($username) == 0)
				$errors['username'] = 'Required field must not be blank';
			if (strlen($password) == 0)
				$errors['password'] = 'Required field must not be blank';
			if (count($errors) == 0) 
			{
				// setup the authentication adapter
				$adapter = new Zend_Auth_Adapter_DbTable($this->db,
					'users_usr',
					'login_name_usr',
					'password_usr',
					'md5(?)');
				$adapter->setIdentity($username);
				$adapter->setCredential($password);
				
				// try and authenticate the user
				$result = $auth->authenticate($adapter);
				
				echo '<pre>';
				print_r($result);
				echo '</pre>';
				
				if ($result->isValid()) 
				{
					$user = new Default_Model_User();
					
					//$user->load($adapter->getResultRowObject()->userid);
					// record login attempt
					//$user->loginSuccess();
					// create identity data and write it to session
					//$identity = $user->createAuthIdentity();
					//$auth->getStorage()->write($identity);
					// send user to page they originally request
					// $this->_redirect($redirect);
				}
				

				// record failed login attempt
				//DatabaseObject_User::LoginFailure($username, $result->getCode());
				$errors['username'] = 'Your login details were invalid';
			}
		}
		// redirect the user away if there was no post
		else{}
		
	} // end of indexAction()
	
	/**
	 *	login page: contains login form for users. If user was redirected
	 *	to login by AclManager the user is redirected back to the page that was requested originally, 
	 *	if user is already logged in redirects them to account/index page. Writes login attemps to a log file.
	 */
	function loginAction()
	{
	}

	/**
	 *	logout page: logout the user and redirect to home page.
	 */
	function logoutAction()
	{
		Zend_Auth::getInstance()->clearIdentity();
		//$this->_redirect('/index');
	}
	
	/**	
	*	fetch forgotten password page: Users can request a password reset by givin their username, a new password is created and sent
	*	to user email address. Users must also activate the new password on this page by clicking the activation link in the email.
	*
	*	@param	String		$action		Defines wheather the user is asking for reset or activating the new password
	*	@param	String		$username		Username whose password will be changed
	*	@param	int			$id			Used in password activation, this is the id of user whose new password wil l be activated
	*	@param	int			$key			Md5 hash to confirm that user is following the link in activation email
	*/
	public function fetchpasswordAction()
	{
		// if a user's already logged in, send them to their account home page
		if (Zend_Auth::getInstance()->hasIdentity())
		{
			$this->_redirect('/account');
		}
		
		// initialize the error array
		$errors = array();
		$action = $this->getRequest()->getQuery('action');
		
		if ($this->getRequest()->isPost())
		{
			$action = 'submit';
		}
		
		// check is the user requesting password reset or activating new password
		switch ($action) 
		{
			case 'submit': // request new password
			
				// get username form post
				$username = trim($this->getRequest()->getPost('username'));
				
				// check that username is not empty
				if (strlen($username) == 0) 
				{
					$errors['username'] = 'Required field must not be blank';
				}
				else 
				{
					$user = new DatabaseObject_User($this->db);
					// load user data
					if ($user->load($username, 'username')) 
					{
						// create the new password and send email to user
						$user->fetchPassword($this->view->language);
						
						// redirect user
						$url = '/account/fetchpassword?action=complete';
						$this->_redirect($url);
					}
					else
					{
						$errors['username'] = 'Specified user not found';
					}	
				}
				break;
			
			case 'complete': // if user submitted the request password form
				// nothing to do, show message in view
				break;
			
			// activate new password
			case 'confirm':
				
				$id = $this->getRequest()->getQuery('id');
				$key = $this->getRequest()->getQuery('key');
				$user = new DatabaseObject_User($this->db);
				// load user data
				if (!$user->load($id))
				{
					$errors['confirm'] = 'Error confirming new password';
				}	
				// confirm the key and activate the new password
				else if (!$user->confirmNewPassword($key))
				{
					$errors['confirm'] = 'Error confirming new password';
				}
				break;
		}
			
		// inject the possible errors and the action to view
		$this->view->errors = $errors;
		$this->view->action = $action;
	}
}
?>