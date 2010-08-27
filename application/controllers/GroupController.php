<?php
/**
 *  GroupController -> Viewing content from the database
 *
 *   Copyright (c) <2010>, Mikko Aatola <mikko@aatola.net>
 *             (c) <2010>, Sami Kiviharju <stoney78@kapsi.fi>
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
 *  GroupController - class
 *
 *  @package    controllers
 *  @author     Mikko Aatola & Sami kiviharju
 *  @copyright  2010 Mikko Aatola & Sami Kiviharju
 *  @license    GPL v2
 *  @version    1.0
 */
 class GroupController extends Oibs_Controller_CustomController
{
    function indexAction()
    {
        $auth = Zend_Auth::getInstance();
        $logged_in = $auth->hasIdentity();

        $this->view->logged_in = $logged_in;
    }

    /**
     * listAction - shows a list of all groups
     */
    function listAction()
    {
        $redirectUrl = $this->_urlHelper->url(array('controller' => 'group',
                                                    'action' => 'index',
                                                    'language' => $this->view->language),
                                              'lang_default', true);
        $this->_redirector->gotoUrl($redirectUrl);
        /*
        $grpmodel = new Default_Model_Groups();
        $cmpmodel = new Default_Model_Campaigns();
        $grpadm = new Default_Model_GroupAdmins();

        // If you find a better way to do this, be my guest.
        // ...and also fix it to GroupsAndCampaignsController.
        $grps = $grpmodel->getAllGroups();
        $grps_new = array();
        foreach ($grps as $grp) {
            $adm = $grpadm->getGroupAdmins($grp['id_grp']);
            $grp['id_admin'] = $adm[0]['id_usr'];
            $grp['login_name_admin'] = $adm[0]['login_name_usr'];
            $grps_new[] = $grp;
        }

        $this->view->groups = $grps_new;
        */
    }

    /**
     * viewAction - shows an individual group's page
     *
     * @author Mikko Aatola
     */
    function viewAction()
    {
        // Get authentication
        $auth = Zend_Auth::getInstance();

        // If user has identity
        if ($auth->hasIdentity()) {
            $this->view->identity = true;
        } else {
            $this->view->identity = false;
        }

        // Get data for this specific group.
        $grpId = $this->_request->getParam('groupid');
        $grpModel = new Default_Model_Groups();
        $usrHasGrpModel = new Default_Model_UserHasGroup();
        $usrHasGrpWaitingModel = new Default_Model_UserHasGroupWaiting();
        $grpAdminsModel = new Default_Model_GroupAdmins();
        $campaignModel = new Default_Model_Campaigns();
        $grpAdmins = $grpAdminsModel->getGroupAdmins($grpId);
        $user = $auth->getIdentity();
        $grpData = $grpModel->getGroupData($grpId);
        $grpData['description_grp'] = str_replace("\n", '<br>', $grpData['description_grp']);
        $grpData['body_grp'] = str_replace("\n", '<br>', $grpData['body_grp']);

        // Group type
        $grpTypeId = $grpModel->getGroupTypeId($grpId);
        $grpTypeModel = new Default_Model_GroupTypes();
        $isClosed = $grpTypeModel->isClosed($grpTypeId);
        // Waiting list count
        $usrWaitingCount = $usrHasGrpWaitingModel->getUserCountByGroup($grpId);

        // Group weblinks
        $groupWeblinksModel = new Default_Model_GroupWeblinks();
        $grpData['groupWeblinks'] = $groupWeblinksModel->getGroupWeblinks($grpId);
        $i = 0;
        foreach($grpData['groupWeblinks'] as $weblink) {
            if (strlen($weblink['name_gwl']) == 0 || strlen($weblink['url_gwl']) == 0) {
                unset($grpData['groupWeblinks'][$i]);
            }
            $i++;
        }

        // Get group groups
        $grpHasGrpModel = new Default_Model_GroupHasGroup();
        $linkedgroups = $grpHasGrpModel->getGroupGroups($grpId);
        $linkedgroups = array_merge($linkedgroups['parents'], $linkedgroups['childs']);

        $comments = new Oibs_Controller_Plugin_Comments("group", $grpId);
        if ($this->view->identity) $comments->allowComments(true);
  		$this->view->jsmetabox->append('commentUrls', $comments->getUrls());
		$comments->loadComments();
        
		$this->view->comments		 = $comments;
        // Add data to the view.
        $this->view->grpId = $grpId;
        $this->view->grpData = $grpData;
        $this->view->grpUsers = $usrHasGrpModel->getAllUsersInGroup($grpId);
        $this->view->grpAdmins = $grpAdmins;
        $this->view->userHasGroup = $usrHasGrpModel;
        $this->view->userHasGroupWaiting = $usrHasGrpWaitingModel;
        //$this->view->campaigns = $campaignModel->getCampaignsByGroup($grpId);
        $this->view->openCampaigns = $campaignModel->getOpenCampaignsByGroup($grpId);
        $this->view->notstartedCampaigns = $campaignModel->getNotstartedCampaignsByGroup($grpId);
        $this->view->endedCampaigns = $campaignModel->getEndedCampaignsByGroup($grpId);
        $this->view->userIsGroupAdmin = $this->checkIfArrayHasKeyWithValue($grpAdmins, 'id_usr', $user->user_id);
        $this->view->linkedgroups = $linkedgroups;
        $this->view->isClosed = $isClosed;
        $this->view->usrWaitingCount = $usrWaitingCount;
    }

