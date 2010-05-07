<?php
/**
 *  CampaignController
 *
 *  Copyright (c) <2009>, Joel Peltonen <joel.peltonen@cs.tamk.fi>
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
 *  ContentController - class
 *
 *  @package        controllers
 *  @author         Markus RIihel�
 *  @copyright      2009 MassIdea.org
 *  @license        GPL v2
 *  @version        1.0
 */ 
class CampaignController extends Oibs_Controller_CustomController
{
    /**
     * The default action - show the home page
     */
    public function indexAction() 
    {
    }
    
    /**
     * createAction
     *
     * Show the campaign creation page
     */
    public function createAction()
    {
        $grpId = $this->_request->getParam('grpid');
        // TODO:
        // if (!userIsAdminInGroup(grpid)) die;
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

                // Redirect back to campaigns page.
                $target = $this->_urlHelper->url(
                    array(
                        'groupid'    => $grpId,
                        'language'   => $this->view->language),
                    'group_shortview', true);
                $this->_redirector->gotoUrl($target);
            }
        }
    }
    
    /**
     * viewAction
     *
     * Show the campaign view page
     */
    public function viewAction()
    {
        $cmpid = $this->_request->getParam('cmpid');

        $cmpmodel = new Default_Model_Campaigns();
        $cmp = $cmpmodel->getCampaignById($cmpid);
        echo "<pre>";
        var_dump($cmp->toArray());
        echo "</pre>";
    }
}