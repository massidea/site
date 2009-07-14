<?php
/**
 *  AccountController -> User profile information , login, logout and registration
 *
*     Copyright (c) <2008>, Matti Särkikoski <matti.sarkikoski@cs.tamk.fi>
*     Copyright (c) <2008>, Jani Palovuori <jani.palovuori@cs.tamk.fi>
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
 *  AccountController - class
 *
 *  @package     controllers
 *  @author         Matti Särkikoski & Jani Palovuori
 *  @copyright     2008 Matti Särkikoski & Jani Palovuori
 *  @license     GPL v2
 *  @version     1.0
 */ 
class AccountController extends Oibs_Controller_CustomController
{

    /**
    *    init
    *    
    *    Initialization of account controller.
    *
    */
    public function init()
    {
        parent::init();
        
        $this->view->title = 'account-title';
    } // end of init()
    
    /**
    *    profilethumbAction
    *    
    *    Gets users profile thumbnail image from database. Sets image to view with empty layout. 
    *
    */
    function profilethumbAction()
    {
        // Set an empty layout for view
        $this->_helper->layout()->setLayout('empty');
        
        // Get requests
        $params = $this->getRequest()->getParams();
        
        $thumb = isset($params['thumb']) ? true : false;
        
        $this->view->image = null;
        
        if (isset($params['id'])) {
            // Get thumbnail data from database
            $user = new Models_User($params['id']);
            $image = $user->getUserImageData($params['id'], $thumb);
            
            // If image data is not null, set imagedata and filetype to view.
            if($image != null) {
                $this->view->image = $image;
                $this->view->filetype = mime_content_type($image);
            } // end if
        } // end if
    } // end of profilethumbAction()
        
    /**
    *    indexAction
    *    
    *    Contains users account information page, accessible only by users who are logged in.
    *
    */
    function indexAction()
    {
        // Set array for userdata
        $data = array();
        
        //  Get user identity
        $auth = Zend_Auth::getInstance();
        
        // If user is logged in
        if ($auth->hasIdentity()) {
            // Get users identity
            $identity = $auth->getIdentity();
            
            // User id
            $id = $identity->user_id;
            
            // Load content created by user
            $user = new Models_User();
                
            // Get users info
            $data = $user->getUserRow($id)->toArray();
            
            // Get content user has released
            $content_list = $user->getUserContent($id);
            
            // Set user data to view
            $this->view->authorContents = $content_list;        
        } // end if
        
        // set user data to view
        $this->view->user = $data;
    } // end of indexAction()
    
    /*
    *    viewAction
    *
	*    Gets user profile information, users content and comments.
    */
    function viewAction()
    {
        //  Get user identity
        $auth = Zend_Auth::getInstance();
        
        $user_edit = false;
        
        $params = $this->getRequest()->getParams();
        
        $username = $params['user'];
        
        $content_types = new Models_ContentTypes();
        $select = $content_types->select()->order('id_cty ASC');
        $this->view->content_types = $content_types->fetchAll($select);
        
        $user = new Models_User();
        
        $data = $user->getUserByName($username);
        $this->view->user = $data;
        
        // Get content user has released
        $content_list = $user->getUserContent($data['id_usr'], 1, 15);
        
        $temp = array();
		
        foreach ($content_list as $c) {
            $temp[$c['key_cty']][] = $c;
        } // end foreach
		
        $content_list = $temp;
        
        // Set user data to view
        $this->view->authorContents = $content_list;
        //print_r($content_list); die();
        
        $this->view->user_has_image = $user->userHasProfileImage($data['id_usr']);
        
        // If user is logged in
        if ($auth->hasIdentity()) {
            // Get users identity
            $identity = $auth->getIdentity();
            
            if ($data['id_usr'] == $identity->user_id) {
                $user_edit = true;
            } // end if
        } // end if
        
        $this->view->user_edit = $user_edit;
    }
    
