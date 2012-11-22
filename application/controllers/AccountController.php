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
 *
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
        Zend_Layout::getMvcInstance()->setLayout('layout_public');
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
			$user = new Default_Model_User($userid);

			$gravatar = $user->getGravatarStatus($userid);

			if($gravatar == 0) {

		        // Get cache from registry
	        	$cache = Zend_Registry::get('cache');

	        	$mimeType = "image/jpeg";

	        	// Load recent posts from cache
	        	$cacheImages = 'ProfileThumbs_' . $userid . '_' . $thumbnail;

	        	if(!$result = $cache->load($cacheImages)) {

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

	        elseif ($gravatar == 1) {
	        	$gravatarUrl = "http://www.gravatar.com/avatar/".md5(strtolower($user->getUserEmail($userid)))."?s=200";
	        	$this->_redirect($gravatarUrl);
	        }
	        else {
                $filename = '../www/images/no_profile_img_placeholder.png';
                $handle = fopen($filename, "r");
                $imagedata[$thumbnail] = fread($handle, filesize($filename));
                $mimeType = "image/jpeg";
                $this->view->mime = $mimeType;
	        	$this->view->img = $imagedata[$thumbnail];
	         }

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
        $this->addFlashMessage('logout-succesful-msg', $redirect);
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

		$topListClasses = $user->getUserTopList();
	    $topListUsers = $topListClasses['Users'];

        if($id != 0) $topListUsers->addUser($id);
		$topList = $topListUsers->getTopList();

        // Get public user data from UserProfiles Model
		$userProfile = new Default_Model_UserProfiles();
        $dataa = $userProfile->getPublicData($id);
        if (isset($dataa['biography'])) $dataa['biography'] = str_replace("\n", '<br>', $dataa['biography']);

        // User weblinks
        $userWeblinksModel = new Default_Model_UserWeblinks();
        $dataa['userWeblinks'] = $userWeblinksModel->getUserWeblinks($id);
        $i = 0;
        foreach($dataa['userWeblinks'] as $weblink) {
            if (strlen($weblink['name_uwl']) == 0 || strlen($weblink['url_uwl']) == 0) {
                unset($dataa['userWeblinks'][$i]);
            }
            $i++;
        }

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

        // No countries in countries_ctr and not very good table at all?
        // This would be better: http://snipplr.com/view/6636/mysql-table--iso-country-list-with-abbreviations/
        /*
		$dataa['country'] = $userProfile->getUserProfileValue($id, 'country');

        $userCountry = new Default_Model_UserCountry();
		$dataa['country'] = $userCountry->getCountryNameById(
            $dataa['country']['profile_value_usp']
        );
        */
        // Get content user has released
        $type = isset($params['type']) ? $params['type'] : 0 ;

        $temp = array();

        // Initialize content counts
        $dataa['contentCounts']['all'] = 0;
        $dataa['contentCounts']['user_edit'] = 0;

        $dataa['contentCounts']['problem'] = 0;
        $dataa['contentCounts']['finfo'] = 0;
        $dataa['contentCounts']['idea'] = 0;

        // Count amount of content user has published
        // and check unpublished so only owner can see it.
        $cntModel = new Default_Model_Content();
        $contentList = array();
        foreach ($user->getUserContent($data['id_usr'], array('order' => 'DESC')) as $k => $c) {
            // If user not logged in and content not published,
            // remove content from list
            if (!$auth->hasIdentity() && $c['published_cnt'] == 0) {
                //unset($contentList[$k]);
            // Else if user logged in and not owner of unpublished content,
            // remove content from list
            } else if (isset($c['id_usr']) && $auth->hasIdentity() &&
                       $c['id_usr'] != $auth->getIdentity()->user_id &&
                       $c['published_cnt'] == 0) {
                //unset($contentList[$k]);
            // Else increase content counts and sort content by content type
            } else {
                if (isset($c['key_cty'])) {
                    // Set content to array by its content type
                    //$temp[$c['key_cty']][] = $c;
                    //$temp[] = $c;

                    // Increase total count
                    $dataa['contentCounts']['all']++;

                    // Set content type count to 0 if count is not set
                    if (!isset($dataa['contentCounts'][$c['key_cty']] )) {
                        $dataa['contentCounts'][$c['key_cty']] = 0;
                    }

                    // Increase content type count
                    $dataa['contentCounts'][$c['key_cty']]++;
                }
                if($c['published_cnt'] == 0) {
             	   $dataa['contentCounts']['user_edit']++;
            	}
            	$c['hasCntLinks'] = $cntModel->hasCntLinks($c['id_cnt']);
          		$c['hasCmpLinks'] = $cntModel->hasCmpLinks($c['id_cnt']);
            	$contentList[] = $c;
            }

        } // end foreach

        // If user is logged in, and viewing self; allow edit
        if ($auth->hasIdentity()) {
            $identity = $auth->getIdentity();

            if ($data['id_usr'] == $identity->user_id) {
                $userEdit = true;
            }
        }

       // if ($auth->hasIdentity() && $data['id_usr'] == $auth->getIdentity()->user_id) {
        	$myFavourites = $this->getFavouriteRows($data['id_usr']);
        	//print_r($dataa);print_r($favouriteList);die;
        //}
        //Zend_Debug::dump("" === null);
		//Zend_Debug::dump($dataa['contentCounts']['idea']);
		//Zend_Debug::dump($dataa['contentCounts']['idea'] == "");
		//die;
        //	My Posts box data
		$box = new Oibs_Controller_Plugin_AccountViewBox();

		$box->setHeader("My Posts")
			->setClass("right")
			->setName("my-posts")
			->addTab("All", "all", "all selected", $dataa['contentCounts']['all']) //Header, type, calss, extra
			->addTab("Challenges", "problem", "challenges", $dataa['contentCounts']['problem'])
			->addTab("Ideas", "idea", "ideas", $dataa['contentCounts']['idea'])
			->addTab("Visions", "finfo", "visions", $dataa['contentCounts']['finfo']);
		//Zend_Debug::dump($dataa); die;
		if ($dataa['contentCounts']['user_edit'] && $userEdit) {
			$box->addTab("Saved", "user_edit", "saved", $dataa['contentCounts']['user_edit']);
		}
		$boxes[] = $box;

		$box = new Oibs_Controller_Plugin_AccountViewBox();
		$box->setHeader("My Groups")
			->setClass("left")
			->setName("my_groups")
			->addTab("All", "all", "all selected");
		$boxes[] = $box;

		$views = new Default_Model_ContentViews();
		$myViews = $this->getViewRows($data['id_usr']);
		$myViews = array_merge($myViews,$myFavourites['contents']);
		//print_r($myFavourites);die;
		//print_r($myViews);die;
		$box = new Oibs_Controller_Plugin_AccountViewBox();
		$box	->setHeader("My Views & Favourites")
				->setName("my-views")
				->setClass("right")
				->addTab("Views", "views", "views selected")
				->addTab("Favourites","problem","fvr_problem fvr_idea fvr_finfo",$myFavourites['counts']['total'])
				//->addTab("Updated","updated","fvr_updated",$myFavourites['counts']['updated'])
				;

		$boxes[] = $box;

		$myReaders = $user->getUsersViewers($data['id_usr']);
		$box = new Oibs_Controller_Plugin_AccountViewBox();
		$box->setHeader("My Readers")
			->setClass("left")
			->setName("my-reads")
			->addTab("Readers", "readers", "all selected");

		$boxes[] = $box;

		/*Box for user profile custom layout settings*/
		$box = new Oibs_Controller_Plugin_AccountViewBox();
		$box->setHeader("Custom Layout")
			->setClass("wide")
			->setName("my-custom-layout")
			->addTab("Customize", "fonts", "all selected") //Header, type, class, extra
			/*->addTab("Colors", "colors", "colors")
			->addTab("Background", "background", "background")*/;
		//$boxes[] = $box;

		$customLayoutForm = new Default_Form_AccountCustomLayoutSettingsForm();
        // Set to view

		// Comment module
        $comments = new Oibs_Controller_Plugin_Comments("account", $id);
		$this->view->jsmetabox->append('commentUrls', $comments->getUrls());
        // enable comment form
		if ($auth->hasIdentity()) $comments->allowComments(true);
		$comments->loadComments();

        $this->view->user_has_image = $user->userHasProfileImage($data['id_usr']);
        $this->view->userprofile = $dataa;
        $this->view->comments = $comments;
        $this->view->authorContents = $contentList;/*$temp*/
        $this->view->boxes = $boxes;
        $this->view->myViews = $myViews;
        $this->view->myReaders = $myReaders;
        $this->view->user_edit = $userEdit;
        $this->view->topList = $topList;
        $this->view->type = $type;
        $this->view->customLayoutSettingsForm = $customLayoutForm;

        $group_model = new Default_Model_UserHasGroup();
        $usergroups = $group_model->getGroupsByUserId($id);

        $this->view->usergroups = $usergroups;
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
	    $return_url = $this->_getParam('login_returnurl') ?: '/' . $this->view->language . '/content/feed';

	    if ($this->getIdentity()) {
		    $this->_forward('feed', 'content');
	    }

        // create a new login form and inject it into the view

        $form = new Default_Form_LoginForm();
	    $form->getElement('login_username')->placeholder = '';
	    $form->getElement('login_password')->placeholder = '';
        $this->view->form = $form;

	    if (!$this->getRequest()->isPost()) {
		    return;
	    }

	    // the form has already been submitted -> validate it

	    $form->populate($this->getRequest()->getPost());
	    $form_data = $form->getValues();

	    if(!$form->isValid($form_data)) {
		    return;
	    }

	    // form values are valid -> try to perform a login

		$users = new Default_Model_User;
		$result = $users->loginUser($form_data);

	    if (!$result) {
		    $form->getElement('login_password')->addError('account-login-not-successful');
		    return;
	    }

	    // login was successful -> store the identity in the session

	    $id = $users->getIdByUsername($form_data['login_username']);
	    $user = new Default_Model_User($id);
	    $user->loginSuccess();

	    $identity = $user->createAuthIdentity();
	    $this->setIdentity($identity);

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

	    $this->_redirect($return_url);
    }

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
        // if user is already logged in redirect away from here
        if ($auth->hasIdentity()) {
            $this->_forward('view', 'account');
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
        $this->addFlashMessage('account-logout-succesful-msg', $redirect);
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

        // if user is already logged in redirect away from here
        if ($auth->hasIdentity()) {
            $this->_forward('view', 'account');
        } // end if

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
        // if user is already logged in redirect away from here
        if ($this->getIdentity()) {
            $this->_forward('view', 'account');
        }

        // Create new registration form
        $form = new Default_Form_RegistrationForm();
	    $this->view->form = $form;

	    if (!$this->getRequest()->isPost()) {
		    return;
	    }

        $form_data = $this->getRequest()->getPost();
        $form->populate($form_data);

        if (!$form->isValid($form->getValues())) {
	        return;
        }

	    // user data handling
	    $user = new Default_Model_User();

	    if (!$user->registerUser($form_data)) {
		    $redirect = $this->_urlHelper->url(array('controller' => 'msg',
				    'action' => 'index',
				    'language' => $this->view->language),
			    'lang_default', true);

		    $this->addFlashMessage('registration-usermodel-data-procesing-failure', $redirect);
	    }

	    // Add register to log
	    $logger = Zend_Registry::get('logs');
	    if(isset($logger['register'])) {
		    $message = sprintf(
			    'Successful register attempt from %s user %s',
			    $_SERVER['REMOTE_ADDR'],
			    $form_data['username']
		    );

		    $logger['register']->notice($message);
	    }

	    // Fetch user id
	    $uid = $user->getIdByUsername($form_data['register_username']);

	    $userProfiles = new Default_Model_UserProfiles();
	    $userProfiles->setUserEmployment($uid, $form_data, 0);
	    $userProfiles->setUserCity($uid, $form_data, 1);

	    // check if user is logged in
	    $auth = Zend_Auth::getInstance();

	    $username = $form_data['register_username'];
	    $password = $form_data['register_password'];

	    // $model = new Default_Model_User();
	    $id = $user->getIdByUsername($username);

	    $user = new Default_Model_User($id);
	    $result = $user->loginUser(array(
		    'login_username' => $username,
		    'login_password' => $password,
	    ));

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

		    $redirect = $urlHelper->url(array(
				    'controller' => 'content',
				    'action' => 'feed',
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
	 * Generate Api key -action
	 *
	 */
	public function apikeyAction()
	{
		$auth = Zend_Auth::getInstance();
		$hasApiKey = false;
		$apiKey = null;
		if($auth->hasIdentity())
		{
			$id = $auth->getIdentity()->user_id;
			$model = new Default_Model_UserApiKey();
			$hasApiKey = $model->hasApiKey($id);
			if(!$hasApiKey && $this->_hasParam('generate'))
			{
				$apiKey = $model->addApiKey($id, true);
				$hasApiKey = true;
			}
			else
			{
				$apiKey = $model->getApiKeyById($id);
			}
		}
		$this->view->hasApiKey = $hasApiKey;
		$this->view->apikey = $apiKey;
	}

    /**
    *    fetch forgotten password page:
    *    Users can request a password reset by givin their username.
    *    They will be sent an email with a verification link to a page,
    *    where they have to enter and confirm a new password.
    *
    *    @param    String        $action         Defines wheather the user is asking for reset or activating the new password
    *    @param    int           $key            Md5 hash to confirm that user is following the link in activation email
    */
    public function fetchpasswordAction()
    {
        // if a user's already logged in, send them to their account home page
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $target = $this->_urlHelper->url(array('controller' => 'index',
                                                   'action' => 'index',
                                                   'language' => $this->view->language),
                                                   'lang_default', true);
            $this->_redirect($target);
        }

        // get POST and GET parameters if there are any
        $action = $this->getRequest()->isPost() ? 'submit' : $this->getRequest()->getQuery('action');
        $submittedForm = $this->getRequest()->getPost('submittedform');
        $key = $this->getRequest()->getParam('key');
        $error = null;

        /** check in what stage the process of password reset is
            (according to variables $action, $_POST['passwordgiven'] and $_GET['key']) **/

        // the user came here for the first time
        $form = new Default_Form_FetchPasswordForm();


        // if first action (so no key submitted)
        if ($action == '' && $submittedForm == '' && $key == '') {
            $this->view->form = $form;
        }
        // submitted first form for account validation
        // send Verificatoin Email and generate Key (hash)
        else if ($action == 'submit' && $submittedForm == 'fetchpassword') {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {

                $user = new Default_Model_User();

                // get user's email and id
                $email = trim($this->getRequest()->getPost('email'));
                $userId = $user->getIdByEmail($email);

                // if the email address was valid
                if ($userId != null) {
                    // create verification key and it's md5 hash
                    $key = $user->generateSalt(30);
                    $key_safe = md5($key);

                    // generate URL for the verification link
                    $path = explode('/', $_SERVER['SERVER_PROTOCOL']);
                    $url = strtolower(trim(array_shift($path))) .
                        '://' . $_SERVER['HTTP_HOST'];
                    $url .= $this->_urlHelper->url(array('controller' => 'account',
                            'action' => 'fetchpassword',
                            'language' => $this->view->language),
                        'lang_default', true);
                    $url .= '?key=' . $key;

                    // add new password request into the database
                    $user->addPasswordRequest($userId, $key_safe);

                    //var_dump($userId, $email, $url, $this->view->language->toString());
                    //exit;

                    // send verification email
                    if ($user->sendVerificationEmail($userId, $email, $url, $this->view->language)) {
                        $action = 'emailsent';
                        $this->getFlashMessenger()->addMessage('account-fetchpassword-verification-email-sent-message');
                        // forward to Login page
                        $target = $this->_urlHelper->url(array('controller' => 'account',
                                'action' => 'login',
                                'language' => $this->view->language),
                            'lang_default', true);
                        $this->_redirect($target);
                    }
                    else {
                        $action = 'emailproblem';
                        $error = 'account-fetchpassword-error-email';
                    }
                }
                else {
                    $error = 'account-fetchpassword-error-nosuchemail';
                    $this->view->form = $form;
                }

                //Flash Messenger
                //$this->getFlashMessenger()->addMessage('account-fetchpassword-verification-email-sent-message');
                //$this->getFlashMessenger()->addMessage(array('success' => $this->translate('account-fetchpassword-verification-email-sent-message')));

                // Delete this, will open directly by url in email
                /*  $newPassForm = new Default_Form_NewPasswordForm();
                    $formData = $this->_request->getPost();
                    $this->view->form = $newPassForm;                  */

            // invalid fetchPasswordform
            } else {
                $this->view->form = $form;
            }
        }
        // confirm new password and redirect to login page
        else if ($action == 'submit' && $submittedForm == 'newpassword') {

            $newPassForm = new Default_Form_NewPasswordForm();
            $formData = $this->_request->getPost();
            // validate new Password Form
            if($newPassForm->isValid($formData)) {
                if ($formData['password'] == $formData['confirm']) {

                    $user = new Default_Model_User();

                    // change password
                    $user->changeUserPassword($_SESSION['request_userid'], $formData['password']);

                    // delete the request
                    $user->getAdapter()->delete('usr_has_npwd', 'id_usr_npwd='.$_SESSION['request_userid']);

                    // unset the session to avoid conflicts
                    unset($_SESSION['request_userid']);

                    // forward to Login page
                    $target = $this->_urlHelper->url(array('controller' => 'account',
                            'action' => 'login',
                            'language' => $this->view->language),
                        'lang_default', true);
                    $this->_redirect($target);
                } else {
                    // User failed (passwords didn't match), show form again
                    $form->getElement('confirm')->addErrorMessage('Passwords didn\'t match.');
                    $form->getElement('confirm')->markAsError();
                    $this->view->form = $form;
                    }
            // invalid newPasswordForm
            } else {
                $this->view->form = $newPassForm;
            }

        // Verification Link in Email was clicked
        } else if ($action == '' && $key != '') {
            $this->view->keyGiven = true;

            $user = new Default_Model_User();

            // create md5 hash of the key
            $key_safe = md5($key);

            // get password request
            $selectQuery = $user->getAdapter()->select()
                ->from('usr_has_npwd')
                ->where('key_npwd = ?', $key_safe);
            $npwdData = $user->getAdapter()->fetchAll($selectQuery);

            // check if request existed
            if ($npwdData != false) {
                // Check if the password has expired or not
                $dateNow = date('y-m-d H:i:s');
                if ($dateNow < $npwdData[0]['expire_date_npwd']) {
                    // Show the form for giving a new password
                    $form = new Default_Form_NewPasswordForm();
                    $this->view->form = $form;

                    // Place the userId into a session in order for the script
                    // above (new password confirmation) to know the id.
                    $_SESSION['request_userid'] = $npwdData[0]['id_usr_npwd'];
                }
                else {
                    $error = 'account-fetchpassword-error-keyexpired';
                }
            }
            else {
                $error = 'account-fetchpassword-error-nosuchkey';
            }
        }

        // inject the variables to the view
        $this->view->error         = $error;
        $this->view->action        = $action;
        $this->view->submittedForm = $submittedForm;
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

            $settingsData['gravatar'] = $userModel->getGravatarStatus($id);
            $settingsData['email'] = $email;
            $settingsData['confirm_email'] = $email;
            $settingsData['username'] = $identity->username;

            // Get user weblinks
            $userWeblinksModel = new Default_Model_UserWeblinks();
            $userWeblinks = $userWeblinksModel->getUserWeblinks($id);
            foreach ($userWeblinks as $userWeblink) {
                $settingsData['weblinks_name_site'.$userWeblink['count_uwl']] = $userWeblink['name_uwl'];
                $settingsData['weblinks_url_site'.$userWeblink['count_uwl']] = $userWeblink['url_uwl'];
            }

            // Get users email notifications and push to settingsdata in correct form
            $notificationsModel = new Default_Model_Notifications();
            $notifications = $notificationsModel->getNotificationsById($id);
			$settingsData['notifications'] = array();
            foreach ($notifications as $id_ntf => $notification) {
            		array_push($settingsData['notifications'], $id_ntf);
            }

            $favouriteModel = new Default_Model_UserHasFavourites();
            $followed = $favouriteModel->getWhatUserIsFollowing($id);
            foreach($followed as $key => $dataArray) {
            	$settingsData[$key] = array();
            	$settingsData[$key] = array_keys($followed[$key]);
            }

            //print_r($settingsData);die;
            // populate form
			if(isset($settingsData)) {
                //echo '<pre>'; var_dump($settingsData);die;
				$form->populate($settingsData);
			}

			// If request is post
			//$request = $this->getRequest();
			if($this->getRequest()->isPost()) {

                // get form data
				$formdata = $this->getRequest()->getPost();

				if($form->isValid($formdata)) {

					/* RC fix
					$formdata['own_follows'] = array_sum($formdata['own_follows']);
					$formdata['fvr_follows'] = array_sum($formdata['fvr_follows']);
					*/
					$formdata['own_follows'] = 7;
					$formdata['fvr_follows'] = 23;
                    // if form is valid
                    // Updates checked notifications

                    //echo "<pre>"; var_dump($formdata);
                    $notificationsModel->setUserNotifications($id, $formdata['notifications']);

                    $userProfile = new Default_Model_UserProfiles();
                    $userProfile->setProfileData($id, $formdata);

                    // Set weblinks
                    if (isset($formdata['weblinks_name_site1']) && isset($formdata['weblinks_url_site1'])) {
                        $userWeblinksModel->setWeblink($id, $formdata['weblinks_name_site1'], $formdata['weblinks_url_site1'], 1);
                    }
                    if (isset($formdata['weblinks_name_site2']) && isset($formdata['weblinks_url_site2'])) {
                        $userWeblinksModel->setWeblink($id, $formdata['weblinks_name_site2'], $formdata['weblinks_url_site2'], 2);
                    }
                    if (isset($formdata['weblinks_name_site3']) && isset($formdata['weblinks_url_site3'])) {
                        $userWeblinksModel->setWeblink($id, $formdata['weblinks_name_site3'], $formdata['weblinks_url_site3'], 3);
                    }
                    if (isset($formdata['weblinks_name_site4']) && isset($formdata['weblinks_url_site4'])) {
                        $userWeblinksModel->setWeblink($id, $formdata['weblinks_name_site4'], $formdata['weblinks_url_site4'], 4);
                    }
                    if (isset($formdata['weblinks_name_site5']) && isset($formdata['weblinks_url_site5'])) {
                        $userWeblinksModel->setWeblink($id, $formdata['weblinks_name_site5'], $formdata['weblinks_url_site5'], 5);
                    }

                    $user = new Default_Model_User($id);

                    // Updates email
                    if(strlen($formdata['email']) != 0) {
                        $user->changeUserEmail($id, $formdata['email']);
                    }

                    // Updates the password
                    if(strlen($formdata['password']) != 0) {
                        $user->changeUserPassword($id, $formdata['password']);
                    }

                    // Redirects the user to a user page
                    $redirect = $this->_urlHelper->url(array('controller' => 'account',
                                                             'action' => 'view',
                                                             'user' => $identity->username,
                                                             'language' => $this->view->language),
                                                       	     'lang_default', true);
                    $this->_redirect($redirect);
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

        $url_array = array('controller' => 'account',
                           'action' => 'userlist',
                           'language' => $this->view->language);

        $url = $this->_urlHelper->url($url_array,'lang_default', true);
        // Get requests
        $params = $this->getRequest()->getParams();

        // Get page nummber and items per page
        $page = isset($params['page']) ? $params['page'] : 1;
        $count = isset($params['count']) ? $params['count'] : 10;
        $order = isset($params['order']) ? $params['order'] : null;
        $list = isset($params['list']) ? $params['list'] : null;

        if($list != "asc" && $list != "desc") $list = null;

        // Filter form data
        $formData['username'] = isset($params['username']) ? $params['username'] : '';
        $formData['city'] = isset($params['city']) ? $params['city'] : '';
        $formData['country'] = isset($params['country']) ? $params['country'] : 0;
        $formData['group'] = isset($params['group']) ? $params['group'] : '';
        $formData['exactg'] = isset($params['exactg']) ? $params['exactg'] : 0;
        //$formData['contentlimit'] = isset($params['contentlimit']) ? $params['contentlimit'] : null;
        //$formData['counttype'] = isset($params['counttype']) ? $params['counttype'] : 0;

        if($list == "asc") $listName = "ascending";
        elseif($list == "desc") $listName = "descending";
		else $listName = "ascending";

        $orderList = array(
			"username" => $this->view->translate('userlist-orderlist-username',$listName),
        	"login" => $this->view->translate('userlist-orderlist-login',$listName),
			"joined" => $this->view->translate('userlist-orderlist-joined',$listName),
			"content" => $this->view->translate('userlist-orderlist-content',$listName),
			"views" => $this->view->translate('userlist-orderlist-views',$listName),
			"rating" => $this->view->translate('userlist-orderlist-rating',$listName),
			"popularity" => $this->view->translate('userlist-orderlist-popularity',$listName),
        	"comments" => $this->view->translate('userlist-orderlist-comments',$listName),
		);

		$userCountries = null;
		$userCities= null;

        $userLocations = $this->getAllCitiesAndCountries();
        if(isset($userLocations['countries'])) $userCountries = Zend_Json::encode($userLocations['countries']);
        if(isset($userLocations['cities'])) $userCities = Zend_Json::encode($userLocations['cities']);

        $formData['countries'][] = $this->view->translate('userlist-filter-country-all');
        if(isset($userLocations['countries'])) {
	        foreach($userLocations['countries'] as $country) {
	        	$formData['countries'][$country['countryIso']] = $country['name'];
	        }
        }

        $pat_sql = array("%","_");
        $pat_def = array("*","?");

        //Replace * and ? characters to % and _ characters for mysql LIKE
        $formData['username'] = str_replace($pat_def,$pat_sql,$formData['username']);
        $formData['city'] = str_replace($pat_def,$pat_sql,$formData['city']);
        $formData['group'] = str_replace($pat_def,$pat_sql,$formData['group']);

        $userModel = new Default_Model_User();

        //variable initializations (to avoid notice errors :p)
        $pageCount = null;
        $userContents = null;
        $listSize = null;
        $userIdList = null;
        $userListing = null;
        $topNames = null;
        $topList = null;
        $topCountry = null;
        $topGroup = null;
        $topCity = null;

        //This is code to fetch search results
        if($url != $this->_urlHelper->url()) {
	        $listSize = 1;

	        // Get user listing
	        $userListing = $userModel->getUserListing($formData, $page, $count, $order, $list, $listSize);

	        $userContents = array();
	        $cache = Zend_Registry::get('short_cache');
	        foreach($userListing as $user) {
	        	// Get cache from registry
	        	if(is_array($user['contents']) && sizeof($user['contents']) > 0) {
	        		//Content ID:s are saved to cache which is used by ajax in user search
					$cache->save($user['contents'], 'UserContentsList_'.$user['id_usr']);
					$contentsArray = $userModel->getUserContentList($user['contents'],3);
	        	}
	        	else $contentsArray = null;

	        	if (!is_array($contentsArray) || sizeof($contentsArray) < 1)
	        		$userContents[$user['id_usr']] = array();
	        	else $userContents[$user['id_usr']] = $contentsArray;
	        }
	        $userIdList = array();
	        foreach($userListing as $u) {
	        	$userIdList[] = $u['id_usr'];
	        }

	        // Calculate total page count
	        $pageCount = ceil($listSize / $count);
        } else { //Here is Top list code :)

        	$auth = Zend_Auth::getInstance();
        	$userid = null;
			if($auth->hasIdentity()) $userid = $auth->getIdentity()->user_id;

        	$topListClasses = $userModel->getUserTopList();

        	$topListUsers = $topListClasses['Users'];
        	$topListCountries = $topListClasses['Countries'];
        	$topListCities = $topListClasses['Cities'];
        	$topListGroups = $topListClasses['Groups'];

        	if($userid) $topListUsers->addUser($userid);
			$topList = $topListUsers->getTopList();

			if($userid) $topListCountries->addUser($userid);
			$topCountry = $topListCountries->getTopList();

			if($userid) $topListCities->addUser($userid);
			$topCity = $topListCities->getTopList();

			$topGroup = $topListGroups->getTopList();
        }

        if(!$topNames) {
        	$topNames[] = "Count";
            $topNames[] = "View";
			$topNames[] = "Popularity";
			$topNames[] = "Rating";
			$topNames[] = "Comment";
			$topNames[] = "Amount";
        }

        $topListBoxes = array(
        	'Users' => $topList,
       		'Groups' => $topGroup,
       		'Cities' => $topCity,
        	'Countries' => $topCountry,
        );
        //print_r($topListBoxes);die;

        // User list search form
        $userSearch = new Default_Form_UserListSearchForm(null, $formData);

        $order = isset($order) ? $order : "username";
        $list = isset($list) ? $list : "asc";
        $form_url_path = array_merge($url_array,array('order' => $order,'list' => $list));
        $form_url = $this->_urlHelper->url($form_url_path,'lang_default', true);
        $userSearch->setAction($form_url)
                   ->setMethod('get');

        $parsedUrl = "";
        foreach($params as $key => $param) {
        	if($key == "controller" || $key == "action" || $key == "module" || $key == "language") continue;
        	$parsedUrl .= "/$key/$param";
        }
        $parsedUrl = str_replace("%","%25",$parsedUrl);

        $this->view->userSearch = $userSearch;
        // Custom pagination to fix memory error on large amount of data
        $paginator = new Zend_View();
        $paginator->setScriptPath('../application/views/scripts');
        $paginator->pageCount = $pageCount;
        $paginator->currentPage = $page;
        $paginator->pagesInRange = 10;

        // Set to view
        $this->view->userPaginator = $paginator;
        $this->view->userListData = $userListing;
        $this->view->userList = $userIdList;
        $this->view->count = $count;
        $this->view->userCount = $listSize;
        $this->view->list = $listName;
        $this->view->top = $topList;
        $this->view->topListBoxes = $topListBoxes;
        $this->view->topCountry = $topCountry;
        $this->view->parsedUrl = $parsedUrl;
        $this->view->topNames = $topNames;
        $this->view->page = $page;
        $this->view->order = $orderList;
        $this->view->lastOrder = $order;
        $this->view->cities = $userCities;
        $this->view->countries = $userCountries;
        $this->view->userContents = $userContents;

    } // end of userListingAction


    /*
     * getAllCitiesAndCountries
     *
     */
	private function getAllCitiesAndCountries() {

		$cache = Zend_Registry::get('short_cache');

		// Load user locations from cache
		if(!$resultList = $cache->load('UserLocationsList')) {
			$userModel = new Default_Model_UserProfiles();
			$locations = $userModel->getAllUsersLocations();
			$cache->save($locations, 'UserLocationsList');

		} else {
			$locations = $resultList;
		}

		$output = $locations;

		return $output;
	}

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

              $this->resetCache();
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


			$this->resetCache();
            $url = $this->_urlHelper->url(array('controller' => 'account',
                                                  'action' => 'images',
                                                  'language' => $this->language),
                                            'lang_default', true);
            $this->_redirect($url);
        } else {
            $this->_redirect($this->_baseUrl);
        }
    }

    public function resetCache() {
       // Purge cache from old picture
        $cache = Zend_Registry::get('cache');
        $auth = Zend_Auth::getInstance();
		$userid = $auth->getIdentity()->user_id;

    	$cacheThumb = 'ProfileThumbs_' . $userid . '_thumbnail_usi';
        $cacheImage = 'ProfileThumbs_' . $userid . '_image_usi';
        $cache->remove($cacheThumb);
        $cache->remove($cacheImage);
    }

    private function getViewRows($id_usr) {

    	$viewsModel = new Default_Model_ContentViews();
    	$contentHasTagModel = new Default_Model_ContentHasTag();

    	// Get recent post data
    	$recentposts_raw = $viewsModel->getUserViewedContents($id_usr);

    	$recentposts = array();

    	// Gather data for recent posts
    	$i = 0;
    	foreach ($recentposts_raw as $post) {
	    	$tags = $contentHasTagModel->getContentTags($post['id_cnt']);

	    	// Action helper for define is tag running number divisible by two
		$tags = $this->_helper->tagsizes->isTagDivisibleByTwo($tags);

	    	$this->gtranslate->setLangFrom($post['language_cnt']);
	    	$translang = $this->gtranslate->getLangPair();

	    	$recentposts[$i]['class'] = 'views';
	    	$recentposts[$i]['original'] = $post;
	    	$recentposts[$i]['translated'] = $this->gtranslate->translateContent($post);
	    	$recentposts[$i]['original']['tags'] = $tags;
	    	$recentposts[$i]['translated']['tags'] = $tags;
	    	$recentposts[$i]['original']['translang'] = $translang;
	    	$recentposts[$i]['translated']['translang'] = $translang;

	    	$i++;
    	}
    	return $recentposts;
    }

    private function getFavouriteRows($id_usr) {
   			$favouriteModel = new Default_Model_UserHasFavourites();
   			$contentHasTagModel = new Default_Model_ContentHasTag();
   			$user = new Default_Model_User();
        	$favouriteList = $user->getUserFavouriteContent($id_usr);

        	// Initialize Favourite counts
        	$dataa['favouriteCounts'] = null;
        	$dataa['favouriteCounts']['totalCount'] = 0;
        	$dataa['favouriteCounts']['updated'] = 0;
        	$dataa['favouriteCounts']['problem'] = 0;
        	$dataa['favouriteCounts']['finfo'] = 0;
        	$dataa['favouriteCounts']['idea'] = 0;

        	foreach($favouriteList as $k => $favourite) {
        		/*
        		 * If content Id doesn't exist anymore:
        		 * unset from Favouritelist and remove all lines from user_has_favourites table that
        		 * refers to this content id
        		 */
        		if (isset($favourite['id_cnt_fvr']) && $favourite['id_cnt'] == '') {
                	unset($favouriteList[$k]);
                	$favouriteModel->removeAllContentFromFavouritesByContentId($favourite['id_cnt_fvr']);
                	continue;
            	}

        	    if (isset($favourite['key_cty'])) {
                    $dataa['favouriteCounts']['totalCount']++; // Increase total count
                    $dataa['favouriteCounts'][$favourite['key_cty']]++; // Increase content type count
                }

                if(isset($favourite['last_checked']) && isset($favourite['modified_cnt'])) {
                	/*if(strtotime($favourite['last_checked']) < strtotime($favourite['modified_cnt'])) {
                		$dataa['favouriteCounts']['updated']++;
                		$favouriteList[$k] = array_merge($favourite,array('updated' => '1'));
                	}
                	else $favouriteList[$k] = array_merge($favourite,array('updated' => '0'));*/
                	$favouriteList[$k] = array_merge($favourite,array('updated' => '0')); // This row makes all favourites look like there are no updates since we dont need that check
                }
        	}

        	$newList = array(
        		'counts' => array(
        			'total' => $dataa['favouriteCounts']['totalCount'],
        			'updated' => $dataa['favouriteCounts']['updated'],
        			'problem' => $dataa['favouriteCounts']['problem'],
        			'finfo'	=> $dataa['favouriteCounts']['finfo'],
        			'idea'	=> $dataa['favouriteCounts']['idea']
        		),
        		'contents' => array()
        	);

        	$k = 0;
        	foreach($favouriteList as $key => $favourite) {
        		//print_r($favourite);die;

        	    $tags = $contentHasTagModel->getContentTags($favourite['id_cnt']);

		    	// Action helper for define is tag running number divisible by two
				$tags = $this->_helper->tagsizes->isTagDivisibleByTwo($tags);

		    	$this->gtranslate->setLangFrom($favourite['language_cnt']);
		    	$translang = $this->gtranslate->getLangPair();


		    	$newList['contents'][$k]['class'] = "fvr_".$favourite['key_cty'];
		    	if($favourite['updated'] === "1")
		    		$newList['contents'][$k]['class'] = "fvr_updated ".$newList['contents'][$k]['class'];
		    	$newList['contents'][$k]['original'] = $favourite;
		    	$newList['contents'][$k]['translated'] = $this->gtranslate->translateContent($favourite);
		    	$newList['contents'][$k]['original']['tags'] = $tags;
		    	$newList['contents'][$k]['translated']['tags'] = $tags;
		    	$newList['contents'][$k]['original']['translang'] = $translang;
		    	$newList['contents'][$k]['translated']['translang'] = $translang;
		    	$k++;
        	}

        	return $newList;

    }

    public function onlineAction() {
    	$this->_helper->viewRenderer->setNoRender(true);
    	$this->_helper->layout()->disableLayout();

    	$timer = 180;

    	$cache = Zend_Registry::get('cache');
    	$userList = array();
    	$userList = $cache->load('onlineUsers');

    	if ($userList) {
    		foreach ($userList as $user) {

    			if (time() - $user['time'] >= $timer) {
   					unset($userList[$user['id_usr']]);
    			} else {
    				echo $user['login_name_usr'].":";
    				echo time()-$user['time'].":";
    				echo $user['mode']."<br />";
    			}
    		}
    		$cache->save($userList, 'onlineUsers');
    	}
    	//Zend_Debug::dump($userList);
    }
} // end of class
