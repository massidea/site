<?php
/**
 * Oibs_Content_CustomController
 *
 * @package        controllers
 * @license        GPL v2
 * @version        2.0
 */

/**
 * Oibs_Content_CustomController
 *
 * @package        controllers
 * @license        GPL v2
 * @version        2.0
 */
class Oibs_Controller_CustomController extends Zend_Controller_Action
{
	/** @var \Zend_Session_Namespace */
	protected $_session;
	/** @var \Zend_Controller_Action_Helper_FlashMessenger */
	protected $_flashMessenger;
	/** @var \Zend_View_Helper_Url */
	protected $_urlHelper;
	/** @var mixed */
	protected $_identity;

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		// initialize actions, view helpers and other components
		$this->_urlHelper = $this->_helper->getHelper('url');
		$this->_identity = Zend_Auth::getInstance()->getIdentity();

		// load identity information for whatever reason
		if ($this->hasIdentity()) {
			$message_model = New Default_Model_PrivateMessages();
			$unread_messages = $message_model->getCountOfUnreadPrivMsgs($this->getIdentity()->user_id);
			$this->view->unread_privmsgs = $unread_messages;

			$profile_model = New Default_Model_UserProfiles();
			$roles = $profile_model->getUserRoles($this->getIdentity()->user_id);
			if (is_string($roles)) {
				$roles = array($roles);
			}
			$this->view->logged_user_roles = $roles;

		} else {
			$this->view->logged_user_roles = json_decode('["user"]');
		}
	}

	/**
	 * @inheritdoc
	 */
	public function preDispatch()
	{
		//(see boostrap) this hack is used in bypassing flashmessenger one hop limiter.
		if (isset($_SESSION["msg"])) {
			$_SESSION["FlashMessenger"]["default"][0] = $_SESSION["msg"];
		}
	}

	/**
	 * @inheritdoc
	 */
	public function postDispatch()
	{
		// set layout view variables
		$this->view->authenticated   = $this->hasIdentity();
		$this->view->language        = $this->getSession()->language;
		$this->view->activeLanguages = $this->_getTranslatedLanguages();
		$this->view->baseUrl         = Zend_Controller_Front::getInstance()->getBaseUrl();

		// inject plugins into the view
		$this->view->BBCode = new Oibs_Controller_Plugin_BBCode();

		// inject flash messenger messages
		$this->view->messages = $this->getFlashMessenger()->getMessages();

		if ($this->hasIdentity()) {
			// identity
            $this->view->identity      = $this->getIdentity();
            $this->view->profile_image = $this->getProfileImage();

			// navigation
			$this->view->groups = $this->_getNavigationGroups();
			$this->view->categories = $this->_getNavigationCategories();
			$this->view->campaigns = $this->_getNavigationCampains();
        } else {

			$login_form = new Default_Form_LoginForm();
			$login_form
				->setReturnUrl($this->getRequest()->getRequestUri())
				->setDecorators(array(array('ViewScript', array('viewScript' => 'forms/loginHeader.phtml'))));
			$this->view->loginform = $login_form;
		}

		parent::postDispatch();
	}

	/**
	 * Action for all controllers to change the language
	 */
	public function changeLanguageAction()
	{
		$language   = $this->_getParam('language');
		$return_url = $this->_getParam('returnUrl');
		// simply redirect, the language will automatically change
		$this->_redirect('/' . $language . $return_url);
	}

	/**
	 * Adds a new message to the flash messenger view helper.
	 * When a redirect url is given, it
	 *
	 * @param string $message
	 * @param string $redirect_url
	 */
	protected function addFlashMessage($message, $redirect_url = null)
	{
		$this->getFlashMessenger()->addMessage($this->view->translate($message));
		if ($redirect_url !== null) {
			$this->_redirect($redirect_url);
		}
	}

	/**
	 * Returns a formatted URL for the given parameters
	 *
	 * @param  array  $urlOptions Options passed to the assemble method of the Route object.
	 * @param  mixed  $name       The name of a Route to use. If null it will use the current Route
	 * @param  bool   $reset      Whether or not to reset the route defaults with those provided
	 * @param  bool   $encode     Whether or not to encode url parameters
	 * @return string Url for the link href attribute.
	 */
	public function getUrl(array $urlOptions = array(), $name = null, $reset = true, $encode = true)
	{
		if (!isset($urlOptions['action']))
			$urlOptions['action'] = $this->_getParam('action');
		if (!isset($urlOptions['controller']))
			$urlOptions['controller'] = $this->_getParam('controller');
		if (!isset($urlOptions['language']))
			$urlOptions['language'] = $this->getActiveLanguage();

		return $this->_urlHelper->url($urlOptions, 'lang_default', true);
	}

	/**
	 * Encodes given value and key to url.
	 * @param $value
	 * @param $key
	 */
	public static function encodeParam(&$value, &$key)
	{
		$value = urlencode($value);
		$key = urlencode($key);
	}

	/**
	 * Sets the identity and stores it in the Zend_Auth store.
	 *
	 * @param $identity
	 * @return Oibs_Controller_CustomController
	 */
	protected function setIdentity($identity)
	{
		$this->_identity = $identity;

		$auth = Zend_Auth::getInstance();
		$auth->getStorage()->write($identity);

		return $this;
	}

	/**
	 * Returns the logged in identity or null.
	 *
	 * @return mixed
	 */
	public function getIdentity()
	{
		return $this->_identity;
	}

	/**
	 * Determines whether the user is logged in or not.
	 *
	 * @return bool
	 */
	public function hasIdentity()
	{
		return $this->getIdentity() != null;
	}

    /**
     * Get Profile Image for Layout
     * @return string
     */
    public function getProfileImage() {
        if ($this->hasIdentity()) {
            $id = $this->getIdentity()->user_id;

            $model = new Default_Model_UserImages;
            $images = $model->getImagesByUsername($id);

            if(count($images) > 0) {
                $dates = array();
                for($a = 0; $a < count($images); $a++) {
                    $dates[$a] = $images[$a]['modified_usi'];
                }
                $active = array_search(max($dates), $dates);
                return $images[$active]['imagepath_usi'];
            }
        }

        return "/img/user_avatar_24.png";
    }

	/**
	 * Returns the flash messenger action helper.
	 *
	 * @return \Zend_Controller_Action_Helper_FlashMessenger
	 */
	public function getFlashMessenger()
	{
		if ($this->_flashMessenger === null) {
			$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
		}
		return $this->_flashMessenger;
	}

	/**
	 * Retrieves a session object for storing session data.
	 * This object is assigned to the 'Default' namespace.
	 *
	 * @param string $namespace
	 * @return Zend_Session_Namespace
	 */
	public function getSession($namespace = 'Default')
	{
		if ($this->_session === null) {
			$this->_session = new \Zend_Session_Namespace($namespace);
		}
		return $this->_session;
	}

	/**
	 * Returns the currently active language.
	 *
	 * @return string
	 */
	protected function getActiveLanguage()
	{
		return $this->getSession()->language;
	}

	/**
	 * Returns an array of all available languages with translations.
	 *
	 * The array has two keys:
	 * <ul>
	 *  <li><b>id</b>: The ISO6391 shortcut</li>
	 *  <li><b>name</b>: The (already translated) name of the language</li>
	 * </ul>
	 *
	 * @return array
	 */
	private function _getTranslatedLanguages()
	{
		$lang_model = new Default_Model_Languages();
		$languages  = $lang_model->getAllNamesAndCodes();

		$translated_languages = array();
		foreach ($languages as $lang) {
			$translated_languages[] = array(
				'id' => $lang['iso6391_lng'],
				'name' => $lang['name_lng'],
			);
		}

		return $translated_languages;
	}

	/**
	 * Returns an array of groups for the currently displayed user
	 * @return array
	 */
	private function _getNavigationGroups() {
		if (!$this->hasIdentity()) return array();

		$id = $this->getIdentity()->user_id;
		$userModel = new Default_Model_User();

		return $userModel->getUserGroups($id);
	}

	/**
	 * Returns an array of categories for the navigation
	 * @return array
	 */
	private function _getNavigationCategories() {
		if (!$this->hasIdentity()) return array();

		$categoryModel = new Default_Model_Category();
		return $categoryModel->getCategories();
	}

	/**
	 * Returns an array of campaigns for the navigation
	 * @return array
	 */
	private function _getNavigationCampains() {
		if (!$this->hasIdentity()) return array();

		$id = $this->getIdentity()->user_id;
		$userModel = new Default_Model_User();

		return $userModel->getUserCampaigns($id);
	}

}