    /**
    *    loginAction
    *
    *    Contains login form for users. If user was redirected
    *    to login by AclManager the user is redirected back to the page that was requested originally, 
    *    if user is already logged in redirects them to account/index page. Writes login attemps to a log file.
    */
    function loginAction()
    {
        // check if user is logged in
        $auth = Zend_Auth::getInstance();
        
        // Get url helper
        $urlHelper = $this->_helper->getHelper('url');
        
        // if user is already logged in redirect away from here
        if ($auth->hasIdentity()) {    
            $target = $urlHelper->url(array('controller' => 'index', 'action' => 'index', 
                                        'language' => $this->view->language), 'lang_default', true);
            $this->_redirect($target);
        } // end if
        
        // creata new LoginForm and set to view
        $form = new Forms_LoginForm();
        $this->view->form = $form;

        // Get request
        $request = $this->getRequest();
        $formData = $this->_request->getPost();
        
        // process login if request method is post
        if ($request->isPost()) {
            // Check user authentity if form data is valid
            if($form->isValid($formData)) {
                // Get username and password
                $username = $formData['username'];
                $password = $formData['password'];  

                $userModel = new Models_User();
                $saltLength = $userModel->getSaltCountByUsername($username);

                if($saltLength == 7) {
                    // setup the authentication adapter
                    $adapter = new Zend_Auth_Adapter_DbTable($this->db,
                        'users_usr',
                        'login_name_usr',
                        'password_usr',
                        'MD5(CONCAT(?))');
                    $adapter->setIdentity($username);
                    $adapter->setCredential($password);
                    
                    // try and authenticate the user
                    $result = $auth->authenticate($adapter);
                } else {          
                    // setup the authentication adapter
                    $adapter = new Zend_Auth_Adapter_DbTable($this->db,
                        'users_usr',
                        'login_name_usr',
                        'password_usr',
                        'MD5(CONCAT(password_salt_usr, ?, password_salt_usr))');
                    $adapter->setIdentity($username);
                    $adapter->setCredential($password);
                    
                    // try and authenticate the user
                    $result = $auth->authenticate($adapter);
                }
                
                // If user is authenticated
                if ($result->isValid()) {
                    // Get user id
                    $id = $adapter->getResultRowObject()->id_usr;
                    
                    // record login attempt
                    $user = new Models_User($id);
                    $user->loginSuccess();
                    
                    // create identity data and write it to session
                    $identity = $user->createAuthIdentity();
                    $auth->getStorage()->write($identity);
                    
                    // send user to front page
                    $redirect = $urlHelper->url(array('controller' => 'index', 'action' => 'index', 
                                                'language' => $this->view->language), 'lang_default', true);
                    $this->_redirect($redirect);
                } // end if
                else
                {
                    $this->view->errormsg = $this->view->translate('account-login-not-successful');
                }
        } //end if
        } // end if
    } // end of loginAction()

    /**
    *    logoutAction
    *
     *    Logout the user and redirect to home page.
     */
    function logoutAction()
    {
        // Clear users session data
        Zend_Auth::getInstance()->clearIdentity();
        
        // Redirect user
        $this->flash('logout-succesful-msg', '/en/msg/');    
    } // end of logoutAction()

    /**
    * registerAction
    *
    * User registration page and post-validation actions
    *
    * @author Joel Peltonen
    */
    function registerAction()
    {
        // Create new registration form
        $form = new Forms_RegistrationForm();
        $this->view->form = $form;    
        
        // Get requests
        $request = $this->getRequest();
        
        // If form is POST, get and validate form data
        if ($this->_request->isPost()) 
        {
            $formData = $this->_request->getPost();

            $valid = $form->isValid($formData);
            
            // If form data is valid, handle database insertions
            if ($valid) {
                // user data handling
                $user = new Models_User();
                if (!$user->registerUser($formData)) {
                    $this->flash('registration-usermodel-data-procesing-failure', '/en/msg/');
                }
                
                // Fetch user id, set variables for reminder saving
                $uid = $user->getIdByUsername($formData['username']);
                $urq = $formData['reminder_question'];
                $ura = $formData['reminder_answer'];
                
                // user image handling
                $userImages = new Models_UserImages();
                if (!$userImages->newUserImage($uid)) {
                    $this->flash('registration-userimages-data-procesing-failure', '/en/msg/');
                }
                
                // user profile initiation and reminder saving
                $userProfiles = new Models_UserProfiles();
                if (!$userProfiles->initNewUser($uid, $urq, $ura)) {
                    $this->flash('registration-userprofile-data-procesing-failure', '/en/msg/');
                }
                
                // save reminder question and answer to profile table
                try {
                    $this->flash('registration-successful', '/en/msg/');
                } catch(exception $e) {
                    echo "<pre>"; print_r($e); echo "</pre>"; die;
                }
            }
        }
    }
    
