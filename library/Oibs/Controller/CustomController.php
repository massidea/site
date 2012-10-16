<?php
/**
 *  ContentController -> Viewing content
 *
* 	Copyright (c) <2009>, Joel Peltonen <joel.peltonen@cs.tamk.fi>
* 	Copyright (c) <2009>, Pekka Piispanen <pekka.piispanen@cs.tamk.fi>
*
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 *  Copyright (c) <2009>, Joel Peltonen <joel.peltonen@cs.tamk.fi>
 *  Copyright (c) <2009>, Pekka Piispanen <pekka.piispanen@cs.tamk.fi>
 *
 *  This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License
 *  as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
 * more details.
 *  This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 *  warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
 *  more details.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write to the Free
 * Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *  You should have received a copy of the GNU General Public License along with this program; if not, write to the Free
 *  Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * License text found in /license/
 */

/**
 *  ContentController - class
 *
 *  @package 	controllers
 *  @author 		Joel Peltonen & Pekka Piispanen
 *  @copyright 	2009 Joel Peltonen & Pekka Piispanen
 *  @license 	GPL v2
 *  @version 	1.0
 *  @package    controllers
 *  @author     Joel Peltonen & Pekka Piispanen
 *  @copyright  2009 Joel Peltonen & Pekka Piispanen
 *  @license    GPL v2
 *  @version    1.1
 */
class Oibs_Controller_CustomController extends Zend_Controller_Action
{
	public $db;
	public $breadcrumbs;

	protected $_redirector;
	protected $_flashMessenger;
    protected $_urlHelper;

	/**
	*	init
	*
	*	Class initialization
	*
	*/
	public function init()
	{

        if (isset($_SESSION['language'])) {
            $this->view->language = $_SESSION['language'];

        } else {
        // Zend_Controller_Action_Helper_Redirector::setPrependBase(false);
		// Load languages to view
		    $this->view->languages = Zend_Registry::get('Available_Translations');
		    $this->view->language = Zend_Registry::get('Zend_Locale');
        }

        // this can be used in any view now...useful I believe :)
		$this->view->baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
		//$this->db = Zend_Registry::get('db');
		$this->breadcrumbs = new Oibs_Controller_Plugin_BreadCrumbs();

        // bbCode plugin
        $this->view->BBCode = new Oibs_Controller_Plugin_BBCode();

        // Set up GTranslate plugin
        $this->gtranslate = new Oibs_Controller_Plugin_GTranslate();
        $translateSession = new Zend_Session_Namespace('translate');
        // If no session exist, set default translation language to english
        if(!isset($translateSession->translateTo)) $translateSession->translateTo = 'en';
        $this->gtranslate->setLangTo($translateSession->translateTo);

        // Set up JsMetaBox plugin
        $this->view->jsmetabox = new Oibs_Controller_Plugin_JsMetaBox();

		// Add the root step to breadcrumbs
		$this->breadcrumbs->addStep('Massidea.org Home', '/');

		// Flash message
		$this->_flashMessenger 	= $this->_helper->getHelper('FlashMessenger');

		// Redirector... by putting PrependBase to false, the url helper does not
        // just 'attach' the new url on top of the old one. This avoids those mysterious
        // urls like http://localhost/controller/action/something/action/something...
		$this->_redirector = $this->_helper->getHelper('Redirector');
		$this->_redirector->setPrependBase(false);

        // Set database. Is this needed somewhere? This creates an error at least...Maybe depreciated.
		//$this->db = Zend_Registry::get('db');

		// Get users name
		$auth = Zend_Auth::getInstance();

		if(isset($auth->getIdentity()->username))
        {
			$this->view->username = $auth->getIdentity()->username;
            $this->view->userid = $auth->getIdentity()->user_id;
            $this->view->jsmetabox->append('userId', $auth->getIdentity()->user_id);

            $Default_Model_privmsg = New Default_Model_PrivateMessages();
            $unread_privmsgs = $Default_Model_privmsg->getCountOfUnreadPrivMsgs($auth->getIdentity()->user_id);
            $this->view->unread_privmsgs = $unread_privmsgs;

            $Default_Model_UserProfiles = New Default_Model_UserProfiles();
            $roles = $Default_Model_UserProfiles->getUserRoles($auth->getIdentity()->user_id);
            if(is_string($roles)) {
                $roles = array($roles);
            }
            $this->view->logged_user_roles = $roles;

        }
        else
        {
            $this->view->logged_user_roles = json_decode('["user"]');
        }

        $params = $this->getRequest()->getParams();
        $this->view->controller = $params['controller'];
        $this->view->action = $params['action'];

		// Search form
		$simpleSearchForm = new Default_Form_SimpleSearchForm();

		// url helper
		$this->_urlHelper = $this->_helper->getHelper('url');
		// $params = $this->getRequest()->getParams();

		$url = $this->_urlHelper->url(array('controller' => 'search',
                                            'action' => 'result',
                                            'language' => $this->view->language),
                                      'lang_default', true);

		$simpleSearchForm ->setAction($url)
			->setMethod('get');

		$this->view->searchForm = $simpleSearchForm;

		if ($params['controller'] != 'ajax') {
			$this->setActiveOnline();
		}

		$this->view->jsmetabox->append('idleRefreshUrl', $this->_urlHelper->url(array('controller' => 'ajax', 'action' => 'idlerefresh'), 'lang_default', true));
		$this->view->jsmetabox->append('baseUrl', $this->view->baseUrl);


		$id_target = "";
		switch($params['controller']) {
			case ('campaign'): $id_target = "cmpid"; break;
			case ('content'): $id_target = "content_id"; break;
			case ('account'): $id_target = "user"; break;
			case ('group'): $id_target = "groupid"; break;
		}

		if (isset($params[$id_target])) $this->view->jsmetabox->append('currentPage', array('id' => $params[$id_target], 'type' => $params['controller']));
		/*
		echo '<pre>';
		print_r($params);
		echo '</pre>';
		*/


        // fill footer comboBox with languages
        $languageModel = new Default_Model_Languages();

        $languages = $languageModel->getAllNamesAndCodes();

        $activeLanguages = array();

        foreach($languages as $lang) {
            $activeLanguages[] = array(
                'id'   => $lang['iso6391_lng'],
                'name' => $lang['name_lng'],
            );
        }

        $this->view->activeLanguages = $activeLanguages;



    } // end of init

