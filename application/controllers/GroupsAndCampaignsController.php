<?php
/**
 *  GroupsAndCampaignsController
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
 *  GroupsAndCampaignsController - class
 *
 *  @package        controllers
 *  @author         Mikko Aatola
 *  @copyright      2010 MassIdea.org
 *  @license        GPL v2
 *  @version        1.0
 */ 
class GroupsAndCampaignsController extends Oibs_Controller_CustomController
{
    public function indexAction() 
    {
        $auth = Zend_Auth::getInstance();
        $logged_in = $auth->hasIdentity();

        $grpmodel = new Default_Model_Groups();
        $cmpmodel = new Default_Model_Campaigns();
        $grpadm = new Default_Model_GroupAdmins();

        $grps = $grpmodel->getRecent(12);
        $grps_new = array();
        foreach ($grps as $grp) {
            $adm = $grpadm->getGroupAdmins($grp['id_grp']);
            $grp['id_admin'] = $adm[0]['id_usr'];
            $grp['login_name_admin'] = $adm[0]['login_name_usr'];
            $grps_new[] = $grp;
        }

        $this->view->logged_in = $logged_in;
        $this->view->groups = $grps_new;
    }
}