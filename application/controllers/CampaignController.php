<?php
/**
 *  CampaignController
 *
 *  Copyright (c) <2010>, Mikko Aatola
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
 *  CampaignController - class
 *
 *  @package        controllers
 *  @author         Mikko Aatola
 *  @copyright      2010 MassIdea.org
 *  @license        GPL v2
 *  @version        1.0
 */ 
class CampaignController extends Oibs_Controller_CustomController
{
    public function indexAction() 
    {
        $redirectUrl = $this->_urlHelper->url(array('controller' => 'campaign',
                                                    'action' => 'list',
                                                    'language' => $this->view->language),
                                              'lang_default', true);
        $this->_redirector->gotoUrl($redirectUrl);
    }
    
    /**
     * createAction
     *
     * Show the campaign creation page
     */
    public function createAction()
    {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $usrId = $auth->getIdentity()->user_id;
            $grpId = $this->_request->getParam('grpid');

            if (!$grpId) {
                $redirectUrl = $this->_urlHelper->url(
                    array(
                        'controller' => 'campaign',
                        'action' => 'list',
                        'language' => $this->view->language),
                    'lang_default', true);
                $this->_redirector->gotoUrl($redirectUrl);
            }

            $grpAdminModel = new Default_Model_GroupAdmins();
            if (!$grpAdminModel->userIsAdmin($grpId, $usrId)) {
                // Only group admins can create campaigns.
                $target = $this->_urlHelper->url(
                    array(
                        'groupid'    => $grpId,
                        'language'   => $this->view->language),
                    'group_shortview', true);
                $this->_redirector->gotoUrl($target);
            }

            $this->view->grpid = $grpId;

            // Add the "add new campaign"-form to the view.
            $form = new Default_Form_AddCampaignForm();
            $this->view->form = $form;

            // Handle posted form.
            $request = $this->getRequest();
            if ($request->isPost()) {
                $post = $request->getPost();
                if ($form->isValid($post)) {
                    $campaignModel = new Default_Model_Campaigns();

                    $name = $post['campaign_name'];
                    $ingress = $post['campaign_ingress'];
                    $desc = $post['campaign_desc'];
                    $start = $post['campaign_start'];
                    $end = $post['campaign_end'];

                    $newCampaign = $campaignModel->createCampaign(
                        $name, $ingress, $desc, $start, $end, $grpId);

                    $target = $this->_urlHelper->url(
                        array(
                            'groupid'    => $grpId,
                            'language'   => $this->view->language),
                        'group_shortview', true);
                    $this->_redirector->gotoUrl($target);
                }
            }
        } else {
            $redirectUrl = $this->_urlHelper->url(array('controller' => 'campaign',
                                                        'action' => 'list',
                                                        'language' => $this->view->language),
                                                  'lang_default', true);
            $this->_redirector->gotoUrl($redirectUrl);
        }
    }
    
    /**
     * viewAction
     *
     * Show the campaign view page
     */
    public function viewAction()
    {
        $auth = Zend_Auth::getInstance();

        // If user has identity
<<<<<<< HEAD
        if ($auth->hasIdentity()) {
            $this->view->identity = true;
        } else {
            $this->view->identity = false;
        }
=======
       // if ($auth->hasIdentity()) {
            $user = $auth->getIdentity();
            $cmpid = $this->_request->getParam('cmpid');
>>>>>>> 27739705fd091c228ad09a30e4468e55087f6a5c

        $user = $auth->getIdentity();
        $cmpid = $this->_request->getParam('cmpid');

        // Get campaign & its contents.
        $cmpmodel = new Default_Model_Campaigns();
        $cmp = $cmpmodel->getCampaignById($cmpid)->toArray();
        $cmp['ingress_cmp'] = str_replace("\n", '<br>', $cmp['ingress_cmp']);
        $cmp['description_cmp'] = str_replace("\n", '<br>', $cmp['description_cmp']);
        $cnts = $cmpmodel->getAllContentsInCampaign($cmpid);

        // Get group admins.
        $grpAdminsModel = new Default_Model_GroupAdmins();
        $grpAdmins = $grpAdminsModel->getGroupAdmins($cmp['id_grp_cmp']);
        $this->view->userIsGroupAdmin = $this->checkIfArrayHasKeyWithValue($grpAdmins, 'id_usr', $user->user_id);

<<<<<<< HEAD
        // Get group info.
        $grpmodel = new Default_Model_Groups();
        $grp = $grpmodel->getGroupData($cmp['id_grp_cmp']);
        $grpname = $grp['group_name_grp'];

        $this->view->campaign = $cmp;
        $this->view->cmpcnts  = $cnts;
        $this->view->grpname  = $grpname;
=======
            $this->view->campaign = $cmp;
            $this->view->cmpcnts  = $cnts;
            $this->view->grpname  = $grpname;
/*        } else {
            // Campaigns are only visible to registered users.
        }*/
>>>>>>> 27739705fd091c228ad09a30e4468e55087f6a5c
    }

    function editAction() {
        $auth = Zend_Auth::getInstance();

        if ($auth->hasIdentity()) {
            $cmpId = $this->_request->getParam('id');

            if (!$cmpId) {
                $target = $this->_urlHelper->url(
                    array(
                        'controller' => 'index',
                        'action' => 'index',
                        'language' => $this->view->language),
                    'lang_default', true
                );
                $this->_redirector->gotoUrl($target);
            }

            // Get group id from campaign info.
            $cmpModel = new Default_Model_Campaigns();
            $cmp = $cmpModel->getCampaignById($cmpId)->toArray();
            $grpId = $cmp['id_grp_cmp'];

            // Only group admins get to edit campaign info.
            $grpAdminsModel = new Default_Model_GroupAdmins();
            $grpAdmins = $grpAdminsModel->getGroupAdmins($grpId);
            $userIsGroupAdmin = $this->checkIfArrayHasKeyWithValue(
                $grpAdmins, 'id_usr', $auth->getIdentity()->user_id);
            if (!$userIsGroupAdmin) {
                $redirectUrl = $this->_urlHelper->url(
                    array(
                        'controller' => 'campaign',
                        'action' => 'index',
                        'language' => $this->view->language),
                    'lang_default', true
                );
                $this->_redirector->gotoUrl($redirectUrl);
            }

            // Create & populate the form.
            $form = new Default_Form_AddCampaignForm($this, 'edit');
            $formData = array();
            $formData['campaign_name'] = $cmp['name_cmp'];
            $formData['campaign_ingress'] = $cmp['ingress_cmp'];
            $formData['campaign_desc'] = $cmp['description_cmp'];
            $form->populate($formData);

            $this->view->form = $form;

            $this->view->cmpName = $cmp['name_cmp'];

            // If the form is posted and valid, save the changes to db.
            $request = $this->getRequest();
            if ($request->isPost()) {
                $post = $request->getPost();
                if ($form->isValid($post)) {
                    // Change existing group info.
                    $cmpModel->editCampaign(
                        $cmpId,
                        $post['campaign_name'],
                        $post['campaign_ingress'],
                        $post['campaign_desc']
                    );

                    // Redirect back to the campaign page.
                    $target = $this->_urlHelper->url(
                        array(
                            'cmpid' => $cmpId,
                            'language' => $this->view->language),
                        'campaign_view', true
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

    /**
     * listAction - shows a list of all campaigns
     */
    function listAction()
    {
        $grpmodel = new Default_Model_Groups();
        $cmpmodel = new Default_Model_Campaigns();

        // If you find a better way to do this, be my guest.
        $cmps = $cmpmodel->getAll()->toArray();
        $cmps_new = array();
        foreach ($cmps as $cmp) {
            $grp = $grpmodel->getGroupData($cmp['id_grp_cmp']);
            $cmp['group_name_grp'] = $grp['group_name_grp'];
            $cmps_new[] = $cmp;
        }

        $this->view->campaigns = $cmps_new;
    }

    /**
     * linkAction
     *
     * Link content to campaign.
     */
    public function linkAction()
    {
        $auth = Zend_Auth::getInstance();

        if ($auth->hasIdentity()) {
            $usrId = $auth->getIdentity()->user_id;

            $cmpId = $this->_request->getParam('cmpid');
            if (!isset($cmpId)) {
                $redirectUrl = $this->_urlHelper->url(array('controller' => 'campaign',
                                                            'action' => 'index',
                                                            'language' => $this->view->language),
                                                      'lang_default', true);
                $this->_redirector->gotoUrl($redirectUrl);
            }
            
            $this->view->cmpid = $cmpId;

            $cmpmodel = new Default_Model_Campaigns();
            $cmp = $cmpmodel->getCampaignById($cmpId);

            $usrmodel = new Default_Model_User();
            $usrcnt = $usrmodel->getUserContent($usrId);

            if (!empty($usrcnt)) {
                $cmpcnt = $cmpmodel->getAllContentsInCampaign($cmpId);

                $cnt = array();
                foreach ($usrcnt as $usercontent) {
                    if (!$this->checkIfArrayHasKeyWithValue($cmpcnt, 'id_cnt', $usercontent['id_cnt'])) {
                        $cnt[] = $usercontent;
                    }
                }
                $hasUserContents = true;
            } else {
                $hasUserContents = false;
            }

            $this->view->cmp = $cmp;
            $this->view->usrcnt = $cnt;
            $this->view->hasUserContents = $hasUserContents;
        } else {
            // If not logged, redirecting to system message page
			$message = 'campaign-link-not-logged';

			$url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index',
                                                'language' => $this->view->language),
                                          'lang_default', true);
			$this->flash($message, $url);
        }
    }

    /**
     * unlinkAction
     *
     * Shows user contents which are linket to campaign. User can select and remove link.
     *
     * @author Mikko Korpinen
     */
    public function unlinkAction()
    {
        // Get authentication
		$auth = Zend_Auth::getInstance();
		// If user has identity
		if ($auth->hasIdentity())
		{
			// Get requests
			$params = $this->getRequest()->getParams();

			$relatestoid = $params['relatestoid'];

            if (!isset($relatestoid)) {
                $redirectUrl = $this->_urlHelper->url(array('controller' => 'campaign',
                                                            'action' => 'index',
                                                            'language' => $this->view->language),
                                                      'lang_default', true);
                $this->_redirector->gotoUrl($redirectUrl);
            }

            $contenttype = '';
            $campaigns = array();

            $model_content = new Default_Model_Content();
            $contentexists = $model_content->checkIfContentExists($relatestoid);

            if ($contentexists) {
                $relatesToContent = $model_content->getDataAsSimpleArray($relatestoid);
                $this->view->relatesToContentTitle = $relatesToContent['title_cnt'];

                $model_content_types = new Default_Model_ContentTypes();
                $model_cmp_has_cnt = new Default_Model_CampaignHasContent();

                $contenttype = $model_content_types->getTypeById($relatesToContent['id_cty_cnt']);

                $contentCampaigns = $model_cmp_has_cnt->getContentCampaigns($relatestoid);
            }
            $this->view->contentexists = $contentexists;
            $this->view->relatesToId = $relatestoid;
            $this->view->linkingContentType = $contenttype;
            $this->view->campaigns = $contentCampaigns;
		} else {
			// If not logged, redirecting to system message page
			$message = 'content-link-not-logged';

			$url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index',
                                                'language' => $this->view->language),
                                          'lang_default', true);
			$this->flash($message, $url);
		}
    }

    /**
     * adminunlinkAction
     *
     * Shows contents which are linket to campaign. Campaign(group) admin can select and remove link.
     *
     * @author Mikko Korpinen
     */
    public function adminunlinkAction()
    {
        // Get authentication
		$auth = Zend_Auth::getInstance();
		// If user has identity
		if ($auth->hasIdentity())
		{
			// Get requests
			$params = $this->getRequest()->getParams();

			$relatestoid = $params['relatestoid'];
            $groupId = $params['groupid'];

            if (!isset($relatestoid) && !isset($groupId)) {
                $redirectUrl = $this->_urlHelper->url(array('controller' => 'campaign',
                                                            'action' => 'index',
                                                            'language' => $this->view->language),
                                                      'lang_default', true);
                $this->_redirector->gotoUrl($redirectUrl);
            }

            $groupAdminsModel = new Default_Model_GroupAdmins();
            $groupAdmins = $groupAdminsModel->getGroupAdmins($groupId);
            $user = $auth->getIdentity();

            $campaignModel = new Default_Model_Campaigns();
            $campaignexists = $campaignModel->campaignExists($relatestoid);

            if ($campaignexists) {
                $relatesToCampaign = $campaignModel->getCampaignById($relatestoid);
                $this->view->relatesToCampaignName = $relatesToCampaign['name_cmp'];
				$campaignContents = $campaignModel->getAllContentsInCampaign($relatestoid);
            }
            $this->view->campaignexists = $campaignexists;
            $this->view->relatesToId = $relatestoid;
            $this->view->contents = $campaignContents;
            $this->view->userIsGroupAdmin = $this->checkIfArrayHasKeyWithValue($groupAdmins, 'id_usr', $user->user_id);
		} else {
			// If not logged, redirecting to system message page
			$message = 'content-link-not-logged';

			$url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index',
                                                'language' => $this->view->language),
                                          'lang_default', true);
			$this->flash($message, $url);
		}
    }

    public function makelinkAction()
    {
        $cmpId = $this->_request->getParam('cmpid');
        $this->view->cmpid = $cmpId;

        $cntId = $this->_request->getParam('cntid');
        $this->view->cntid = $cntId;

        if (!((isset($cmpId)) && (isset($cntId)))) {
            $redirectUrl = $this->_urlHelper->url(array('controller' => 'campaign',
                                                        'action' => 'index',
                                                        'language' => $this->view->language),
                                                  'lang_default', true);
            $this->_redirector($redirectUrl);
        }

        $cmphascntmodel = new Default_Model_CampaignHasContent();
        $cmphascntmodel->addContentToCampaign($cmpId, $cntId);

        // TODO:
        // Tell the user that the link was created.

        // Redirect back to the current campaign's page.
        $target = $this->_urlHelper->url(array('cmpid' => $cmpId,
                                               'language' => $this->view->language),
                                         'campaign_view', true);
        $this->_redirector->gotoUrl($target);
    }

    /**
     * removelinkAction
     *
     * Remove link to content from campaign
     *
     * @author Mikko Korpinen
     */
    public function removelinksAction()
    {
        $cmpId = $this->_request->getParam('cmpid');
        $this->view->cmpid = $cmpId;

        $cntId = $this->_request->getParam('cntid');
        $this->view->cntid = $cntId;

        if (!((isset($cmpId)) && (isset($cntId)))) {
            $redirectUrl = $this->_urlHelper->url(array('controller' => 'campaign',
                                                        'action' => 'index',
                                                        'language' => $this->view->language),
                                                  'lang_default', true);
            $this->_redirector($redirectUrl);
        }

        $cmphascntmodel = new Default_Model_CampaignHasContent();
        $cmphascntmodel->removeContentFromCampaign($cmpId, $cntId);

        // TODO:
        // Tell the user that the link was created.

        // Redirect back to the current campaign's page.
        $target = $this->_urlHelper->url(array('cmpid' => $cmpId,
                                               'language' => $this->view->language),
                                         'campaign_view', true);
        $this->_redirector->gotoUrl($target);
    }
<<<<<<< HEAD


}
=======
}
>>>>>>> 27739705fd091c228ad09a30e4468e55087f6a5c
