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
 *  warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
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
        $auth = Zend_Auth::getInstance();
        $logged_in = $auth->hasIdentity();

        $this->view->logged_in = $logged_in;

//	FIXME: Notice: Undefined variable: grps_new in /home/iiuusit/massidea/application/controllers/CampaignController.php on line 37
//      $this->view->groups = $grps_new;
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
                $this->_redirect($redirectUrl);
            }

            $grpAdminModel = new Default_Model_GroupAdmins();
            if (!$grpAdminModel->userIsAdmin($grpId, $usrId)) {
                // Only group admins can create campaigns.
                $target = $this->_urlHelper->url(
                    array(
                        'groupid'    => $grpId,
                        'language'   => $this->view->language),
                    'group_shortview', true);
                $this->_redirect($target);
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

                    // Set weblinks
                    $campaignWeblinksModel = new Default_Model_CampaignWeblinks();
                    if (isset($post['weblinks_name_site1']) && isset($post['weblinks_url_site1'])) {
                        $campaignWeblinksModel->setWeblink($newCampaign['id_cmp'], $post['weblinks_name_site1'],
                                $post['weblinks_url_site1'], 1);
                    }
                    if (isset($post['weblinks_name_site2']) && isset($post['weblinks_url_site2'])) {
                        $campaignWeblinksModel->setWeblink($newCampaign['id_cmp'], $post['weblinks_name_site2'],
                                $post['weblinks_url_site2'], 2);
                    }
                    if (isset($post['weblinks_name_site3']) && isset($post['weblinks_url_site3'])) {
                        $campaignWeblinksModel->setWeblink($newCampaign['id_cmp'], $post['weblinks_name_site3'],
                                $post['weblinks_url_site3'], 3);
                    }
                    if (isset($post['weblinks_name_site4']) && isset($post['weblinks_url_site4'])) {
                        $campaignWeblinksModel->setWeblink($newCampaign['id_cmp'], $post['weblinks_name_site4'],
                                $post['weblinks_url_site4'], 4);
                    }
                    if (isset($post['weblinks_name_site5']) && isset($post['weblinks_url_site5'])) {
                        $campaignWeblinksModel->setWeblink($newCampaign['id_cmp'], $post['weblinks_name_site5'],
                                $post['weblinks_url_site5'], 5);
                    }

					$filesModel = new Default_Model_Files();
					$files = $_FILES['content_file_upload'];
                    $filesModel->newFiles($newCampaign->id_cmp, "campaign", $files);

                    $target = $this->_urlHelper->url(
                        array(
                            'groupid'    => $grpId,
                            'language'   => $this->view->language),
                        'group_shortview', true);
                    $this->_redirect($target);
                }
            }
        } else {
            $redirectUrl = $this->_urlHelper->url(array('controller' => 'campaign',
                                                        'action' => 'list',
                                                        'language' => $this->view->language),
                                                  'lang_default', true);
            $this->_redirect($redirectUrl);
        }
    }

    public function removeAction()
    {
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
                $this->_redirect($target);
            }

            // Get group id from campaign info.
            $cmpModel = new Default_Model_Campaigns();
            $cmp = $cmpModel->getCampaignById($cmpId)->toArray();
            $grpId = $cmp['id_grp_cmp'];

            // Only group admins get to remove campaigns.
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
                $this->_redirect($redirectUrl);
            }

            // Delete campaign.
            $cmpModel->removeCampaign($cmpId);

            // Redirect to the group page.
            $target = $this->_urlHelper->url(
                array(
                    'groupid'    => $grpId,
                    'language'   => $this->view->language),
                'group_shortview', true);
            $this->_redirect($target);
        } else {
            // Not logged in - redirect to the group page.
            $target = $this->_urlHelper->url(
                array(
                    'groupid'    => $grpId,
                    'language'   => $this->view->language),
                'group_shortview', true);
            $this->_redirect($target);
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

        $user = $auth->getIdentity();
        $cmpid = $this->_request->getParam('cmpid');

        $cmpmodel = new Default_Model_Campaigns();
        // Check if campaign exists
        if (!isset($cmpid) || !$cmpmodel->campaignExists($cmpid)) {
            $target = $this->_urlHelper->url(array('controller'    => 'campaign',
                                                   'action'        => 'index',
                                                   'language'      => $this->view->language),
                                             'lang_default', true);
            $this->_redirect($target);
        }

        // Get campaign & its contents.
        $cmp = $cmpmodel->getCampaignById($cmpid)->toArray();
        $cmp['ingress_cmp'] = str_replace("\n", '<br>', $cmp['ingress_cmp']);
        $cmp['description_cmp'] = str_replace("\n", '<br>', $cmp['description_cmp']);
        $cnts = $cmpmodel->getAllContentsInCampaign($cmpid);

        // If user has identity
        if ($auth->hasIdentity()) {
            $this->view->identity = true;

            $uhgModel = new Default_Model_UserHasGroup();
            $this->view->userHasGroup = $uhgModel->userHasGroup($cmp['id_grp_cmp'], $user->user_id);

            // Get group admins.
            $grpAdminsModel = new Default_Model_GroupAdmins();
            $grpAdmins = $grpAdminsModel->getGroupAdmins($cmp['id_grp_cmp']);
            $this->view->userIsGroupAdmin = $this->checkIfArrayHasKeyWithValue($grpAdmins, 'id_usr', $user->user_id);
        } else {
            $this->view->identity = false;
        }

        // Campaign weblinks
        $campaignWeblinksModel = new Default_Model_CampaignWeblinks();
        $cmp['campaignWeblinks'] = $campaignWeblinksModel->getCampaignWeblinks($cmpid);
        $i = 0;
        foreach($cmp['campaignWeblinks'] as $weblink) {
            if (strlen($weblink['name_cwl']) == 0 || strlen($weblink['url_cwl']) == 0) {
                unset($cmp['campaignWeblinks'][$i]);
            }
            $i++;
        }

        // Get group info.
        $grpmodel = new Default_Model_Groups();
        $grp = $grpmodel->getGroupData($cmp['id_grp_cmp']);
        $grpname = $grp['group_name_grp'];

        // Get campaign campaigns
        $cmpHasCmpModel = new Default_Model_CampaignHasCampaign();
        $linkedcampaigns = $cmpHasCmpModel->getCampaignCampaigns($cmpid);
        $linkedcampaigns = array_merge($linkedcampaigns['parents'], $linkedcampaigns['childs']);

        // Get files
        $filesModel = new Default_Model_Files();
        $files = $filesModel->getFilenames($cmpid, "campaign");

        $comments = new Oibs_Controller_Plugin_Comments("campaign", $cmpid);
        if ($this->view->identity) $comments->allowComments(true);
  		$this->view->jsmetabox->append('commentUrls', $comments->getUrls());
		$comments->loadComments();

		$this->view->hasFeeds 		 = Oibs_Controller_Plugin_RssReader::hasFeeds($cmpid, "campaign");
		$this->view->comments		 = $comments;
        $this->view->campaign        = $cmp;
        $this->view->cmpcnts         = $cnts;
        $this->view->grpname         = $grpname;
        $this->view->linkedcampaigns = $linkedcampaigns;
        $this->view->status          = $cmpmodel->getStatus($cmpid);
        $this->view->files 			 = $files;

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
                $this->_redirect($target);
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
                $this->_redirect($redirectUrl);
            }

			// Get contents filenames from database
			$filesModel = new Default_Model_Files();
			$filenames = $filesModel->getFilenames($cmpId, "campaign");
			$formData['filenames'] = $filenames;

            // Create & populate the form.
            $form = new Default_Form_AddCampaignForm($this, array(
                'mode'     => 'edit',
                'startdate' => $cmp['start_time_cmp'],
            	'fileNames' => $filenames,
            ));
            $formData = array();
            $formData['campaign_name'] = $cmp['name_cmp'];
            $formData['campaign_ingress'] = $cmp['ingress_cmp'];
            $formData['campaign_desc'] = $cmp['description_cmp'];
            $formData['campaign_start'] = $cmp['start_time_cmp'];
            if ($cmp['end_time_cmp'] != '0000-00-00')
                $formData['campaign_end'] = $cmp['end_time_cmp'];

            // Get campaign weblinks
            $campaignWeblinksModel = new Default_Model_CampaignWeblinks();
            $campaignWeblinks = $campaignWeblinksModel->getCampaignWeblinks($cmpId);
            foreach ($campaignWeblinks as $campaignWeblink) {
                $formData['weblinks_name_site'.$campaignWeblink['count_cwl']] = $campaignWeblink['name_cwl'];
                $formData['weblinks_url_site'.$campaignWeblink['count_cwl']] = $campaignWeblink['url_cwl'];
            }

            $form->populate($formData);

            $this->view->form = $form;

            $this->view->cmpName = $cmp['name_cmp'];

            // If the form is posted and valid, save the changes to db.
            $request = $this->getRequest();
            if ($request->isPost()) {
                $post = $request->getPost();
                if ($form->isValid($post)) {
                    // Change existing group info.
                    if (isset($post['campaign_start'])) {
                        $cmpModel->editCampaign(
                            $cmpId,
                            $post['campaign_name'],
                            $post['campaign_ingress'],
                            $post['campaign_desc'],
                            $post['campaign_start'],
                            $post['campaign_end']
                        );
                    } else {
                        $cmpModel->editCampaign(
                            $cmpId,
                            $post['campaign_name'],
                            $post['campaign_ingress'],
                            $post['campaign_desc'],
                            $cmp['start_time_cmp'],
                            $cmp['end_time_cmp']
                        );
                    }

                    // Set weblinks
                    if (isset($post['weblinks_name_site1']) && isset($post['weblinks_url_site1'])) {
                        $campaignWeblinksModel->setWeblink($cmpId, $post['weblinks_name_site1'], $post['weblinks_url_site1'], 1);
                    }
                    if (isset($post['weblinks_name_site2']) && isset($post['weblinks_url_site2'])) {
                        $campaignWeblinksModel->setWeblink($cmpId, $post['weblinks_name_site2'], $post['weblinks_url_site2'], 2);
                    }
                    if (isset($post['weblinks_name_site3']) && isset($post['weblinks_url_site3'])) {
                        $campaignWeblinksModel->setWeblink($cmpId, $post['weblinks_name_site3'], $post['weblinks_url_site3'], 3);
                    }
                    if (isset($post['weblinks_name_site4']) && isset($post['weblinks_url_site4'])) {
                        $campaignWeblinksModel->setWeblink($cmpId, $post['weblinks_name_site4'], $post['weblinks_url_site4'], 4);
                    }
                    if (isset($post['weblinks_name_site5']) && isset($post['weblinks_url_site5'])) {
                        $campaignWeblinksModel->setWeblink($cmpId, $post['weblinks_name_site5'], $post['weblinks_url_site5'], 5);
                    }

					$filesModel = new Default_Model_Files();
					$files = $_FILES['content_file_upload'];
                    $filesModel->newFiles($cmpId, "campaign", $files);

                    if (isset($post['uploadedFiles'])) $filesModel->deleteCertainFiles($cmpId, "campaign", $post['uploadedFiles']);

                    // Redirect back to the campaign page.
                    $target = $this->_urlHelper->url(
                        array(
                            'cmpid' => $cmpId,
                            'language' => $this->view->language),
                        'campaign_view', true
                    );
                    $this->_redirect($target);
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
            $this->_redirect($target);
        }
    }

    /**
     * listAction - shows a list of all campaigns
     */
    function listAction()
    {
        $redirectUrl = $this->_urlHelper->url(array('controller' => 'campaign',
                                                    'action' => 'index',
                                                    'language' => $this->view->language),
                                              'lang_default', true);
        $this->_redirect($redirectUrl);
        /*
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
        */
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
                $this->_redirect($redirectUrl);
            }

            // Check if campaign is open
            $cmpmodel = new Default_Model_Campaigns();
            if (!$cmpmodel->isOpen($cmpId)) {
                $redirectUrl = $this->_urlHelper->url(array('controller' => 'campaign',
                                                            'action' => 'index',
                                                            'language' => $this->view->language),
                                                      'lang_default', true);
                $this->_redirect($redirectUrl);
            }

            $this->view->cmpid = $cmpId;

            $cmp = $cmpmodel->getCampaignById($cmpId);

            // Only members of the group that created the campaign are allowed
            // to link content.
            $grpId = $cmp['id_grp_cmp'];
            $uhgmodel = new Default_Model_UserHasGroup();
            if (!$uhgmodel->userHasGroup($grpId, $usrId)) {
                // Redirect back to the campaign page.
                $target = $this->_urlHelper->url(
                    array(
                        'cmpid' => $cmpId,
                        'language' => $this->view->language),
                    'campaign_view', true
                );
                $this->_redirect($target);
            }

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
			$this->addFlashMessage($message, $url);
        }
    }

    /**
     * linkcampaignAction
     *
     * Link campaign to campaign.
     */
    public function linkcampaignAction()
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
                $this->_redirect($redirectUrl);
            }

            $this->view->cmpid = $cmpId;

            $cmpmodel = new Default_Model_Campaigns();
            $cmp = $cmpmodel->getCampaignById($cmpId);

            $usrmodel = new Default_Model_User();

            $usrcmp = $usrmodel->getUserCampaigns($usrId);

            $cmpHasCmpModel = new Default_Model_CampaignHasCampaign();
            if (!empty($usrcmp)) {
                $i = 0;
                foreach ($usrcmp as $campaign) {
                    if ($cmpHasCmpModel->checkIfCampaignHasCampaign($cmpId, $campaign['id_cmp']) ||
                        $cmpHasCmpModel->checkIfCampaignHasCampaign($campaign['id_cmp'], $cmpId) ||
                        $cmpId == $campaign['id_cmp']) {
                            unset($usrcmp[$i]);
                    }
                    $i++;
                }
            }

            if (!empty($usrcmp)) {
                $hasUserCampaigns = true;
            } else {
                $hasUserCampaigns = false;
            }

            $this->view->cmp = $cmp;
            $this->view->usrcmp = $usrcmp;
            $this->view->hasUserCampaigns = $hasUserCampaigns;
        } else {
            // If not logged, redirecting to system message page
			$message = 'campaign-link-not-logged';

			$url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index',
                                                'language' => $this->view->language),
                                          'lang_default', true);
			$this->addFlashMessage($message, $url);
        }
    }

    /**
     * unlinkcampaignAction
     *
     * Unlink campaign from campaign.
     */
    public function unlinkcampaignAction()
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
                $this->_redirect($redirectUrl);
            }

            $this->view->cmpid = $cmpId;

            $cmpmodel = new Default_Model_Campaigns();
            $cmp = $cmpmodel->getCampaignById($cmpId);

            // Is user campaing group admin?
            $grpadminmodel = new Default_Model_GroupAdmins();
            if (!$grpadminmodel->userIsAdmin($cmp['id_grp_cmp'], $usrId)) {
                $redirectUrl = $this->_urlHelper->url(array('controller' => 'campaign',
                                                            'action' => 'index',
                                                            'language' => $this->view->language),
                                                      'lang_default', true);
                $this->_redirect($redirectUrl);
            }

            $cmpHasCmpModel = new Default_Model_CampaignHasCampaign();
            $usrcmp = $cmpHasCmpModel->getCampaignCampaigns($cmpId);

            if (!empty($usrcmp)) {
                $hasUserCampaigns = true;
                $usrcmp = array_merge($usrcmp['parents'], $usrcmp['childs']);
            } else {
                $hasUserCampaigns = false;
            }

            $this->view->cmp = $cmp;
            $this->view->usrcmp = $usrcmp;
            $this->view->hasUserCampaigns = $hasUserCampaigns;
        } else {
            // If not logged, redirecting to system message page
			$message = 'campaign-link-not-logged';

			$url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index',
                                                'language' => $this->view->language),
                                          'lang_default', true);
			$this->addFlashMessage($message, $url);
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
                $this->_redirect($redirectUrl);
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
			$this->addFlashMessage($message, $url);
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
                $this->_redirect($redirectUrl);
            }

            $groupAdminsModel = new Default_Model_GroupAdmins();
            $groupAdmins = $groupAdminsModel->getGroupAdmins($groupId);
            $user = $auth->getIdentity();

            if (!$groupAdminsModel->userIsAdmin($groupId, $user->user_id)) {
                $redirectUrl = $this->_urlHelper->url(array('controller' => 'campaign',
                                                            'action' => 'index',
                                                            'language' => $this->view->language),
                                                      'lang_default', true);
                $this->_redirect($redirectUrl);
            }

            $campaignModel = new Default_Model_Campaigns();
            $campaignexists = $campaignModel->campaignExists($relatestoid);

            if ($campaignexists) {
                $relatesToCampaign = $campaignModel->getCampaignById($relatestoid);
                $this->view->relatesToCampaignName = $relatesToCampaign['name_cmp'];
				$campaignContents = $campaignModel->getAllContentsInCampaign($relatestoid);
                $campaignFlagContents = array();
                $campaignNormalContents = array();
                $contentflagmodel = new Default_Model_ContentFlags();
                // Check if content is flaged
                foreach ($campaignContents as $content) {
                    $cfl_ids = $contentflagmodel->getFlagsByContentId($content['id_cnt']);
                    if (!empty($cfl_ids)) {
                        $campaignFlagContents[] = $content;
                    } else {
                        $campaignNormalContents[] = $content;
                    }
                }

            }
            $this->view->campaignexists = $campaignexists;
            $this->view->relatesToId = $relatestoid;
            $this->view->contents = $campaignNormalContents;
            $this->view->flagcontents = $campaignFlagContents;
            $this->view->userIsGroupAdmin = $this->checkIfArrayHasKeyWithValue($groupAdmins, 'id_usr', $user->user_id);
		} else {
			// If not logged, redirecting to system message page
			$message = 'content-link-not-logged';

			$url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index',
                                                'language' => $this->view->language),
                                          'lang_default', true);
			$this->addFlashMessage($message, $url);
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
            $this->_redirect($redirectUrl);
        }

        // Check if campaign is open
        $cmpModel = new Default_Model_Campaigns();
        if (!$cmpModel->isOpen($cmpId)) {
            $redirectUrl = $this->_urlHelper->url(array('controller' => 'campaign',
                                                        'action' => 'index',
                                                        'language' => $this->view->language),
                                                  'lang_default', true);
            $this->_redirect($redirectUrl);
        }

        $cmphascntmodel = new Default_Model_CampaignHasContent();
        $cmphascntmodel->addContentToCampaign($cmpId, $cntId);

        // TODO:
        // Tell the user that the link was created.

        // Redirect back to the current campaign's page.
        $target = $this->_urlHelper->url(array('cmpid' => $cmpId,
                                               'language' => $this->view->language),
                                         'campaign_view', true);
        $this->_redirect($target);
    }

    /**
     * makecampaignlinkAction - Make campaign link
     */
    public function makecampaignlinkAction()
    {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $parentCmpId = $this->_request->getParam('parentcmpid');
            $this->view->parentcmpid = $parentCmpId;

            $childCmpId = $this->_request->getParam('childcmpid');
            $this->view->childcmpid = $childCmpId;

            if (!((isset($parentCmpId)) && (isset($childCmpId)))) {
                $redirectUrl = $this->_urlHelper->url(array('controller' => 'campaign',
                                                            'action' => 'index',
                                                            'language' => $this->view->language),
                                                      'lang_default', true);
                $this->_redirect($redirectUrl);
            }

            $cmphascmpmodel = new Default_Model_CampaignHasCampaign();
            if (!$cmphascmpmodel->checkIfCampaignHasCampaign($parentCmpId, $childCmpId) &&
                !$cmphascmpmodel->checkIfCampaignHasCampaign($childCmpId, $parentCmpId)) {
                    $cmphascmpmodel->addCampaignToCampaign($parentCmpId, $childCmpId);
            }

            // TODO:
            // Tell the user that the link was created.

            // Redirect back to the current campaign's page.
            $target = $this->_urlHelper->url(array('cmpid' => $parentCmpId,
                                                   'language' => $this->view->language),
                                             'campaign_view', true);
            $this->_redirect($target);
        } else {
            $target = $this->_urlHelper->url(array('controller' => 'campaign',
                                                   'action'     => 'index',
                                                   'language'   => $this->view->language),
                                             'lang_default', true);
            $this->_redirect($target);
        }
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
        // Get authentication
		$auth = Zend_Auth::getInstance();
		// If user has identity
		if ($auth->hasIdentity())
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
                $this->_redirect($redirectUrl);
            }

            $cntHasUsrModel = new Default_Model_ContentHasUser();

            $usrId = $auth->getIdentity()->user_id;
            if (!$cntHasUsrModel->contentHasOwner($usrId, $cntId)) {
                $redirectUrl = $this->_urlHelper->url(array('controller' => 'account',
                                                            'action' => 'view',
                                                            'user' => $auth->getIdentity()->username,
                                                            'language' => $this->language),
                                                      'lang_default', true);
                $this->_redirect($redirectUrl);
            }

            $cmphascntmodel = new Default_Model_CampaignHasContent();
            $cmphascntmodel->removeContentFromCampaign($cmpId, $cntId);

            // TODO:
            // Tell the user that the unlink was created.

            // Redirect back to the user page
            $redirectUrl = $this->_urlHelper->url(array('controller' => 'account',
                                                        'action' => 'view',
                                                        'user' => $auth->getIdentity()->username,
                                                        'language' => $this->language),
                                                  'lang_default', true);
            $this->_redirect($redirectUrl);
        } else {
            // If not logged, redirecting to system message page
			$message = 'content-link-not-logged';

			$url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index',
                                                'language' => $this->view->language),
                                          'lang_default', true);
			$this->addFlashMessage($message, $url);
        }
    }

    /**
     * removeadminlinkAction
     *
     * Remove link to content from campaign
     *
     * @author Mikko Korpinen
     */
    public function removeadminlinksAction()
    {
        // Get authentication
		$auth = Zend_Auth::getInstance();
		// If user has identity
		if ($auth->hasIdentity())
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
                $this->_redirect($redirectUrl);
            }

            $cmpModel = new Default_Model_Campaigns();
            $cmp = $cmpModel->getCampaignById($cmpId);
            $grpId = $cmp['id_grp_cmp'];

            $usrId = $auth->getIdentity()->user_id;
            $grpadminmodel = new Default_Model_GroupAdmins();
            if (!$grpadminmodel->userIsAdmin($grpId, $usrId)) {
                $redirectUrl = $this->_urlHelper->url(array('controller' => 'campaign',
                                                            'action' => 'index',
                                                            'language' => $this->view->language),
                                                      'lang_default', true);
                $this->_redirect($redirectUrl);
            }

            $cmphascntmodel = new Default_Model_CampaignHasContent();
            $cmphascntmodel->removeContentFromCampaign($cmpId, $cntId);

            // TODO:
            // Tell the user that the unlink was created.

            // Redirect back to the current campaign's page.
            $target = $this->_urlHelper->url(array('cmpid' => $cmpId,
                                                   'language' => $this->view->language),
                                             'campaign_view', true);
            $this->_redirect($target);
        } else {
            // If not logged, redirecting to system message page
			$message = 'content-link-not-logged';

			$url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index',
                                                'language' => $this->view->language),
                                          'lang_default', true);
			$this->addFlashMessage($message, $url);
        }
    }

    /**
     * removecampaignlinkAction - Remove campaign link
     */
    public function removecampaignlinkAction()
    {
        // Get authentication
		$auth = Zend_Auth::getInstance();
		// If user has identity
		if ($auth->hasIdentity())
		{
            $parentCmpId = $this->_request->getParam('parentcmpid');
            $this->view->parentcmpid = $parentCmpId;

            $childCmpId = $this->_request->getParam('childcmpid');
            $this->view->childcmpid = $childCmpId;

            if (!((isset($parentCmpId)) && (isset($childCmpId)))) {
                $redirectUrl = $this->_urlHelper->url(array('controller' => 'campaign',
                                                            'action' => 'index',
                                                            'language' => $this->view->language),
                                                      'lang_default', true);
                $this->_redirect($redirectUrl);
            }

            $cmpModel = new Default_Model_Campaigns();
            $cmp = $cmpModel->getCampaignById($parentCmpId);
            $grpId = $cmp['id_grp_cmp'];

            $usrId = $auth->getIdentity()->user_id;
            $grpadminmodel = new Default_Model_GroupAdmins();
            if (!$grpadminmodel->userIsAdmin($grpId, $usrId)) {
                $redirectUrl = $this->_urlHelper->url(array('controller' => 'campaign',
                                                            'action' => 'index',
                                                            'language' => $this->view->language),
                                                      'lang_default', true);
                $this->_redirect($redirectUrl);
            }

            $cmphascmpmodel = new Default_Model_CampaignHasCampaign();
            $cmphascmpmodel->removeCampaignFromCampaign($parentCmpId, $childCmpId);
            $cmphascmpmodel->removeCampaignFromCampaign($childCmpId, $parentCmpId);

            // TODO:
            // Tell the user that the unlink was created.

            // Redirect back to the current campaign's page.
            $target = $this->_urlHelper->url(array('cmpid' => $parentCmpId,
                                                   'language' => $this->view->language),
                                             'campaign_view', true);
            $this->_redirect($target);
        } else {
            // If not logged, redirecting to system message page
			$message = 'content-link-not-logged';

			$url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index',
                                                'language' => $this->view->language),
                                          'lang_default', true);
			$this->addFlashMessage($message, $url);
        }
    }

    function endAction()
    {
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
                $this->_redirect($target);
            }

            // Get group id from campaign info.
            $cmpModel = new Default_Model_Campaigns();
            $cmp = $cmpModel->getCampaignById($cmpId)->toArray();
            $grpId = $cmp['id_grp_cmp'];

            // Only group admins can end campaign.
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
                $this->_redirect($redirectUrl);
            }

            // Check if campaign status is ended
            $status = $cmpModel->getStatus($cmpId);
            if ($status==="ended") {
                $redirectUrl = $this->_urlHelper->url(
                    array(
                        'controller' => 'campaign',
                        'action' => 'index',
                        'language' => $this->view->language),
                    'lang_default', true
                );
                $this->_redirect($redirectUrl);
            }

            // Chang end date to yesterday
            $cmpModel->endCampaign($cmpId, $cmp['start_time_cmp']);

            // Redirect back to the campaign page.
            $target = $this->_urlHelper->url(
                array(
                    'cmpid' => $cmpId,
                    'language' => $this->view->language),
                'campaign_view', true
            );
            $this->_redirect($target);
        } else {
            // Not logged in.
            $redirectUrl = $this->_urlHelper->url(
                array(
                    'controller' => 'campaign',
                    'action' => 'index',
                    'language' => $this->view->language),
                'lang_default', true
            );
            $this->_redirect($redirectUrl);
        }
    }

}