    /**
    *    captchaAction
    *
    *    Create captcha
    *
    */
    function captchaAction()
    {
        // Set views layout to empty
        $this->_helper->layout()->setLayout('empty');
          
        // generate code with 5 characters
        $security_code = $this->generateCode(5);

        // start session so user input can be compared to $security_code
        $session = new Zend_Session_Namespace('registration');
        $session->security_code = md5($security_code);

        // Set the image width and height
        $width = 150;
        $height = 48; 

        // Create the image resource
        $image = ImageCreate($width, $height);  

        // Set the font
        $font = "images/HARNGTON.TTF";

        // define colors used in image
        $white = ImageColorAllocate($image, 255, 255, 255);
        $blue = ImageColorAllocate($image, 51, 102, 153);
        $green = ImageColorAllocate($image, 112, 191, 12);
        $black = ImageColorAllocate($image, 0, 0, 0);
        $grey = ImageColorAllocate($image, 221, 221, 221);
        $grey2 = ImageColorAllocate($image, 198, 198, 198);
        $grey3 = ImageColorAllocate($image, 170, 170, 170);

        //Make the background grey
        ImageFill($image, 0, 0, $grey); 

        //Add randomly generated string in white to the image
        //ImageString($image, 5, 20, 1, strtoupper($security_code), $green); 
        //array imagettftext  ($image, float $size ,float $angle ,int $x ,int $y  , int $color  , string $fontfile  , string $text  )
        //imagettftext($im,    20, 0, 10, 20, $black, $font, $text);
        imagettftext($image, 30, 0, 20, 35, $green, $font, strtoupper($security_code));
        
        //Throw in some lines to make it a little bit harder for any bots to break
        $s = ($width*$height)/1000; 
		
        for($i=0; $i < $s; $i++) {
            imageline($image, mt_rand(0, $width), mt_rand(0, $height), mt_rand(0, $width), mt_rand(0, $height), $grey);
        } // end for
        
               // Shadow effect
        ImageLine($image, 0, 0, $width, 1, $grey3);                 // top 1st line (dark)
        ImageLine($image, 0, 1, $width, 1, $grey2);                 // top 2nd line (semi-dark)
        ImageLine($image, 0, 0, 1, $height, $grey3);                // left 1st line (dark)
        ImageLine($image, 1, 1, 1, $height, $grey2);                // left 2nd line (semi-dark)
        
        $this->view->image = $image;
    } // end of captchaAction()
    
    /**
    *    generateCode
    *
     *     generates random string of numbers and letters for captcha image
     *
     *    @param integer $characters count of characters in code
     *    @return string
     */
    private function generateCode($characters)
    {
      // list all possible characters, similar looking characters and vowels have been removed 
      $possible = '346789bcdfghjkmnpqrtvwxy';
      $code = '';
      
      for ($i = 0;$i < $characters; $i++) { 
         $code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
      } // end for
      return $code;
   } // end of generateCode()

    /**    
    *    fetch forgotten password page: Users can request a password reset by givin their username, a new password is created and sent
    *    to user email address. Users must also activate the new password on this page by clicking the activation link in the email.
    *
    *    @param    String        $action        Defines wheather the user is asking for reset or activating the new password
    *    @param    String        $username        Username whose password will be changed
    *    @param    int            $id            Used in password activation, this is the id of user whose new password wil l be activated
    *    @param    int            $key            Md5 hash to confirm that user is following the link in activation email
    */
    public function fetchpasswordAction()
    {
        /*
        $this->breadcrumbs->addStep('Fetchpassword');
    
        $url_helper = $this->_helper->getHelper('url');
    
        // if a user's already logged in, send them to their account home page
        if (Zend_Auth::getInstance()->hasIdentity())
        {
            // $this->_redirect('/account');
            $this->_redirect = $url_helper->url(array('controller' => 'account', 'action' => 'index', 'language' => $this->view->language), 'lang_default', true);
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
                    $user = new Models_User();
                    
                    // load user data
                    if ($user->getUserByName($username) != null) 
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
                $user = new Models_User();
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
        */
    }
    