    /**
     * change Lanugage Action
     */
    public function changeLanguageAction() {
        $language = $this->_getParam('language');
        $return_url = $this->_getParam('returnUrl');

        $_SESSION['language'] = $language;

        $this->_redirect('/'.$language.$return_url);

    }



	/**
	 *	getUrl
	 *
	 *	returns the url for the action and controller given as
	 *	parameters.
	 *
	 *	@param string action the name of the action
	 * 	@param string controller the name of the controller
	 *	@return string
	 */
	public function getUrl($action = null, $controller = null)
	{
		$url = $this->_helper->url->simple($action, $controller);
		return $url;
	} // end of getUrl

	/**
	 *	preDispatch
	 *
	 *	receives a user request before the front controller dispatches
	 *	the request to the respective action
	 */
	public function preDispatch()
	{
		$auth = Zend_Auth::getInstance();
		if ($auth->hasIdentity())
		{
			$this->view->authenticated = true;
			$this->view->identity = $auth->getIdentity();
		}
		else
		{
			$this->view->authenticated = false;
			$loginForm = new Default_Form_LoginForm(array('returnurl' => $this->getRequest()->getRequestUri()));
			$loginForm->setDecorators(array(array(
				'ViewScript',
				array('viewScript' => 'forms/loginHeader.phtml')
			)));
            $this->view->loginform = $loginForm;
		}
		$this->view->title = $this->breadcrumbs->getTitle();

		$this->setMessages();

				//(see boostrap) this hack is used in bypassing flashmessenger one hop limiter.
				if (isset($_SESSION["msg"])) {
					//$_SESSION["FlashMessenger"]["default"][1] = $_SESSION["msg"];
					$_SESSION["FlashMessenger"]["default"][0] = $_SESSION["msg"];
				}
				//hax end

	} // end of preDispatch

