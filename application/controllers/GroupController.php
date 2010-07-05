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
        $redirectUrl = $this->_urlHelper->url(array('controller' => 'group',
                                                    'action' => 'list',
                                                    'language' => $this->view->language),
                                              'lang_default', true);
        $this->_redirector->gotoUrl($redirectUrl);
    }

    /**
     * listAction - shows a list of all groups
     */
    function listAction()
    {
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
        $grpAdminsModel = new Default_Model_GroupAdmins();
        $campaignModel = new Default_Model_Campaigns();
        $grpAdmins = $grpAdminsModel->getGroupAdmins($grpId);
        $user = $auth->getIdentity();
        $grpData = $grpModel->getGroupData($grpId);
        $grpData['description_grp'] = str_replace("\n", '<br>', $grpData['description_grp']);
        $grpData['body_grp'] = str_replace("\n", '<br>', $grpData['body_grp']);

        // Group weblinks
        $groupWeblinksModel = new Default_Model_GroupWeblinks();
        $grpData['campaignWeblinks'] = $groupWeblinksModel->getGroupWeblinks($grpId);
        $i = 0;
        foreach($grpData['groupWeblinks'] as $weblink) {
            if (strlen($weblink['name_gwl']) == 0) {
                unset($grpData['groupWeblinks'][$i]);
            }
            $i++;
        }

        // Add data to the view.
        $this->view->grpId = $grpId;
        $this->view->grpData = $grpData;
        $this->view->grpUsers = $usrHasGrpModel->getAllUsersInGroup($grpId);
        $this->view->grpAdmins = $grpAdmins;
        $this->view->userHasGroup = $usrHasGrpModel;
        $this->view->campaigns = $campaignModel->getCampaignsByGroup($grpId);
        $this->view->userIsGroupAdmin = $this->checkIfArrayHasKeyWithValue($grpAdmins, 'id_usr', $user->user_id);
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
            $formData['groupdesc'] = $grpData['description_grp'];
            $formData['groupbody'] = $grpData['body_grp'];
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
                        $post['groupdesc'],
                        $post['groupbody']);

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
                        $post['groupdesc'],
                        $post['groupbody']);

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

            // Join the group.
            $usrHasGroupModel = new Default_Model_UserHasGroup();
            $usrHasGroupModel->addUserToGroup($grpId, $usrId);

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
                // Remove user from group.
                $usrHasGroupModel = new Default_Model_UserHasGroup();
                $usrHasGroupModel->removeUserFromGroup($grpId, $usrId);
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

    public function imageAction()
    {
        $form = new Default_Form_ProfileImageForm();
        $this->view->form = $form;
    }
}