    /**
    *    settings page: page for users to edit their personal information, accessible only to users who are logged in.
    *    Divided to different "tabs" to simplify the edit form.
    *
    *    @param    string    page        defines which form page is displayed
    */
    function settingsAction()
    {
        // set breadscrumbs
        $this->breadcrumbs->addStep('Settings');
        
        // Get authentication
        $auth = Zend_Auth::getInstance();
		
        // If user has identity
        if ($auth->hasIdentity())
		{    
			// Retrieving user id from the identity
			$identity = $auth->getIdentity();
			$id = $identity->user_id;

			// Fetching the form -class
            $form = new Forms_AccountSettingsForm();
            $this->view->form = $form;
			
			// Gathering data for form population
			$userinfos = new Models_UserProfiles();
			$settingsdata = $userinfos->getUserInfoById($id);

			if(isset($settingsdata))
			{
				$form->populate($settingsdata);
			}
			
			// If submit has been pressed...
			$request = $this->getRequest();
			if($this->_request->isPost())
			{
				$formData = $this->_request->getPost();
			
				if($form->isValid($formData))
				{
					$auth = Zend_Auth::getInstance();
		
					// If user is logged in
					if ($auth->hasIdentity()) 
					{
						$userprofile = new Models_UserProfiles();
						
						// Updates first name if set
						if(strlen($formData['first_name']) != 0 || $formData['first_name'] != $settingsdata['first_name'])
						{
							$userprofile->setUserFirstName($id, $formData);
						}
						
						// Updates surname if set
						if(strlen($formData['surname']) != 0 || $formData['surname'] != $settingsdata['surname'])
						{
							$userprofile->setUserSurname($id, $formData);
						}
						
						$user = new Models_User($id);
						
						// Updates email
						if(strlen($formData['email']) != 0)
						{
							$user->changeUserEmail($id, $formData['email']);
						}

						// Updates the password
						if(strlen($formData['password']) != 0)
						{
							$user->changeUserPassword($id, $formData['password']);
						}
						
							$this->flash('Information has been changed.', '/en/account/settings/');	
					}
				}
				else
				{
					// Just for possible debugging...
					// echo $form->getErrors();
					// echo $form->getMessages();
				}
			}
        } // end if
        else 
		{
            // Get url helper
            $urlHelper = $this->_helper->getHelper('url');
            
            $target = $urlHelper->url(array('controller' => 'index', 'action' => 'index', 
                                        'language' => $this->view->language), 'lang_default', true);
            $this->_redirect($target);
        } // end else
  
        /*
        // get requests
        $request = $this->getRequest();

        // if page is not set show the default tab
        if (!$page = $request->getParam("page"))
        {
            $page ="account";
        }

        // some vatiables to make the tabs (these are here for a reason, but i'm not sure for what reason)
        $this->view->account_class = "content_menu_item";
        $this->view->account_url = "images/content_menu_left.gif";
        $this->view->contact_class = "content_menu_item";
        $this->view->contact_url = "images/content_menu_left.gif";
        $this->view->personal_class = "content_menu_item";
        $this->view->personal_url = "images/content_menu_left.gif";
        $this->view->profile_class = "content_menu_item";
        $this->view->profile_url = "images/content_menu_left.gif";

        // settings page is divided to  tabs, this switch defines which tab button is activated currently
        // other tabs not yet implemented
        switch ($page) {
        case "account":
            $this->view->account_class = "content_menu_item_current";
            $this->view->account_url = "images/content_menu_left_current.gif";
            break;
        }
        // set the page variable to view so that the right page will be shown
        $this->view->page = $page;

        // user id of the user who is logged in
        $userid = $this->view->identity->user_id;

        
        // process the form and show errors if update fails
        $fp = new FormProcessor_Userprofile($this->db, $userid);
        if ( $request->isPost() )
        {
            if (!$fp->process($request))
            {
                // set  errors to view if update failed
                $this->view->errors = $fp->getErrors();
            }
        }
        // user data from form processor
        $user = $fp->user;
        // set the data to view
        $this->view->user = $user;
        $this->view->fp = $fp;
        */
    } // end of settingsAction
    
    /*
    *   userListingAction
    *
    *   Gets listing of all users.
    */
    function userlistAction()
    {
        try {
        // Get authentication
        $auth = Zend_Auth::getInstance();
        
        // If user has identity
        if ($auth->hasIdentity()) { 
            // Get requests
            $params = $this->getRequest()->getParams();
            
            // Get page nummber and items per page
    		$page = isset($params['page']) ? $params['page'] : 1;
    		$count = isset($params['count']) ? $params['count'] : 10;
            
            // Get user listing
            $user = new Models_User();
            $userListing = $user->getUserListing();
            
            // User list search form
            $userSearch = new Forms_UserListSearchForm();
            $this->view->userSearch = $userSearch;
                        
            if (!empty($userListing)) {
                // Content pagination
    			$paginator = Zend_Paginator::factory($userListing);
    			
    			// Set items per page
    			$paginator->setItemCountPerPage($count);
                
    			// Get items by page
    			$paginator->getItemsByPage($page);
                
    			// Set current page number
    			$paginator->setCurrentPageNumber($page);
    			
    			Zend_Paginator::setDefaultScrollingStyle('Sliding');
    			
    			$view = new Zend_View();
    			$paginator->setView($view);
    			
    			// Set paginator for view
    			$this->view->userListPaginator = $paginator;	
            } // end if
            
        $this->view->count = $count;
		$this->view->page = $page;
        
            // $this->view->users = $userListing;
        } // end if
		else
		{
			$message = 'account-userlist-not-logged';
            $this->flash($message, '/'.$this->view->language.'/msg/');
		}
        
        } catch (Zend_Exception $e) {
            echo $e->getMessage();
        }
    } // end of userListingAction
} // end of class