    function removeAction()
    {
        $auth = Zend_Auth::getInstance();

        if ($auth->hasIdentity()) {
            $grpId = $this->_request->getParam('id');

            if (!$grpId) {
                $target = $this->_urlHelper->url(
                    array(
                        'controller' => 'index',
                        'action' => 'index',
                        'language' => $this->view->language),
                    'lang_default', true
                );
                $this->_redirector->gotoUrl($target);
            }

            // Only group admins get to delete the group.
            $grpAdminsModel = new Default_Model_GroupAdmins();
            $grpAdmins = $grpAdminsModel->getGroupAdmins($grpId);
            $userIsGroupAdmin = $this->checkIfArrayHasKeyWithValue(
                $grpAdmins, 'id_usr', $auth->getIdentity()->user_id);
            if (!$userIsGroupAdmin) {
                $target = $this->_urlHelper->url(
                    array(
                        'groupid' => $grpId,
                        'language' => $this->view->language),
                    'group_shortview', true
                );
                $this->_redirector->gotoUrl($target);
            }

            // Get existing group info.
            $grpModel = new Default_Model_Groups();
            $grpData = $grpModel->getGroupData($grpId);

            // Delete group.
            $grpModel->removeGroup($grpId);

            // Redirect to the groups & campaigns page.
            $target = $this->_urlHelper->url(
                array(
                    'controller' => 'groupsandcampaigns',
                    'action' => 'index',
                    'language' => $this->view->language),
                'lang_default', true);
            $this->_redirector->gotoUrl($target);
        } else {
            // Not logged in.
            $target = $this->_urlHelper->url(
                array(
                    'controller' => 'groupsandcampaigns',
                    'action' => 'index',
                    'language' => $this->view->language),
                'lang_default', true);
            $this->_redirector->gotoUrl($target);
        }
    }

