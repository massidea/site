<?php
/**
 *  AdminController -> Includes tools for site admins
 *
 *  Copyright (c) <2009>, Pekka Piispanen <pekka.piispanen@cs.tamk.fi>
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
 *  AdminController - class
 *
 *  @package        controllers
 *  @author         Pekka Piispanen & Mikko Korpinen
 *  @copyright      2009 Pekka Piispanen
 *  @license        GPL v2
 *  @version        1.0
 */
class AdminController extends Oibs_Controller_CustomController
{
	public function init()
	{
        parent::init();

        // Get authentication
        $auth = Zend_Auth::getInstance();
        Zend_Layout::getMvcInstance()->setLayout('layout_public');
        // If user has identity
        if ($auth->hasIdentity())
        {
            if(!in_array("admin", $this->view->logged_user_roles))
            {
                $message = 'admin-no-permission';
                $url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index',
                                                'language' => $this->view->language),
                                          'lang_default', true);
                $this->addFlashMessage($message, $url);
            }
        }
        else
        {
            $message = 'admin-no-permission';
                 $url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index',
                                                'language' => $this->view->language),
                                          'lang_default', true);
            $this->addFlashMessage($message, $url);
        }
		$this->view->title = "OIBS";
        $this->baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
	}

	public function indexAction()
	{
		$this->view->title = "OIBS";
	}

    public function editrolesAction()
    {
        $params = $this->getRequest()->getParams();
        $username = $params['user'];

        if($username != "")
        {
            $user = new Default_Model_User();
            if($user->usernameExists($username))
            {
                $this->view->editrole_username = $username;

                $id_usr = $user->getIdByUsername($username);

                $userProfiles = new Default_Model_UserProfiles();
                $user_roles = $userProfiles->getUserRoles($id_usr);
                $this->view->user_roles = $user_roles;

                $userRoles = new Default_Model_UserRoles();
                $roles = $userRoles->getRoles();
                $this->view->roles = $roles;
            }
            else
            {
                $message = 'admin-editrole-invalid-username';
                $url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index',
                                                'language' => $this->view->language),
                                          'lang_default', true);
                $this->addFlashMessage($message, $url);
            }
        }
        else
        {
            $message = 'admin-editrole-missing-username';
            $url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index',
                                                'language' => $this->view->language),
                                          'lang_default', true);
            $this->addFlashMessage($message, $url);
        }
    }

    public function addroleAction()
    {
        $params = $this->getRequest()->getParams();
        $username = $params['user'];
        $role = $params['role'];

        if($username != "" && $role != "")
        {
            $user = new Default_Model_User();
            if($user->usernameExists($username))
            {
                $id_usr = $user->getIdByUsername($username);

                $userRoles = new Default_Model_UserRoles();
                $roles = $userRoles->getRoles();

                $userProfiles = new Default_Model_UserProfiles();
                $user_roles = $userProfiles->getUserRoles($id_usr);

                if(in_array($role, $roles))
                {
                    array_push($user_roles, $role);

                    if($userProfiles->setUserRoles($id_usr, $user_roles))
                    {
                        $message = 'admin-addrole-successful';
                        $url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index',
                                                'language' => $this->view->language),
                                          'lang_default', true);
                        $this->addFlashMessage($message, $url);
                    }
                    else
                    {
                        $message = 'admin-addrole-not-successful';
                        $url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index',
                                                'language' => $this->view->language),
                                          'lang_default', true);
                        $this->addFlashMessage($message, $url);
                    }
                }
                else
                {
                    $message = 'admin-addrole-invalid-role';
                    $url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index',
                                                'language' => $this->view->language),
                                          'lang_default', true);
                    $this->addFlashMessage($message, $url);
                }
            }
            else
            {
                $message = 'admin-editrole-invalid-user';
                $url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index',
                                                'language' => $this->view->language),
                                          'lang_default', true);
                $this->addFlashMessage($message, $url);
            }
        }
        else
        {
            $message = 'admin-editrole-missing-username-role';
            $url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index',
                                                'language' => $this->view->language),
                                          'lang_default', true);
            $this->addFlashMessage($message, $url);
        }
    }

    public function removeroleAction()
    {
        $params = $this->getRequest()->getParams();
        $username = $params['user'];
        $role = $params['role'];

        if($username != "" && $role != "")
        {
            $user = new Default_Model_User();
            if($user->usernameExists($username))
            {
                $id_usr = $user->getIdByUsername($username);

                $userProfiles = new Default_Model_UserProfiles();
                $user_roles = $userProfiles->getUserRoles($id_usr);

                if(in_array($role, $user_roles))
                {
                    foreach ($user_roles as $key => $value)
                    {
                        if($value == $role)
                        {
                            unset($user_roles[$key]);
                        }
                    }

                    $user_roles = array_values($user_roles);

                    if($userProfiles->setUserRoles($id_usr, $user_roles))
                    {
                        $message = 'admin-removerole-successful';
                        $url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index',
                                                'language' => $this->view->language),
                                          'lang_default', true);
                        $this->addFlashMessage($message, $url);
                    }
                    else
                    {
                        $message = 'admin-removerole-not-successful';
                        $url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index',
                                                'language' => $this->view->language),
                                          'lang_default', true);
                        $this->addFlashMessage($message, $url);
                    }
                }
                else
                {
                    $message = 'admin-removerole-role-not-found';
                    $url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index',
                                                'language' => $this->view->language),
                                          'lang_default', true);
                    $this->addFlashMessage($message, $url);
                }
            }
            else
            {
                $message = 'admin-editrole-invalid-user';
                $url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index',
                                                'language' => $this->view->language),
                                          'lang_default', true);
                $this->addFlashMessage($message, $url);
            }
        }
        else
        {
            $message = 'admin-editrole-missing-username-role';
            $url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index',
                                                'language' => $this->view->language),
                                          'lang_default', true);
            $this->addFlashMessage($message, $url);
        }
    }

    public function managerolesAction()
    {
        $params = $this->getRequest()->getParams();

        if(isset($params['managerolesaction']))
        {
            $this->view->managerolesaction = $params['managerolesaction'];
        }
        else
        {
            $this->view->managerolesaction = "";
        }
    }

    public function commentflagsAction()
    {
    	// Get all POST-parameters
    	$posts = $this->_request->getPost();
    	// Get models for the job
    	$flagmodel = new Default_Model_CommentFlags();
    	$commentmodel = new Default_Model_Comments();
    	$contentmodel = new Default_Model_Content();

        if($posts)
    	{
        // Remove comment text ("Comment removed"-text)
        if($posts['rm'] == "comment")
        {
            foreach($posts as $key => $post)
            {
                if($key != "rm" && $key != "selectall")
                {
                    // Flags from comment_flags_cfl
                    $cmf_ids = $flagmodel->getFlagsByCommentId($key);
                    foreach($cmf_ids as $cmf_id)
                    {
                        $flagmodel->removeFlag($cmf_id);
                    }
                    // Text from comments_cmt
                    $commentmodel->removeCommentText($key);
                }
            }
        }

        // Remove flags
        if($posts['rm'] == "flag")
        {
            foreach($posts as $key => $post)
            {
                if($key != "rm" && $key != "selectall")
                {
                    // Flags from comment_flags_cfl
                    $cmf_ids = $flagmodel->getFlagsByCommentId($key);
                    foreach($cmf_ids as $cmf_id)
                    {
                        $flagmodel->removeFlag($cmf_id);
                    }
                }
            }
        }

    	}

    	$flagItems = $flagmodel->getAllFlags();

    	// Awesome algorithm for counting how many flags each flagged comment has
    	$tmpCount = array();
    	foreach($flagItems as $flagItem)
    	{
    		if(!isset($tmpCount[$flagItem['id_comment_cmf']])) $tmpCount[$flagItem['id_comment_cmf']] = 0;
    		$tmpCount[$flagItem['id_comment_cmf']]++;
    	}
    	arsort($tmpCount);
    	$data = array();
    	$count = 0;

    	// Loop and re-arrange our variables
    	foreach($tmpCount as $cmt_id => $cmt_count)
    	{
    		$comment = $commentmodel->getById($cmt_id);
    		$comment = $comment['Data']['body_cmt'];
    		$content_id = $commentmodel->getContentIdsByCommentId($cmt_id);
    		$content_id = $content_id[0]['id_target_cmt'];
    		$content_url = $this->_urlHelper->url(array('controller' => 'view',
														'action' => $content_id,
														'language' => $this->view->language),
														'lang_default', true);

    		$data[$count]['cnt_id'] = $content_id;
    		$data[$count]['cnt_title'] = $contentmodel->getContentHeaderByContentId($content_id);
    		$data[$count]['cnt_url'] = $content_url;
    		$data[$count]['cmt_id'] = $cmt_id;
    		$data[$count]['cmt_body'] = $comment;
    		$data[$count]['cmt_count'] = $cmt_count;
    		$count++;
    	}

		// Go!
    	$this->view->comments = $data;
    }
    public function contentflagsAction()
    {
    	// Get all POST-parameters
    	$posts = $this->_request->getPost();

    	// Get models for the job
    	$contentflagmodel = new Default_Model_ContentFlags();
        $commentflagmodel = new Default_Model_CommentFlags();
    	$contentmodel = new Default_Model_Content();
        $commentmodel = new Default_Model_Comments();

        // Get cache from registry
        $cache = Zend_Registry::get('cache');
        $cachePosts = array();
        if ($handle = opendir(APPLICATION_PATH.'/../tmp')) {
            while (false !== ($file = readdir($handle))) {
                if (strcmp(substr($file, 0, 24), "zend_cache---IndexPosts_") == 0) {
                    $cachePosts[] = $file;
                }
            }
            closedir($handle);
        }

        // Recent posts id
        if($posts)
    	{
            // Remove content
            if($posts['rm'] == "content")
            {
                foreach($posts as $key => $post)
                {
                    if($key != "rm" && $key != "selectall")
                    {

                        // Remove content and all dependign stuff
                        $content = new Default_Model_Content();
                        $contentRemoveChecker = $content->removeContentAndDepending($key);

                        if (isset($cachePosts)) {
                            // Remove recent post cache
                            foreach($cachePosts as $cachePost) {
                                $cache->remove(mb_substr($cachePost, 13));
                            }
                        }
                    }
                }
            }

            // Unpublish content
            if($posts['rm'] == "pubflag")
            {
                foreach($posts as $key => $post)
                {
                    if($key != "rm" && $key != "selectall")
                    {
                        // Flags from content_flags_cfl
                        $cfl_ids = $contentflagmodel->getFlagsByContentId($key);
                        foreach($cfl_ids as $cfl_id)
                        {
                            $contentflagmodel->removeFlag($cfl_id);
                        }
                        // Unpublish
                        $contentmodel->publishContent($key,0);

                        if (isset($cachePosts)) {
                            // Remove recent post cache
                            foreach($cachePosts as $cachePost) {
                                $cache->remove(mb_substr($cachePost, 13));
                            }
                        }
                    }
                }
            }

            // Remove flags
            if($posts['rm'] == "flag")
            {
                foreach($posts as $key => $post)
                {
                    if($key != "rm" && $key != "selectall")
                    {
                        // Flags from content_flags_cfl
                        $cfl_ids = $contentflagmodel->getFlagsByContentId($key);
                        foreach($cfl_ids as $cfl_id)
                        {
                            $contentflagmodel->removeFlag($cfl_id);
                        }
                    }
                }
            }
        }

    	// Awesome algorithm for counting how many flags each flagged content has
    	$flagItems = $contentflagmodel->getAllFlags();
    	$tmpCount = array();
    	foreach($flagItems as $flagItem)
    	{
    		if (!isset($tmpCount[$flagItem['id_content_cfl']])) $tmpCount[$flagItem['id_content_cfl']] = 0;
    		$tmpCount[$flagItem['id_content_cfl']]++;
    	}
    	arsort($tmpCount);
    	$data = array();
    	$count = 0;

    	// Loop and re-arrange our variables
    	foreach($tmpCount as $cnt_id => $cnt_count)
    	{
    		$content = $contentmodel->getById($cnt_id);
    		$data[$count]['id'] = $cnt_id;
        	$data[$count]['ctype'] = $content['Content']['Data']['id_cty_cnt'];
    		$data[$count]['title'] = $content['Content']['Data']['title_cnt'];
    		$data[$count]['lead'] = $content['Content']['Data']['lead_cnt'];
    		$data[$count]['body'] = $content['Content']['Data']['body_cnt'];
    		$data[$count]['count'] = $cnt_count;
    		$data[$count]['url'] = $this->_urlHelper->url(array('controller' => 'view',
														'action' => $cnt_id,
														'language' => $this->view->language),
														'lang_default', true);
    		$count++;
    	}
		// Go!
    	$this->view->contents = $data;
    }

    public function cachemanagerAction()
    {
    	$flushrequest = $this->_request->getParam('clean', false) ? true : false;
    	$flushResponse = null;
    	$cacheDir = '../tmp/';
    	$cacheFiles = scandir($cacheDir);
    	$totalSize = 0;
    	$i = 0;

    	foreach($cacheFiles as $cacheFile)
    	{
    		if(strstr($cacheFile, 'zend_cache---'))
    		{
    			$fileSize = filesize($cacheDir . $cacheFile) / 1024;

    			if($flushrequest)
    			{
	    			if(unlink($cacheDir.$cacheFile))
	    			{
	    				$flushResponse[$cacheFile] = 'OK';
	    				unset($cacheFiles[$i]);
	    			}
	    			else
	    			{
	    				$flushResponse[$cacheFile] = 'Failed';
	    				$totalSize += $fileSize;
	    			}
    			}
    			else
    			{
    				$totalSize += $fileSize;
    			}

    		}
    		else
    		{
    			unset($cacheFiles[$i]);
    		}
    		$i++;
    	}
    	$fileCount = count($cacheFiles);

    	$cache = Zend_Registry::get('cache');
    	$fillingPercentage = $cache->getFillingPercentage();
    	$cleanLink = $this->_urlHelper->url(array('clean' => 'all'));

    	$this->view->cacheFiles = $cacheFiles;
    	$this->view->fillingPercentage = $fillingPercentage;
    	$this->view->fileCount = $fileCount;
    	$this->view->totalSize = $totalSize;
    	$this->view->flushResponse = $flushResponse;
    	$this->view->cleanLink = $cleanLink;
    }
}
