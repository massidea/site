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
        $cmpmodel = new Default_Model_Campaigns();
        $this->view->campaigns = $cmpmodel->getAll();


        $grpmodel = new Default_Model_Groups();
        $this->view->groups = $grpmodel->getAllGroups();
    }
}