    function editAction()
    {
        $auth = Zend_Auth::getInstance();

        if ($auth->hasIdentity()) {
            $grpId = $this->_request->getParam('id');
            
            if (!$grpId) {
                $target = $this->_urlHelper->url(
                    array(
                        'controller' => 'index',
                        'action' => 'index',
                        'language' => $this->view->language),
                    'lang_default', true
                );
                $this->_redirector->gotoUrl($target);
            }

            // Only group admins get to edit group info.
            $grpAdminsModel = new Default_Model_GroupAdmins();
            $grpAdmins = $grpAdminsModel->getGroupAdmins($grpId);
            $userIsGroupAdmin = $this->checkIfArrayHasKeyWithValue(
                $grpAdmins, 'id_usr', $auth->getIdentity()->user_id);
            if (!$userIsGroupAdmin) {
                $target = $this->_urlHelper->url(
                    array(
                        'groupid' => $grpId,
                        'language' => $this->view->language),
                    'group_shortview', true
                );
                $this->_redirector->gotoUrl($target);
            }

            // Get existing group info.
            $grpModel = new Default_Model_Groups();
            $grpData = $grpModel->getGroupData($grpId);

            // Create the form in edit mode.
            $form = new Default_Form_AddGroupForm($this, array(
                'mode' => 'edit',
                'oldname' => $grpData['group_name_grp'],
            ));

            // Populate the form.
            $formData = array();
            $formData['groupname'] = $grpData['group_name_grp'];
            $formData['grouptype'] = $grpData['id_type_grp'];
            $formData['groupdesc'] = $grpData['description_grp'];
            $formData['groupbody'] = $grpData['body_grp'];

            // Get group weblinks
            $groupWeblinksModel = new Default_Model_GroupWeblinks();
            $groupWeblinks = $groupWeblinksModel->getGroupWeblinks($grpId);
            foreach ($groupWeblinks as $groupWeblink) {
                $formData['weblinks_name_site'.$groupWeblink['count_gwl']] = $groupWeblink['name_gwl'];
                $formData['weblinks_url_site'.$groupWeblink['count_gwl']] = $groupWeblink['url_gwl'];
            }

            $form->populate($formData);

            $this->view->form = $form;

            $this->view->grpName = $grpData['group_name_grp'];

            // If the form is posted and valid, save the changes to db.
            $request = $this->getRequest();
            if ($request->isPost()) {
                $post = $request->getPost();
                if ($form->isValid($post)) {
                    // Change existing group info.
                    $groupModel = new Default_Model_Groups();
                    $newGroupId = $groupModel->editGroup(
                        $grpId,
                        $post['groupname'],
                        $post['grouptype'],
                        $post['groupdesc'],
                        $post['groupbody']);

                    // Set weblinks
                    if (isset($post['weblinks_name_site1']) && isset($post['weblinks_url_site1'])) {
                        $groupWeblinksModel->setWeblink($grpId, $post['weblinks_name_site1'], $post['weblinks_url_site1'], 1);
                    }
                    if (isset($post['weblinks_name_site2']) && isset($post['weblinks_url_site2'])) {
                        $groupWeblinksModel->setWeblink($grpId, $post['weblinks_name_site2'], $post['weblinks_url_site2'], 2);
                    }
                    if (isset($post['weblinks_name_site3']) && isset($post['weblinks_url_site3'])) {
                        $groupWeblinksModel->setWeblink($grpId, $post['weblinks_name_site3'], $post['weblinks_url_site3'], 3);
                    }
                    if (isset($post['weblinks_name_site4']) && isset($post['weblinks_url_site4'])) {
                        $groupWeblinksModel->setWeblink($grpId, $post['weblinks_name_site4'], $post['weblinks_url_site4'], 4);
                    }
                    if (isset($post['weblinks_name_site5']) && isset($post['weblinks_url_site5'])) {
                        $groupWeblinksModel->setWeblink($grpId, $post['weblinks_name_site5'], $post['weblinks_url_site5'], 5);
                    }

                    // Redirect back to the group page.
                    $target = $this->_urlHelper->url(
                        array(
                            'groupid' => $grpId,
                            'language' => $this->view->language),
                         'group_shortview', true
                    );
                    $this->_redirector->gotoUrl($target);
                }
            }
        } else {
            // Not logged in.
            $target = $this->_urlHelper->url(
                array(
                    'controller' => 'groupsandcampaigns',
                    'action' => 'index',
                    'language' => $this->view->language),
                'lang_default', true);
            $this->_redirector->gotoUrl($target);
        }
    }

