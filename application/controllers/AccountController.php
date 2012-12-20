<?php
/**
 * AccountController
 * User profile information, login, logout and registration
 *
 * @package     controllers
 * @license     GPL v2
 * @version     1.0
 */

/**
 * AccountController
 * User profile information, login, logout and registration
 *
 * @package     controllers
 * @license     GPL v2
 * @version     1.0
 */
class AccountController extends Oibs_Controller_CustomController
{

	/**
	 * @inheritdoc
	 */
	public function init()
    {
        parent::init();

        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('login', 'html')->initContext();

        $this->view->title = 'account-title';
        Zend_Layout::getMvcInstance()->setLayout('layout_public');
    }

	public function indexAction()
	{
	    if (!$this->hasIdentity()) {
		    $this->_redirect('/');
	    }

	    $this->_redirect($this->getUrl(array(
		    'controller' => 'account',
		    'action'     => 'view',
		    'user'       => $this->getIdentity()->user_id,
		    'language'   => $this->getActiveLanguage(),
	    )));
    }

	public function loginAction()
	{
		$return_url = $this->_getParam('login_returnurl') ?: '/' . $this->getActiveLanguage() . '/content/feed';

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

		$this->setIdentity($user->createAuthIdentity());
		$this->_redirect($return_url);
	}

    public function logoutAction()
    {
	    $this->setIdentity(null);
        $this->addFlashMessage('account-logout-succesful-msg', '/');
    }

	public function viewAction()
	{

		if (Zend_Controller_Action_HelperBroker::hasHelper('redirector')) {
			$redirector = Zend_Controller_Action_HelperBroker::getExistingHelper('redirector');
		}

		$hometargeturl = $this->_urlHelper->url(array('controller' => 'index',
				'action' => 'index',
				'language' => $this->getActiveLanguage()),
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

		$myFavourites = $this->getFavouriteRows($data['id_usr']);

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
		$box = new Oibs_Controller_Plugin_AccountViewBox();
		$box	->setHeader("My Views & Favourites")
			->setName("my-views")
			->setClass("right")
			->addTab("Views", "views", "views selected")
			->addTab("Favourites","problem","fvr_problem fvr_idea fvr_finfo",$myFavourites['counts']['total'])
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

    public function registerAction()
    {
	    if ($this->hasIdentity()) {
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
		    $this->addFlashMessage('registration-usermodel-data-procesing-failure', '/');
	    }

	    // Fetch user id
	    $uid = $user->getIdByUsername($form_data['register_username']);
	    $userProfiles = new Default_Model_UserProfiles();
	    $userProfiles->setUserEmployment($uid, $form_data, 0);
	    $userProfiles->setUserCity($uid, $form_data, 1);

	    // check if user is logged in
	    $username = $form_data['register_username'];
	    $password = $form_data['register_password'];
	    $id = $user->getIdByUsername($username);

	    $user = new Default_Model_User($id);
	    $result = $user->loginUser(array(
		    'login_username' => $username,
		    'login_password' => $password,
	    ));

	    if ($result == true) {
		    // record login attempt
		    $user->loginSuccess();

		    // create identity data and write it to session
		    $identity = $user->createAuthIdentity();
		    $this->setIdentity($identity);

		    $this->_redirect($this->getUrl(array(
			    'controller' => 'content',
			    'action'     => 'list',
		    )));
	    } else {
		    $this->view->errormsg = $this->view->translate(
			    'account-login-not-successful'
		    );
	    }
    }

    public function fetchpasswordAction()
    {
	    if ($this->hasIdentity()) {
		    $this->_redirect('/');
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
                    $url = strtolower(trim(array_shift($path))) . '://' . $_SERVER['HTTP_HOST'];
	                $url .= $this->getUrl(array('controller' => 'account', 'action' => 'fetchpassword'));
                    $url .= '?key=' . $key;

                    // add new password request into the database
                    $user->addPasswordRequest($userId, $key_safe);

                    // send verification email
                    if ($user->sendVerificationEmail($userId, $email, $url, $this->getActiveLanguage())) {
                        $action = 'emailsent';
                        $this->getFlashMessenger()->addMessage('account-fetchpassword-verification-email-sent-message');

                        // forward to Login page
	                    $this->_redirect($this->getUrl(array('controller' => 'account', 'action' => 'login')));
                    } else {
                        $action = 'emailproblem';
                        $error = 'account-fetchpassword-error-email';
                    }
                } else {
                    $error = 'account-fetchpassword-error-nosuchemail';
                    $this->view->form = $form;
                }
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
                    $this->_redirect($this->getUrl(array('controller' => 'account', 'action' => 'login')));
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

    function settingsAction()
    {
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
                                                             'language' => $this->getActiveLanguage()),
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
                                            'language' => $this->getActiveLanguage()),
                                      'lang_default', true);

            $this->_redirect($target);
        }
    }

}