	/**
	 * 	postDispatch
	 *
	 *	executes once a controller action has completed, prior to the view
	 *	renderer displaying the view.
	 */
	public function postDispatch()
	{
		$this->setMessages();
		parent::postDispatch();
        $this->_flashMessenger->addMessage("");
	} // end of postDispatch

	/**
	 *	flash
	 */
	protected function flash($message,$to)
	{
		$this->_flashMessenger->addMessage($this->view->translate($message));
		//echo "<pre>"; print_r($_SESSION); echo "</pre>"; die;
		$this->_redirector->gotoUrl($to);
	} // end of flash

	/**
	 *	setMessages
	 */
	protected function setMessages()
	{
		$this->view->messages = join("",$this->_flashMessenger->getMessages());
	} // end of setMessages

	/**
	*   encodeParam
	*
	*   Encodes given value and key to url.
	*/
    public static function encodeParam(&$value, &$key) {
        $value = urlencode($value);
        $key = urlencode($key);
    } // end of encodeParam

    /**
    *   checkIfArrayHasKeyWithValue
    *
    *   This function checks if an array has given key and value
    *   It works recursivly so it doesn't matter how deep the array is.
    *   Function found on php.net, submitted by 'brouwer dot p at gmail dot com'
    */
    function checkIfArrayHasKeyWithValue($array, $key, $value) {
        //loop through the array
        foreach($array as $val) {
          //if $val is an array cal myInArray again with $val as array input
          if(is_array($val)){
            if($this->checkIfArrayHasKeyWithValue($val, $key, $value))
              return true;
          }
          //else check if the given key has $value as value
          else{
            if(isset($array[$key]) && $array[$key]==$value)
              return true;
          }
        }
        return false;
    }

    function setActiveOnline() {
    	$auth = Zend_Auth::getInstance();
		if ($auth->hasIdentity()) {
    		$this->setOnline($auth->getIdentity()->user_id, 1);
		}
    }

    protected function setOnline($id, $mode) {
    	if (null == $id || null == $mode) return false;
    	$userModel = new Default_Model_User();
    	$userData = $userModel->getSimpleUserDataById($id);

    	$cache = Zend_Registry::get('cache');

    	$userList = array();
    	$userList = $cache->load('onlineUsers');
	    $userData['mode'] = $mode;
	    $userData['time'] = time();
	    $userList[$id] = $userData;

	    $cache->save($userList, 'onlineUsers');
    }


    /*protected function setOffline($id) {
    	$cache = Zend_Registry::get('cache');

    	$userList = array();
    	$userList = $cache->load('onlineUsers');
    	if (null != $userList[$id]) {
    		$userList[$id]['browsers']--;
    	}
    }*/

    /**
     * Multibyte uppercase first character function
     * @param $string
     */
    function mb_ucfirst($string) {
        $string = mb_strtoupper(mb_substr($string, 0, 1)) . mb_substr($string, 1);
        return $string;
    }

    /**
     * Replace any whitespace with only a single space
     *
     * @param string $str
     * @return string
     */
    function replaceWhitespace($str) {
        $str = preg_replace('/\s+/', ' ', trim((string) $str));
        return $str;
    }

    /**
     * oibs_nl2p - Convert multiple new lines to p tags with an optional class assigned to the p tags
     *
     * @param string $string
     * @param string $class
     * @return string
     */
    function oibs_nl2p($string, $class='') {
        $class_attr = ($class!='') ? ' class="'.$class.'"' : '';
        return
            '<p'.$class_attr.'>'
            .preg_replace('#(<br\s*?/?>\s*?){2,}#', '</p>'."\n".'<p'.$class_attr.'>', $this->oibs_nl2br($string))
            .'</p>';
    }

    /**
     * oibs_nl2br - Replace all \n with just <br />
     *
     * @param $text
     * @return string
     */
    function oibs_nl2br($text) {
        return strtr($text, array("\r\n" => '<br />', "\r" => '<br />', "\n" => '<br />'));
    }

} // end of class