    function createAction()
    {
        $auth = Zend_Auth::getInstance();

        if ($auth->hasIdentity()) {
            // Add the "add new group"-form to the view.
            $form = new Default_Form_AddGroupForm();
            $this->view->form = $form;

            // If the form is posted and valid, add the new group to db.
            $request = $this->getRequest();
            if ($request->isPost()) {
                $post = $request->getPost();
                if ($form->isValid($post)) {
                    // Add new group to db.
                    $groupModel = new Default_Model_Groups();
                    $newGroupId = $groupModel->createGroup(
                        $post['groupname'],
                        $post['grouptype'],
                        $post['groupdesc'],
                        $post['groupbody']);

                    // Set weblinks
                    $groupWeblinksModel = new Default_Model_GroupWeblinks();
                    if (isset($post['weblinks_name_site1']) && isset($post['weblinks_url_site1'])) {
                        $groupWeblinksModel->setWeblink($newGroupId, $post['weblinks_name_site1'], $post['weblinks_url_site1'], 1);
                    }
                    if (isset($post['weblinks_name_site2']) && isset($post['weblinks_url_site2'])) {
                        $groupWeblinksModel->setWeblink($newGroupId, $post['weblinks_name_site2'], $post['weblinks_url_site2'], 2);
                    }
                    if (isset($post['weblinks_name_site3']) && isset($post['weblinks_url_site3'])) {
                        $groupWeblinksModel->setWeblink($newGroupId, $post['weblinks_name_site3'], $post['weblinks_url_site3'], 3);
                    }
                    if (isset($post['weblinks_name_site4']) && isset($post['weblinks_url_site4'])) {
                        $groupWeblinksModel->setWeblink($newGroupId, $post['weblinks_name_site4'], $post['weblinks_url_site4'], 4);
                    }
                    if (isset($post['weblinks_name_site5']) && isset($post['weblinks_url_site5'])) {
                        $groupWeblinksModel->setWeblink($newGroupId, $post['weblinks_name_site5'], $post['weblinks_url_site5'], 5);
                    }

                    // Add the current user to the new group.
                    $userHasGroupModel = new Default_Model_UserHasGroup();
                    $userHasGroupModel->addUserToGroup(
                        $newGroupId, $this->view->userid);

                    // Make the current user an admin for the new group.
                    $groupAdminModel = new Default_Model_GroupAdmins();
                    $groupAdminModel->addAdminToGroup(
                        $newGroupId, $this->view->userid);

                    $target = $this->_urlHelper->url(array(
                        'groupid' => $newGroupId,
                        'language' => $this->view->language),
                         'group_shortview', true);
                    $this->_redirector->gotoUrl($target);
                }
            }
        } else {
            // Not logged in.
            $target = $this->_urlHelper->url(
                array(
                    'controller' => 'groupsandcampaigns',
                    'action' => 'index',
                    'language' => $this->view->language),
                'lang_default', true);
            $this->_redirector->gotoUrl($target);
        }
    }

