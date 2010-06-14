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
        
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('login', 'html')->initContext();
        
        $this->view->title = 'account-title';
    } // end of init()
    
    /**
    *    profilethumbAction
    *    
    *    Gets users profile thumbnail image from database. Sets image to view with empty layout. 
    *
    */
    public function profilethumbAction()
    {
        // Set an empty layout for view
        $this->_helper->layout()->setLayout('empty');
        
        // Get requests
        $params = $this->getRequest()->getParams();
        
        $thumb = isset($params['thumb']) ? true : false;
		$thumbnail = $thumb ? 'thumbnail_usi' : 'image_usi'; 

        $image = null;
        
        if (isset($params['id'])) {
		$userid = $params['id'];

	        // Get cache from registry
        	$cache = Zend_Registry::get('cache');
        	
        	$mimeType = "image/jpeg";
        
        	// Load recent posts from cache
        	$cacheImages = 'ProfileThumbs_' . $userid . '_' . $thumbnail;
        
        	if(!$result = $cache->load($cacheImages)) {
				$user = new Default_Model_User($userid);
				$imagedata = $user->getUserImageData($userid, $thumb);
	            		
	            if($imagedata == null) {
	                $filename = '../www/images/no_profile_img_placeholder.png';
	                $handle = fopen($filename, "r");
	                $imagedata[$thumbnail] = fread($handle, filesize($filename));
	            } 

        		// Save recent posts data to cache
        		$cache->save($imagedata, $cacheImages);          
        	} else {
				$imagedata = $result;
        	}
        	
	        $this->view->mime = $mimeType;
        	$this->view->img = $imagedata[$thumbnail];
        }
    }
    
    /**
    *    indexAction
    *    
    *    Contains users account information page, accessible only by users who are logged in.
    *
    */
    public function indexAction() {
        if (Zend_Controller_Action_HelperBroker::hasHelper('redirector')) {
            $redirector = Zend_Controller_Action_HelperBroker::getExistingHelper('redirector');
        }
        
        $auth = Zend_Auth::getInstance();
		
        // if user has identity
        if ($auth->hasIdentity()) {  
            $identity = $auth->getIdentity();
			$id = $identity->user_id;
            
            $userModel = new Default_Model_User();
            $name = $userModel->getUserNameById($id);
            
            $target = $this->_urlHelper->url(array('controller' => 'account', 
                                                   'action' => 'view', 
                                                   'user' => $name,
                                                   'language' => $this->view->language),
                                             'lang_default', true);

            $redirector->gotoUrl($target);
        } else {
            $target = $this->_urlHelper->url(array('controller' => 'index', 
                                                   'action' => 'index',
                                                   'language' => $this->view->language),
                                             'lang_default', true);
            $redirector->gotoUrl($target);
        }
        
        /*
        $redirect = $this->_urlHelper->url(array('controller' => 'index', 
                                                 'action' => 'index', 
                                                 'language' => $this->view->language), 
                                           'lang_default', true);
        // Redirect user
        $this->flash('logout-succesful-msg', $redirect); 
    /*
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
            $user = new Default_Model_User();
                
            // Get users info
            $data = $user->getUserRow($id)->toArray();
            
            // Get content user has released
            $content_list = $user->getUserContent($id);
            
            // Set user data to view
            // Why is this here twice
            //$this->view->authorContents = $content_list;       
            $this->view->authorContents = $content_list;        
        } // end if
        
        // set user data to view
        $this->view->user = $data;
        */
    } // end of indexAction()
    
    /*
    *    viewAction
    *
	*    Gets user profile information, users content and comments.
    */
    public function viewAction() {
        // View is WIP because user profile edit form is not ready
// WIP START

        if (Zend_Controller_Action_HelperBroker::hasHelper('redirector')) {
            $redirector = Zend_Controller_Action_HelperBroker::getExistingHelper('redirector');
        }

        $hometargeturl = $this->_urlHelper->url(array('controller' => 'index',
                                                      'action' => 'index',
                                                      'language' => $this->view->language),
                                                'lang_default', true);

        // Get user identity
        $auth = Zend_Auth::getInstance();

        // Disable edit profile by default
        $userEdit = false;

        // Get params
        $params = $this->getRequest()->getParams();
        if (isset($params['user'])) {
            // Get username from params
            $username = $params['user'];
        } else {
            $redirector->gotoUrl($hometargeturl);
        }

        // Get content types
        $contentTypes = new Default_Model_ContentTypes();
        $this->view->content_types = $contentTypes->getAllNamesAndIds();

        // Get user data from User Model
        $user = new Default_Model_User();
        $data = $user->getUserByName($username);

        if ($data == null) {
            $redirector->gotoUrl($hometargeturl);
        }

        $this->view->user = $data;
		$id = $data['id_usr'];

        // Get public user data from UserProfiles Model
		$userProfile = new Default_Model_UserProfiles();
        $dataa = $userProfile->getPublicData($id);

        // $dataa is an array with key=>val like firstname => "Joel Peeloten"

        // This was replaced with get public data and the foreach above
        // Kept here just in case for the future
        /*
        $dataa['gender'] 		= $userprofile->getUserProfileValue($id, 'gender');
		$dataa['surname'] 		= $userprofile->getUserProfileValue($id, 'surname');
		$dataa['firstname'] 	= $userprofile->getUserProfileValue($id, 'firstname');
		$dataa['category'] 		= $userprofile->getUserProfileValue($id, 'user category');
		$dataa['profession']	= $userprofile->getUserProfileValue($id, 'profession');
		$dataa['company'] 		= $userprofile->getUserProfileValue($id, 'company');
		$dataa['biography'] 	= $userprofile->getUserProfileValue($id, 'biography');
		$dataa['city'] 			= $userprofile->getUserProfileValue($id, 'city');
		$dataa['phone'] 		= $userprofile->getUserProfileValue($id, 'phone');
		$dataa['birthday'] 		= $userprofile->getUserProfileValue($id, 'birthday');
        */

		$dataa['country'] = $userProfile->getUserProfileValue($id, 'country');

        $userCountry = new Default_Model_UserCountry();
		$dataa['country'] = $userCountry->getCountryNameById(
            $dataa['country']['profile_value_usp']
        );

        // Get content user has released
        $type = isset($params['type']) ? $params['type'] : 0 ;
        $contentList = $user->getUserContent($data['id_usr']);
        $temp = array();

        // Initialize content counts
        $dataa['contentCounts']['totalCount'] = 0;
        $dataa['contentCounts']['savedCount'] = 0;

        $dataa['contentCounts']['problem'] = 0;
        $dataa['contentCounts']['finfo'] = 0;
        $dataa['contentCounts']['idea'] = 0;

        // Count amount of content user has published
        // and check unpublished so only owner can see it.
        foreach ($contentList as $k => $c) {
            // If user not logged in and content not published,
            // remove content from list
            if (!$auth->hasIdentity() && $c['published_cnt'] == 0) {
                unset($contentList[$k]);
            // Else if user logged in and not owner of unpublished content,
            // remove content from list
            } else if ($auth->hasIdentity() &&
                       $c['id_usr'] != $auth->getIdentity()->user_id &&
                       $c['published_cnt'] == 0) {
                unset($contentList[$k]);
            // Else increase content counts and sort content by content type
            } else {
                if (isset($c['key_cty'])) {
                    // Set content to array by its content type
                    //$temp[$c['key_cty']][] = $c;
                    //$temp[] = $c;

                    // Increase total count
                    $dataa['contentCounts']['totalCount']++;

                    // Set content type count to 0 if count is not set
                    if (!isset($dataa['contentCounts'][$c['key_cty']] )) {
                        $dataa['contentCounts'][$c['key_cty']] = 0;
                    }

                    // Increase content type count
                    $dataa['contentCounts'][$c['key_cty']]++;
                }
            }

            if($c['published_cnt'] == 0) {
                $dataa['contentCounts']['savedCount']++;
            }
        } // end foreach

        // If user is logged in, and viewing self; allow edit
        if ($auth->hasIdentity()) {
            $identity = $auth->getIdentity();

            if ($data['id_usr'] == $identity->user_id) {
                $userEdit = true;
            }
        }

        if ($auth->hasIdentity() && $data['id_usr'] == $auth->getIdentity()->user_id) {
        	$favouriteModel = new Default_Model_UserHasFavourites();
        	$favouriteType = isset($params['favourite']) ? $params['favourite'] : 0;
        	$favouriteList = $user->getUserFavouriteContent($data['id_usr']);

        	// Initialize Favourite counts
        	$dataa['favouriteCounts']['totalCount'] = 0;

        	$dataa['favouriteCounts']['problem'] = 0;
        	$dataa['favouriteCounts']['finfo'] = 0;
        	$dataa['favouriteCounts']['idea'] = 0;

        	foreach($favouriteList as $k => $favourite) {
        		/*
        		 * If content Id doesn't exist anymore:
        		 * unset from Favouritelist and remove all lines from user_has_favourites table that
        		 * refers to this content id
        		 */
        		if ($favourite['id_cnt'] == '') {
                	unset($favouriteList[$k]);
                	$favouriteModel->removeAllContentFromFavouritesByContentId($favourite['id_cnt_fvr']);
            	}

        	    if (isset($favourite['key_cty'])) {

                    // Increase total count
                    $dataa['favouriteCounts']['totalCount']++;

                    // Set content type count to 0 if count is not set
                    if (!isset($dataa['favouriteCounts'][$favourite['key_cty']] )) {
                        $dataa['favouriteCounts'][$favourite['key_cty']] = 0;
                    }

                    // Increase content type count
                    $dataa['favouriteCounts'][$favourite['key_cty']]++;
                }
        	}
        	//print_r($dataa);print_r($favouriteList);die;
        }

        // Set to view
        $this->view->user_has_image = $user->userHasProfileImage($data['id_usr']);
        $this->view->userprofile = $dataa;
        $this->view->authorContents = $contentList;/*$temp*/
        //$this->view->authorFavourites = $favouriteList;
        $this->view->user_edit = $userEdit;
        $this->view->type = $type;

// WIP END

        /* Waiting for layout that is maybe coming 
        // MyViews
        $viewsModel = new Default_Model_ContentViews();
        Zend_Debug::dump($viewsModel->getUserViewedContents($data['id_usr']));
        
        // MyReaders
        Zend_Debug::dump($user->getUsersViewers($data['id_usr']));
        die;*/
        
        //$group_model = new Default_Model_UserHasGroup();
        //$usergroups = $group_model->getGroupsByUserId($id);

        //$this->view->usergroups = $usergroups;
    }
    
    /**
    *    loginAction
    *
    *    Contains login form for users. If user was redirected
    *    to login by AclManager the user is redirected back 
    *    to the page that was requested originally, 
    *    if user is already logged in redirects them to account/index page. 
    *    Writes login attemps to a log file.
    */
    public function loginAction()
    {
        // Check if user is logged in
        $auth = Zend_Auth::getInstance();
        
        // Get url helper
        $urlHelper = $this->_helper->getHelper('url');
        
        // if user is already logged in redirect away from here
        if ($auth->hasIdentity()) {    
            $target = $urlHelper->url(array('controller' => 'index', 
                                            'action' => 'index', 
                                            'language' => $this->view->language), 
                                      'lang_default', true);
                                      
            $this->_redirect($target);
        } // end if
        
        // login ajax functionality: 
        // check where user came from (and use to redirect back later)
        if(isset($_SERVER['HTTP_REFERER'])){
        	$formOptions = $_SERVER['HTTP_REFERER'];
        } else {
        	$formOptions = $urlHelper->url(array('controller' => 'index', 
                                                 'action' => 'index', 
                                                 'language' => $this->view->language), 
                                           'lang_default', true);
        }
        
        // creata new LoginForm and set to view
        $form = new Default_Form_LoginForm($formOptions);
        $this->view->form = $form;

        // Get request
        $request = $this->getRequest();
        $formData = $this->_request->getPost();
        
        // process login if request method is post
        if ($request->isPost()) {
            // Check user authentity if form data is valid
            if($form->isValid($formData)) {
                // Get username and password
                $data = $form->getValues();
                $users = new Default_Model_User;
                $result = $users->loginUser($data);

                // If user is authenticated
                if ($result == true) {
                    // Get user id
                    $id = $users->getIdByUsername($data['username']);
                    
                    // record login attempt
                    $user = new Default_Model_User($id);
                    $user->loginSuccess();
                    
                    // create identity data and write it to session
                    $identity = $user->createAuthIdentity();
                    $auth->getStorage()->write($identity);
                    
                    //echo var_dump($auth); die;
                    // send user to front page (the old method)
                    /*$redirect = $urlHelper->url(array('controller' => 'index', 'action' => 'index', 
                                                'language' => $this->view->language), 'lang_default', true);*/
					//echo $data['returnurl']; die;
                    
                    // Add login to log
                    $logger = Zend_Registry::get('logs');
                    if(isset($logger['login'])) {
                        $message = sprintf(
                            'Successful login attempt from %s user %s', 
                            $_SERVER['REMOTE_ADDR'], 
                            $identity->username
                        );
                        
                        $logger['login']->notice($message);
                    }
                    
                    $redirect = $data['returnurl'];
                    $this->_redirect($redirect);
                } else {
                    $this->view->errormsg = $this->view->translate('account-login-not-successful');
                }
            } //end if
        } // end if
    } // end of loginAction()
    
    /**
    *    openidAction
    *
    *    Blah
    */
    public function openidAction()
    {
        $auth = Zend_Auth::getInstance();
        
        // Get url helper
        $urlHelper = $this->_helper->getHelper('url');
        
        // if user is already logged in redirect away from here
        if ($auth->hasIdentity()) {    
            $target = $urlHelper->url(array('controller' => 'index', 
                                            'action' => 'index', 
                                            'language' => $this->view->language), 
                                      'lang_default', true);
            
            $this->_redirect($target);
        } // end if
        
        // if openid provider returns data
		//$status = "";
		if (isset($_POST['openid_action']) &&
		    !empty($_POST['openid_identifier'])) {
		
		    $consumer = new Zend_OpenId_Consumer();
            
		    if (!$consumer->login($_POST['openid_identifier'])) {
		    	//$status = "LOGIN FAILED";
		        $this->view->errormsg = $this->view->translate(
                    'account-openid-login-not-successful'
                );
		    }
		} else if (isset($_GET['openid_mode'])) {
		    if ($_GET['openid_mode'] == "id_res") {
		        $consumer = new Zend_OpenId_Consumer();
                
		        if ($consumer->verify($_GET, $id)) {
		        	$formOptions = htmlspecialchars($id);
					$userProfiles = new Default_Model_UserProfiles();
					$openIdResults = $userProfiles->searchUserOpenid($formOptions);
					
					// if attached openid is found
					if($openIdResults) {
						$userid = $openIdResults['id_usr_usp'];
		
		            	//$status = "VALID " . $formOptions . " / " . $userid;
		            	$user = new Default_Model_User($userid);
                    	$user->loginSuccess();
	                    $identity = $user->createAuthIdentity();
	                    $auth->getStorage()->write($identity);
                        
			            $target = $urlHelper->url(array('controller' => 'index', 
                                                        'action' => 'index', 
                                                        'language' => $this->view->language), 
                                                  'lang_default', true);
                                                  
			            $this->_redirect($target);
					} else {
						//$status = "INVALID, NO ATTACHED OPENID FOUND FOR " . $formOptions;
						$this->view->errormsg = $this->view->translate(
                            'account-openid-login-not-successful'
                        );
					}
		        } else {
		            //$status = "INVALID " . htmlspecialchars($id);
		            $this->view->errormsg = $this->view->translate(
                        'account-openid-login-not-successful'
                    );
		        }
		    } else if ($_GET['openid_mode'] == "cancel") {
		        //$status = "CANCELLED";
		        $this->view->errormsg = $this->view->translate(
                    'account-openid-login-not-successful'
                );
		    }
		}
        
		//echo $status;
		$form = new Default_Form_OpenIDLoginForm();
		$this->view->form = $form;
    }

    /**
    *    logoutAction
    *
    *    Logout the user and redirect to home page.
    */
    public function logoutAction()
    {
        // Clear users session data
        Zend_Auth::getInstance()->clearIdentity();
        $redirect = $this->_urlHelper->url(array('controller' => 'index', 
                                                 'action' => 'index', 
                                                 'language' => $this->view->language), 
                                           'lang_default', true);
        
        // Redirect user
        $this->flash('logout-succesful-msg', $redirect);    
    } // end of logoutAction()

    /**
    * registerCompleteAction
    * aka. user registration phase 2
    *
    * @author joel peltonen
    */
    public function registercompleteAction() 
    {
        $auth = Zend_Auth::getInstance();
        
        if (!$auth->hasIdentity()) { 
            $urlHelper = $this->_helper->getHelper('url');
            
            $target = $urlHelper->url(array('controller' => 'index', 
                                            'action' => 'index', 
                                            'language' => $this->view->language), 
                                      'lang_default', true);
            $this->_redirect($target);
        }
        
        // Create new registration form
        $form = new Default_Form_RegistercompleteForm();
        $this->view->form = $form;  

        /*
        // Get requests
        $request = $this->getRequest();
        
        // If form is POST, get and validate form data
        if ($request->isPost()) {
        
            $formData = $this->_request->getPost();
            
            // If form data is valid, handle data
            if ($form->isValid($formData)) {
                // save profile data
            }
        }
        */
    }
    
    /**
    * registerAction
    *
    * User registration page and post-validation actions
    *
    * @author Joel Peltonen
    * @author ...?
    */
    public function registerAction()
    {
        // if user is logged in, redirect away
        $auth = Zend_Auth::getInstance();
        
        if ($auth->hasIdentity()) { 
            $urlHelper = $this->_helper->getHelper('url');
            
            $target = $urlHelper->url(array('controller' => 'index', 
                                            'action' => 'index', 
                                            'language' => $this->view->language), 
                                      'lang_default', true);
                                      
            $this->_redirect($target);
        }
    
        // Create new registration form
        $form = new Default_Form_RegistrationForm();
        $this->view->form = $form;    
        
        // Get requests
        //$request = $this->getRequest();
        
        // get and validate form data
        if ($this->_request->isPost()) {
        
            $formData = $this->_request->getPost();

            // If form is valid, handle database insertions 
            // Else form population (automatic)
            if ($form->isValid($formData)) {
                // user data handling
                $user = new Default_Model_User();
                
                if (!$user->registerUser($formData)) {
                    $redirect = $this->_urlHelper->url(array('controller' => 'msg', 
                                                             'action' => 'index', 
                                                             'language' => $this->view->language), 
                                                       'lang_default', true);
                                                       
                    $this->flash('registration-usermodel-data-procesing-failure', $redirect);
                }
                
                // Add register to log
                $logger = Zend_Registry::get('logs');
                if(isset($logger['register'])) {
                    $message = sprintf(
                        'Successful register attempt from %s user %s', 
                        $_SERVER['REMOTE_ADDR'], 
                        $formData['username']
                    );
                    
                    $logger['register']->notice($message);
                }
                
                // Fetch user id
                $uid = $user->getIdByUsername($formData['username']);
                
                $userProfiles = new Default_Model_UserProfiles();
                $userProfiles->setUserEmployment($uid, $formData,0);
                $userProfiles->setUserCity($uid, $formData, 1);
                
                // check if user is logged in
        		$auth = Zend_Auth::getInstance();
                
        		$username = $formData['username'];
                $password = $formData['password']; 
        		
                // $model = new Default_Model_User();
                $id = $user->getIdByUsername($username);
      
                $user = new Default_Model_User($id);
                $result = $user->loginUser($formData);
                
                // the logging in worked;
                if ($result == true) {
                    // Get user id
                    //$id = $adapter->getResultRowObject()->id_usr;
                    
                    // record login attempt
                    $user->loginSuccess();
                    
                    // create identity data and write it to session
                    $identity = $user->createAuthIdentity();
                    $auth->getStorage()->write($identity);
                    
                    // Add login to log
                    $logger = Zend_Registry::get('logs');
                    if(isset($logger['login'])) {
                        $message = sprintf(
                            'Successful login attempt from %s user %s', 
                            $_SERVER['REMOTE_ADDR'], 
                            $identity->username
                        );
                        
                        $logger['login']->notice($message);
                    }
                    
                    // send phase 2 page
                    $urlHelper = $this->_helper->getHelper('url');
                    
                    $redirect = $urlHelper->url(array('controller' => 'account', 
                                                      'action' => 'registercomplete', 
                                                      'language' => $this->view->language), 
                                                'lang_default', true);
                      
                    $this->_redirect($redirect);
                } else { 
                    // logging in failed
                    $this->view->errormsg = $this->view->translate(
                        'account-login-not-successful'
                    );
                }      
            }
        }
    }
    
    /**
    *    captchaAction
    *
    *    Create captcha
    */
    public function captchaAction()
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
        $font = imageloadfont("images/anonymous.gdf");
        
        // define colors used in image
        $white = ImageColorAllocate($image, 255, 255, 255);
        $blue = ImageColorAllocate($image, 51, 102, 153);
        $green = ImageColorAllocate($image, 112, 191, 12);
        $black = ImageColorAllocate($image, 0, 0, 0);
        $grey = ImageColorAllocate($image, 150, 150, 150);

        //Make the background blue
        ImageFill($image, 0, 0, $blue); 
        
        //Generate random(ish) position for image text
        $text_x = mt_rand(-2, 30);
        $text_y = mt_rand(-2, 10);
        
        //Add randomly generated string in white to the image
        
        ImageString($image, $font, $text_x, $text_y, strtoupper($security_code), $white); 

        //Throw in some lines to make it a little bit harder for any bots to break
        $s = ($width*$height)/500;
		
        for($i=0; $i < $s; $i++) {
            imageline($image, mt_rand(0, $width), mt_rand(0, $height), mt_rand(0, $width), mt_rand(0, $height), $grey);
        } // end for

        // borders for captcha; syntax is (image, Xstart, Ystart, Xend, Yend, color)
        /*
        imageline($image, 0,        0,          $width,     0,          $blue); //topl-topr
        imageline($image, 0,        $height-1,  $width,     $height-1,  $blue); //btml-btmr
        imageline($image, 0,        0,          0,          $height,    $blue); //topl-btml
        imageline($image, $width-1, 0,          $width-1,   $height,    $blue); //topr-btmr
        */
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
    *    fetch forgotten password page: 
    *    Users can request a password reset by givin their username, 
    *    a new password is created and sent to user email address.
    *    Users must also activate the new password on this page 
    *    by clicking the activation link in the email.
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
                    $user = new Default_Model_User();
                    
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
                $user = new Default_Model_User();
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
    * settingsAction: users edit their information
    * accessible only to users who are logged in
    *
    * @author tuomas valtanen
    * @author joel peltonen
    */
    function settingsAction() {
        // get authentication instance
        $auth = Zend_Auth::getInstance();
		
        // if user has identity
        if ($auth->hasIdentity()) {    
			// retrieve user id from the identity
			$identity = $auth->getIdentity();
			$id = $identity->user_id;
			
			// generate the form for user settings
            $form = new Default_Form_AccountSettingsForm();
            
            // send form to view
            try {
                $this->view->form = $form;
            } catch(Zend_Exception $e) { 
                // this should be replaced by throwing a general 500 error
                echo '<pre>Unknown server error occurred! Please try later </pre>';
            }
			
			// get user data
			$userInfos = new Default_Model_UserProfiles();
			$settingsData = $userInfos->getUserInfoById($id);

            // get user email and push to settingsData
            $userModel = new Default_Model_User($id);
            $email = $userModel->getUserEmail($id);
            
            $settingsData['email'] = $email;
            $settingsData['confirm_email'] = $email;
            $settingsData['username'] = $identity->username;
            
            // Get users email notifications and push to settingsdata in correct form
            $notificationsModel = new Default_Model_Notifications(); 
            $notifications = $notificationsModel->getNotificationsById($id);
			$settingsData['notifications'] = array();
            foreach ($notifications as $id_ntf => $notification) {
            		array_push($settingsData['notifications'], $id_ntf); 
            }
            
            // populate form
			if(isset($settingsData)) {
                //echo '<pre>'; var_dump($settingsData);
				$form->populate($settingsData);
			}
			
			// If request is post
			//$request = $this->getRequest();
			if($this->_request->isPost()) {
       
                // get form data
				$formdata = $this->_request->getPost();
                
				if($form->isValid($formdata)) {
                    // if form is valid
                    // Updates checked notifications

                    //echo "<pre>"; var_dump($formdata);
                    $notificationsModel->setUserNotifications($id, $formdata['notifications']);

                    $userProfile = new Default_Model_UserProfiles();
                    $userProfile->setProfileData($id, $formdata);

                    $user = new Default_Model_User($id);

                    // Updates email
                    if(strlen($formdata['email']) != 0) {
                        $user->changeUserEmail($id, $formdata['email']);
                    }

                    // Updates the password
                    if(strlen($formdata['password']) != 0) {
                        $user->changeUserPassword($id, $formdata['password']);
                    }

                    // Redirects the user to a page that shows the update complete
                    $redirect = $this->_urlHelper->url(array('controller' => 'account',
                                                             'action' => 'settings',
                                                             'language' => $this->view->language),
                                                       'lang_default', true);
                    $this->flash('Information has been changed.', $redirect);
				} else {
                    // Formdata is not valid, do nothing -- here for possible debugging
					// echo $form->getErrors();
					// echo $form->getMessages();
				}
			} else {
                // request is not post, do nothing
			}
        } else {
            // user has no identity -- Get url helper and redirect away
            $urlHelper = $this->_helper->getHelper('url');
            
            $target = $urlHelper->url(array('controller' => 'index', 
                                            'action' => 'index', 
                                            'language' => $this->view->language), 
                                      'lang_default', true);
                                     
            $this->_redirect($target);
        } 
    }
    
    /*
    *   userListingAction
    *
    *   Gets listing of all users.
    */
    function userlistAction()
    {
        // assuming that the CleanQuery plugin has already stripped empty parameters
        if (isset($_GET) && is_array($_GET) && !empty($_GET)) {
            $path = '';
            array_walk($_GET, array('AccountController', 'encodeParam'));
            
            foreach ($_GET as $key => $value) {
                if ($key != 'filter' && $key != 'submit_user_filter')
                    $path .= '/' . $key . '/' . $value;
            }
            
            $uri = $_SERVER['REQUEST_URI'];
            $path = substr($uri, 0, strpos($uri, '?')) . $path;
            $this->getResponse()->setRedirect($path, $this->_permanent ? 301 : 302);
            $this->getResponse()->sendResponse();
            return;
        }

        // Get requests
        $params = $this->getRequest()->getParams();
        
        // Get page nummber and items per page
        $page = isset($params['page']) ? $params['page'] : 1;
        $count = isset($params['count']) ? $params['count'] : 10;
        $order = isset($params['order']) ? $params['order'] : null;
        $list = isset($params['list']) ? $params['list'] : null;
        
        if($order == "username") $order = "usr.login_name_usr"; 
        elseif($order == "joined") $order = "usr.created_usr";
        elseif($order == "login") $order = "usr.last_login_usr";
        elseif($order == "content") $order = "contentCount";
        else $order = null;

        if($list != "asc" && $list != "desc") $list = null;
        
        if(isset($order) && isset($list)) {
        	$sort = $order." ".$list;
        }
        
        // Filter form data
        $formData['username'] = isset($params['username']) ? $params['username'] : '';
        $formData['city'] = isset($params['city']) ? $params['city'] : '';
        //$formData['country'] = isset($params['country']) ? $params['country'] : 0;    
        $formData['contentlimit'] = isset($params['contentlimit']) ? $params['contentlimit'] : null;
        $formData['counttype'] = isset($params['counttype']) ? $params['counttype'] : 0;
        
        // Get country listing
        $userCountry = new Default_Model_UserCountry();
        $formData['countryList'] = $userCountry->getCountryList();
        
        // Reorder country listing and add all countries option
        $temp[0] = $this->view->translate('userlist-filter-country-all');
        
        foreach($formData['countryList'] as $k => $v) {
            $temp[$v['id_ctr']] = $v['name_ctr'];
        }
        
        $formData['countryList'] = $temp;
        
        //Set array patterns
        $pat_sql = array("%","_");
        $pat_def = array("*","?");
        
        //Replace * and ? characters  
        $formData['username'] = str_replace($pat_def,$pat_sql,$formData['username']);
        $formData['city'] = str_replace($pat_def,$pat_sql,$formData['city']);
        
        // Get user listing
        $user = new Default_Model_User();
        $userListing = $user->getUserListing($formData, $page, $count, $sort);

        $userIdList = array();
        foreach($userListing as $u) {
        	array_push($userIdList,$u['id_usr']); 
        }
        // Get total content count
        $userCount = $user->getUserCountBySearch($formData);
        
        // Calculate total page count
        $pageCount = ceil($userCount / $count);
                
        // User list search form
        $userSearch = new Default_Form_UserListSearchForm(null, $formData);
        
        $url = $this->_urlHelper->url(array('controller' => 'account', 
                                            'action' => 'userlist',
                                            'language' => $this->view->language),
                                      'lang_default', true); 
          
        $userSearch->setAction($url)
                   ->setMethod('get');
        
        $this->view->userSearch = $userSearch;
        
        // Custom pagination to fix memory error on large amount of data
        $paginator = new Zend_View();
        $paginator->setScriptPath('../application/views/scripts');
        $paginator->pageCount = $pageCount;
        $paginator->currentPage = $page;
        $paginator->pagesInRange = 10;
        
        /*
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
        */
        
        // Set to view
        $this->view->userPaginator = $paginator;
        $this->view->userListData = $userListing;
        $this->view->userList = $userIdList;
        $this->view->count = $count;
        $this->view->userCount = $userCount;
        $this->view->page = $page;

    } // end of userListingAction
    
    /**
    *   imagesAction
    *
    *   User images
    *
    */
    public function imagesAction()
    {
        // Get authentication
        $auth = Zend_Auth::getInstance();
		
        // If user has identity
        if ($auth->hasIdentity()) { 
            $id = $auth->getIdentity()->user_id;
            
            $model = new Default_Model_UserImages;            
            $images = $model->getImagesByUsername($id);
           
            if(count($images) > 0) {
                for($a = 0; $a < count($images); $a++) {
                    $dates[$a] = $images[$a]['modified_usi'];
                }
                
                $active = array_search(max($dates), $dates);
                $images[$active]['status'] = 1;
                $this->view->image_ids = $images;
            }
            
            if(count($images) < 4) {
                $form = new Default_Form_ProfileImageForm();
                $this->view->form = $form;
                $request = $this->getRequest();
                
                if($this->_request->isPost()) {
                    $formData = $this->_request->getPost();
                    
                    if($form->isValid($formData)) {
                		//Update the user's image
                        $location = $form->image->getFileName();
						
                        // Check if user wants to add a new image
                        if (!empty($location)) {
                        	$user_id = $auth->getIdentity()->user_id;
                            
							// Move the uploaded file to temp
	                        $tmpfile = $_FILES['image']['tmp_name'];
							$uploaddir = "temp/";
							$tmpname = "prethumb_" . $user_id;
                            
							if(move_uploaded_file($tmpfile, $uploaddir.$tmpname)) {
	                            $redirect = $this->_urlHelper->url(array('controller' => 'account',
                                                                         'action' => 'processimage',
                                                                         'language' => $this->language),
                                                                   'lang_default', true);
	                            $this->_redirect($redirect);  
							} else {
								// Bitch about something because file cannot be moved...
								echo "<p>Fail :(</p>";
							}
							
                            //Image -stuff is broken...
                            //$image= new Default_Model_UserImages();
                            //$image->newUserImage($id, $outputfile);
                         
                        }
                     }
                 }
            }
            else
            $this->view->form = 'You need to delete at least one profile image in order to add new ones!';
        }
        else 
        {
            $redirect = $this->_urlHelper->url(array('controller' => 'msg', 
                                                     'action' => 'index', 
                                                     'language' => $this->view->language), 
                                               'lang_default', true);
            $this->_redirect($redirect);    
        }
    }

    /**
     * processimageAction
     * 
	 * Uploaded thumbnail image's resize and cropping.
     */
    public function processimageAction()
    {
		// Get authentication
		$auth = Zend_Auth::getInstance();
		
		// If user has identity
		if ($auth->hasIdentity()) { 
			// Get user ID
			$id = $auth->getIdentity()->user_id;
			
			// Get requests
			$params = $this->getRequest()->getParams();
            
            $imagepath = $this->_urlHelper->url(array('controller' => 'account',
                                                     'action' => 'showimage',
            										 'prethumb' => '1',
                                                     'language' => $this->view->language),
                                                'lang_default', true);
                                                
            // Prevent browsers from caching the image by adding a random number to the url                                    
			$this->view->prethumb = $imagepath . "/" . rand(0,9999);	
			
			$form = new Default_Form_ProcessImageForm();
			$this->view->form = $form;
			
			if($this->_request->isPost()) {
				// Get POST-data
				$postdata = $this->_request->getPost();
				$newcoord_x = $postdata['c_x'];
				$newcoord_y = $postdata['c_y'];
				$newcoord_w = $postdata['c_w'];
				$newcoord_h = $postdata['c_h'];

				// Local paths to files
                $tmpfile = "temp/prethumb_" . $id;
                $rdyfile = $tmpfile . "-ready";

                // Find out what type of image we are dealing with
				switch(exif_imagetype($tmpfile)) {
					case IMAGETYPE_GIF:
						$img_r = imagecreatefromgif($tmpfile);
						break;
					case IMAGETYPE_JPEG:
						$img_r = imagecreatefromjpeg($tmpfile);
						break;
					case IMAGETYPE_PNG:
						$img_r = imagecreatefrompng($tmpfile);
						break;
				}

				// Set some properties...
				$targ_w = $targ_h = 180;
				$jpeg_quality = 90;
			
				$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

				imagecopyresampled($dst_r,$img_r,0,0,$newcoord_x,$newcoord_y,
				$targ_w,$targ_h,$newcoord_w,$newcoord_h);
				
				// Save to temp
				imagejpeg($dst_r,$rdyfile,$jpeg_quality);
	            
				// Save the thumbnail in the DB
				$saveimage = new Default_Model_UserImages();
				$saveimage->newUserImage($id, $tmpfile, $rdyfile);
				
				// All the dirty work is done, time for cleanup!
				
				// Delete the temporary files
	            unlink($tmpfile);
	            unlink($rdyfile);
                
	            // Free up memory
	            imagedestroy($img_r);
	            imagedestroy($dst_r);
 
				// Get us outta here
	            $redirect = $this->_urlHelper->url(array('controller' => 'account',
	                                                     'action' => 'view',
	                                                     'user' => $auth->getIdentity()->username,
	                                                     'language' => $this->language),
	                                               'lang_default', true);
	            $this->_redirect($redirect);  
	            
			}	
		}
	}
	
    /**
    *   showimageAction
    *
    *	@params	img			int			image id
    *	@params thumb		boolean		outputs either thumbnail or original image
    *	@params prethumb	boolean		outputs the uncut thumbnail (used in processimageAction)
    *
    */
    public function showimageAction()
    {
        // Set an empty layout for view
        $this->_helper->layout()->setLayout('empty');
        
        // Get requests
        $params = $this->getRequest()->getParams(); 
        
        $this->view->image = null;
        
        $auth = Zend_Auth::getInstance();
		
        // If user has identity
        if ($auth->hasIdentity()) { 
            if($params['prethumb'] == true) {
            	// Get user id
            	$user_id = $auth->getIdentity()->user_id;
                
				// Get the right file path
            	$filename = 'temp/prethumb_' . $user_id;
                
            	// Get the image contents 
            	$contents = file_get_contents($filename);
                
            	// And push it to view
            	if($contents != null) {
            		$this->view->img = $contents;
            	}
            } else {
	            $id = $params['img'];
                
	            // Get thumbnail data from database
	            $user = new Default_Model_UserImages();
	            $image = $user->getImageById($id);

	            // If image data is not null, set imagedata and filetype to view.
	            if($image != null) {
	            	if($params['thumb'] == true)
	            		$this->view->img = $image['thumbnail_usi'];
	            	else
	                	$this->view->img = $image['image_usi'];   
	            } // end if		
            }
            
        } // end if		
    } // end of showimageAction()
    
    /**
    *   deleteimageAction
    *
    *
    *
    */
    public function deleteimageAction()
    {
        // Get requests
        $params = $this->getRequest()->getParams(); 
        $id = $params['img_id'];
        $auth = Zend_Auth::getInstance();
		
        // If user has identity
        if ($auth->hasIdentity()) {
              $model = new Default_Model_UserImages();
              $model->deleteImageById($id);
              
              $url = $this->_urlHelper->url(array('controller' => 'account',
                                                  'action' => 'images',
                                                  'language' => $this->language),
                                            'lang_default', true);
              $this->_redirect($url);
        } else {
            $this->_redirect($this->_baseUrl);
        }
    }
    
    /**
    *   Activate image action
    */
    public function activateimageAction()
    {
        // Get requests
        $params = $this->getRequest()->getParams(); 
        $id = $params['img_id'];
        $auth = Zend_Auth::getInstance();
		
        // If user has identity
        if ($auth->hasIdentity()) {
              $model = new Default_Model_UserImages();
              $model->updateModDate($id);
              
              $url = $this->_urlHelper->url(array('controller' => 'account',
                                                  'action' => 'images',
                                                  'language' => $this->language),
                                            'lang_default', true);
              $this->_redirect($url);
        } else {
            $this->_redirect($this->_baseUrl);
        }
    }    
} // end of class