    function joinAction()
    {
        $auth = Zend_Auth::getInstance();

        if ($auth->hasIdentity()) {
            // Get group id and user id.
            $grpId = $this->_request->getParam('groupid');
            $usrId = $auth->getIdentity()->user_id;

            // Check group type
            $grpModel = new Default_Model_Groups();
            $grpTypeId = $grpModel->getGroupTypeId($grpId);
            $grpTypeModel = new Default_Model_GroupTypes();
            $isClosed = $grpTypeModel->isClosed($grpTypeId);

            if ($isClosed) {
                // Join the group waitinglist
                $usrHasGroupWaitingModel = new Default_Model_UserHasGroupWaiting();
                $usrHasGroupWaitingModel->addUserWaitingToGroup($grpId, $usrId);

                $privateMessagesModel = new Default_Model_PrivateMessages();
                $grpAdminsModel = new Default_Model_GroupAdmins();
                $grpAdmins = $grpAdminsModel->getGroupAdmins($grpId);
                $grpData = $grpModel->getGroupData($grpId);
                foreach ($grpAdmins as $admin) {
                    $adminmessage = Array();
                    $adminmessage['privmsg_sender_id'] = 0;
                    $adminmessage['privmsg_receiver_id'] = $admin['id_usr'];
                    $adminmessage['privmsg_header'] = $grpData['group_name_grp'].' group has new user in waiting list';
                    $link = $this->_urlHelper->url(array(
                                                       'controller' => 'group',
                                                       'action' => 'waitinglist',
                                                       'grpid' => $grpId,
                                                       'language' => $this->view->language),
                                                   'lang_default', true);
                    $adminmessage['privmsg_message'] = '<a href="'.$link.'">Check user(s) who want to join '
                                                  .$grpData['group_name_grp'].' group.</a>';
                    $privateMessagesModel->addMessage($adminmessage);
                }
                $usermessage = Array();
                $usermessage['privmsg_sender_id'] = 0;
                $usermessage['privmsg_receiver_id'] = $usrId;
                $usermessage['privmsg_header'] = $grpData['group_name_grp'].' waiting list';
                $link = $this->_urlHelper->url(array('groupid'    => $grpId,
                                                     'language'   => $this->view->language),
                                               'group_shortview', true);
                $usermessage['privmsg_message'] = 'You are in waiting list for <a href="'.$link.'">'
                                              .$grpData['group_name_grp'].' group.</a>';
                $privateMessagesModel->addMessage($usermessage);
            } else {
                // Join the group.
                $usrHasGroupModel = new Default_Model_UserHasGroup();
                $usrHasGroupModel->addUserToGroup($grpId, $usrId);
            }

            // Redirect back to the group page.
            $target = $this->_urlHelper->url(
                array(
                    'groupid'    => $grpId,
                    'language'   => $this->view->language),
                'group_shortview', true);
            $this->_redirector->gotoUrl($target);
        } else {
            // Not logged in - can't join a group.
            $target = $this->_urlHelper->url(
                array(
                    'controller' => 'index',
                    'action' => 'index',
                    'language' => $this->view->language),
                'lang_default', true);
            $this->_redirector->gotoUrl($target);
        }
    }

    function leaveAction()
    {
        $auth = Zend_Auth::getInstance();

        if ($auth->hasIdentity()) {
            // Get group id and user id.
            $grpId = $this->_request->getParam('groupid');
            $usrId = $auth->getIdentity()->user_id;

            // Get confirm 0 = question, 1 = leave and delete also content links from group campaigns, 2 = leave only
            $confirm = $this->_request->getParam('confirm');

            $groupAdminsModel = new Default_Model_GroupAdmins();
            if ($groupAdminsModel->userIsAdmin($grpId, $usrId)) {
                // Group admin can't leave the group.
                $message = "You can't leave this group "
                         . "because you're its admin.";
                $url = $this->_urlHelper->url(
                    array(
                        'controller' => 'msg',
                        'action' => 'index',
                        'language' => $this->view->language),
                    'lang_default', true);
                $this->flash($message, $url);
            } else {
                // Get group campaings where user have content linked
                $usrModel = new Default_Model_User();
                $campaigns = $usrModel->getUserCampaignsWhereUserHasContent($usrId, $grpId);
                //echo '<pre>'; var_dump($campaigns);
                //$contents = $usrModel->getUserContentsInCampaign($usrId, $campaigns[1]['id_cmp']);
                //echo '<pre>'; var_dump($contents);

                if (isset($confirm) && $confirm == 1) {
                    // Leave group and delete all content linking from group campaigns
                    $usrHasGroupModel = new Default_Model_UserHasGroup();
                    $cmpHasCntModel = new Default_Model_CampaignHasContent();
                    $usrHasGroupModel->removeUserFromGroup($grpId, $usrId);
                    foreach ($campaigns as $campaign) {
                        $contents = $usrModel->getUserContentsInCampaign($usrId, $campaign['id_cmp']);
                        foreach ($contents as $content) {
                            $cmpHasCntModel->removeContentFromCampaign($content['id_cmp'], $content['id_cnt']);
                        }
                    }
                    $redirect = true;
                } elseif (isset($confirm) && $confirm == 2) {
                    // Only leave campaign
                    $usrHasGroupModel = new Default_Model_UserHasGroup();
                    $usrHasGroupModel->removeUserFromGroup($grpId, $usrId);
                    $redirect = true;
                } else {
                    // No confirmation yet
                    $this->view->campaigns = $campaigns;
                    $this->view->contentInCampaigns = count($campaigns);
                    $this->view->grpId = $grpId;
                    $redirect = false;
                }
            }

            if ($redirect) {
                // Redirect back to the group page.
                $target = $this->_urlHelper->url(
                    array(
                        'groupid'    => $grpId,
                        'language'   => $this->view->language),
                    'group_shortview', true);
                $this->_redirector->gotoUrl($target);
            }
        } else {
            // Not logged in
            $target = $this->_urlHelper->url(
                array(
                    'controller' => 'index',
                    'action' => 'index',
                    'language' => $this->view->language),
                'lang_default', true);
            $this->_redirector->gotoUrl($target);
        }
    }

    function leavewaitingAction()
    {
        $auth = Zend_Auth::getInstance();

        if ($auth->hasIdentity()) {
            // Get group id and user id.
            $grpId = $this->_request->getParam('groupid');
            $usrId = $auth->getIdentity()->user_id;

            $usrHasGroupWaitingModel = new Default_Model_UserHasGroupWaiting();
            $usrHasGroupWaitingModel->removeUserWaitingFromGroup($grpId, $usrId);
            $redirect = true;

            if ($redirect) {
                // Redirect back to the group page.
                $target = $this->_urlHelper->url(
                    array(
                        'groupid'    => $grpId,
                        'language'   => $this->view->language),
                    'group_shortview', true);
                $this->_redirector->gotoUrl($target);
            }
        } else {
            // Not logged in
            $target = $this->_urlHelper->url(
                array(
                    'controller' => 'index',
                    'action' => 'index',
                    'language' => $this->view->language),
                'lang_default', true);
            $this->_redirector->gotoUrl($target);
        }
    }

    public function imageAction()
    {
        $form = new Default_Form_ProfileImageForm();
        $this->view->form = $form;
    }

    /**
     * linkgroupAction
     *
     * Link group to group.
     */
    public function linkgroupAction()
    {
        $auth = Zend_Auth::getInstance();

        if ($auth->hasIdentity()) {
            $usrId = $auth->getIdentity()->user_id;

            $grpId = $this->_request->getParam('grpid');
            if (!isset($grpId)) {
                $redirectUrl = $this->_urlHelper->url(array('controller' => 'group',
                                                            'action' => 'index',
                                                            'language' => $this->view->language),
                                                      'lang_default', true);
                $this->_redirector->gotoUrl($redirectUrl);
            }

            $this->view->grpid = $grpId;

            $grpmodel = new Default_Model_Groups();
            $grp = $grpmodel->getGroupData($grpId);

            $usrmodel = new Default_Model_User();

            $usrgrp = $usrmodel->getUserGroups($usrId);

            $grpHasGrpModel = new Default_Model_GroupHasGroup();
            if (!empty($usrgrp)) {
                $i = 0;
                foreach ($usrgrp as $group) {
                    if ($grpHasGrpModel->checkIfGroupHasGroup($grpId, $group['id_grp']) || $grpId == $group['id_grp']) {
                       unset($usrgrp[$i]);
                    }
                    $i++;
                }
            }

            if (!empty($usrgrp)) {
                $hasUserGroups = true;
            } else {
                $hasUserGroups = false;
            }

            $this->view->grp = $grp;
            $this->view->usrgrp = $usrgrp;
            $this->view->hasUserGroups = $hasUserGroups;
        } else {
            // If not logged, redirecting to system message page
			$message = 'You must login in order to link groups!';

			$url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index',
                                                'language' => $this->view->language),
                                          'lang_default', true);
			$this->flash($message, $url);
        }
    }

    /**
     * unlinkgroupAction
     *
     * Unlink group from group.
     */
    public function unlinkgroupAction()
    {
        $auth = Zend_Auth::getInstance();

        if ($auth->hasIdentity()) {
            $usrId = $auth->getIdentity()->user_id;

            $grpId = $this->_request->getParam('grpid');
            if (!isset($grpId)) {
                $redirectUrl = $this->_urlHelper->url(array('controller' => 'group',
                                                            'action' => 'index',
                                                            'language' => $this->view->language),
                                                      'lang_default', true);
                $this->_redirector->gotoUrl($redirectUrl);
            }

            $this->view->grpid = $grpId;

            $grpmodel = new Default_Model_Groups();
            $grp = $grpmodel->getGroupData($grpId);

            // Is user group admin?
            $grpadminmodel = new Default_Model_GroupAdmins();
            if (!$grpadminmodel->userIsAdmin($grpId, $usrId)) {
                $redirectUrl = $this->_urlHelper->url(array('controller' => 'group',
                                                            'action' => 'index',
                                                            'language' => $this->view->language),
                                                      'lang_default', true);
                $this->_redirector->gotoUrl($redirectUrl);
            }

            $grpHasGrpModel = new Default_Model_GroupHasGroup();
            $usrgrp = $grpHasGrpModel->getGroupGroups($grpId);

            if (!empty($usrgrp)) {
                $hasUserGroups = true;
                $usrgrp = array_merge($usrgrp['parents'], $usrgrp['childs']);
            } else {
                $hasUserGroups = false;
            }

            $this->view->grp = $grp;
            $this->view->usrgrp = $usrgrp;
            $this->view->hasUserGroups = $hasUserGroups;
        } else {
            // If not logged, redirecting to system message page
			$message = 'You must login in order to link groups!';

			$url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index',
                                                'language' => $this->view->language),
                                          'lang_default', true);
			$this->flash($message, $url);
        }
    }

    /**
     * makegrouplinkAction - Make group link
     */
    public function makegrouplinkAction()
    {
        $parentGrpId = $this->_request->getParam('parentgrpid');
        $this->view->parentgrpid = $parentGrpId;

        $childGrpId = $this->_request->getParam('childgrpid');
        $this->view->childgrpid = $childGrpId;

        if (!((isset($parentGrpId)) && (isset($childGrpId)))) {
            $redirectUrl = $this->_urlHelper->url(array('controller' => 'group',
                                                        'action' => 'index',
                                                        'language' => $this->view->language),
                                                  'lang_default', true);
            $this->_redirector->gotoUrl($redirectUrl);
        }

        $grphasgrpmodel = new Default_Model_GroupHasGroup();
        $grphasgrpmodel->addGroupToGroup($parentGrpId, $childGrpId);

        // TODO:
        // Tell the user that the link was created.

        // Redirect back to the current campaign's page.
        $target = $this->_urlHelper->url(array('groupid' => $parentGrpId,
                                               'language' => $this->view->language),
                                         'group_shortview', true);
        $this->_redirector->gotoUrl($target);
    }

    /**
     * removegrouplinkAction - Remove group link
     */
    public function removegrouplinkAction()
    {
        $parentGrpId = $this->_request->getParam('parentgrpid');
        $this->view->parentgrpid = $parentGrpId;

        $childGrpId = $this->_request->getParam('childgrpid');
        $this->view->childgrpid = $childGrpId;

        if (!((isset($parentGrpId)) && (isset($childGrpId)))) {
            $redirectUrl = $this->_urlHelper->url(array('controller' => 'group',
                                                        'action' => 'index',
                                                        'language' => $this->view->language),
                                                  'lang_default', true);
            $this->_redirector->gotoUrl($redirectUrl);
        }

        $grphasgrpmodel = new Default_Model_GroupHasGroup();
        $grphasgrpmodel->removeGroupFromGroup($parentGrpId, $childGrpId);
        $grphasgrpmodel->removeGroupFromGroup($childGrpId, $parentGrpId);

        // TODO:
        // Tell the user that the unlink was created.

        // Redirect back to the current campaign's page.
        $target = $this->_urlHelper->url(array('groupid' => $parentGrpId,
                                               'language' => $this->view->language),
                                         'group_shortview', true);
        $this->_redirector->gotoUrl($target);
    }

    /**
     * waitinlistAction
     *
     * Waiting list for group
     */
    public function waitinglistAction()
    {
        $auth = Zend_Auth::getInstance();

        if ($auth->hasIdentity()) {
            $usrId = $auth->getIdentity()->user_id;

            $grpId = $this->_request->getParam('grpid');
            if (!isset($grpId)) {
                $redirectUrl = $this->_urlHelper->url(array('controller' => 'group',
                                                            'action' => 'index',
                                                            'language' => $this->view->language),
                                                      'lang_default', true);
                $this->_redirector->gotoUrl($redirectUrl);
            }

            $this->view->grpid = $grpId;
            
            $usrHasGrpModel = new Default_Model_UserHasGroup();
            $usrHasGrpWaitingModel = new Default_Model_UserHasGroupWaiting();

            $grpmodel = new Default_Model_Groups();
            $grp = $grpmodel->getGroupData($grpId);

            // Accept or deny button was pressed
       		if ($this->getRequest()->isPost()) {
				// Get the IDs of the first and last selected user
				$firstUsrId = $this->getRequest()->getPost('accept_or_deny_first');
				$lastUsrId = $this->getRequest()->getPost('accept_or_deny_last');

                $privateMessagesModel = new Default_Model_PrivateMessages();
				// Accept or deny selected user
        		for ($i = $firstUsrId; $i <= $lastUsrId; $i++) {
        			if ($this->getRequest()->getPost('select_'.$i) == 'accept') {
                        $usrHasGrpWaitingModel->removeUserWaitingFromGroup($grpId, $i);
                        $usrHasGrpModel->addUserToGroup($grpId, $i);
                        // Send privamessage
                        $usermessage = Array();
                        $usermessage['privmsg_sender_id'] = 0;
                        $usermessage['privmsg_receiver_id'] = $i;
                        $usermessage['privmsg_header'] = $grp['group_name_grp'].' waiting list';
                        $link = $this->_urlHelper->url(array('groupid'    => $grpId,
                                                             'language'   => $this->view->language),
                                                       'group_shortview', true);
                        $usermessage['privmsg_message'] = 'You have been accepted to <a href="'.$link.'">'
                                                      .$grp['group_name_grp'].' group.</a>';
                        $privateMessagesModel->addMessage($usermessage);
        			} else if ($this->getRequest()->getPost('select_'.$i) == 'deny') {
        				$usrHasGrpWaitingModel->removeUserWaitingFromGroup($grpId, $i);
                        // Send privamessage
                        $usermessage = Array();
                        $usermessage['privmsg_sender_id'] = 0;
                        $usermessage['privmsg_receiver_id'] = $i;
                        $usermessage['privmsg_header'] = $grp['group_name_grp'].' waiting list';
                        $link = $this->_urlHelper->url(array('groupid'    => $grpId,
                                                             'language'   => $this->view->language),
                                                       'group_shortview', true);
                        $usermessage['privmsg_message'] = 'You have not been approved by the <a href="'.$link.'">'
                                                      .$grp['group_name_grp'].' group.</a>';
                        $privateMessagesModel->addMessage($usermessage);
        			}
        		}
			}

            $users = $usrHasGrpWaitingModel->getAllWaitingUsersInGroup($grpId);

            // Is user group admin?
            $grpadminmodel = new Default_Model_GroupAdmins();
            if (!$grpadminmodel->userIsAdmin($grpId, $usrId)) {
                $redirectUrl = $this->_urlHelper->url(array('controller' => 'group',
                                                            'action' => 'index',
                                                            'language' => $this->view->language),
                                                      'lang_default', true);
                $this->_redirector->gotoUrl($redirectUrl);
            }

            $this->view->grp = $grp;
            $this->view->users = $users;
        } else {
            // If not logged, redirecting to system message page
			$message = 'You must login in!';

			$url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index',
                                                'language' => $this->view->language),
                                          'lang_default', true);
			$this->flash($message, $url);
        }
    }

}
