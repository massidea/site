<?php
/**
 *  ViewController -> Viewing content from the database
 *
 *   Copyright (c) <2009>, Joel Peltonen <joel.peltonen@cs.tamk.fi>
 *   Copyright (c) <2009>, Pekka Piispanen <pekka.piispanen@cs.tamk.fi>
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
 *  Viewontroller - class
 *
 *  @package    controllers
 *  @author     Joel Peltonen & Pekka Piispanen
 *  @copyright  2009 Joel Peltonen & Pekka Piispanen
 *  @license    GPL v2
 *  @version    1.0
 */ 
 class ViewController extends Oibs_Controller_CustomController
{
    public function init()
    {
        parent::init();

    }

    /**
    *   index page: Contains the content viewing functionality.
    *
    *   @todo   Implement group ownership user images and content links
    *   @todo   AJAXify giving ratings
    *   @todo   Include translation and content info for page title
    *   @todo   AJAXify the "more from" box
    *   @todo   Limitations for the "more from" box - MAX 10 content
    *   @todo   More from box should show ratings
    *   @todo   If not ajax "more from", at least separate to proper MVC
    *   @todo   Look over comment loading for data being fetched and not shown
    *   @todo   Custom paginator style
    *   @todo   Comment rating, userpic (maybe not)
    *
    *   @param  id      integer     id of content to view
    *   @param  page    integer     (optional) Page number for paginator
    *   @param  count   integer     (optional) Count of content for paginator
    *   @param  rate    integer     (optional) Rating given by user
    */
    function indexAction()
    {
        // get requests
        $request = $this->getRequest();
        $params = $request->getParams();

        // get content id from params, if not set or invalid, send a message
        $id = (int)$params['content_id'];
        if ($id == 0) {
            $this->flash('content-not-found', '/en/msg/');   
        }
        
        // Get specific content data -- this could fail? Needs check?
        $contentModel = new Models_Content();
        $contentData = $contentModel->getDataAsSimpleArray($id);
        
        if ($contentData['published_cnt'] == 0) {
            $this->flash('content-not-found', '/en/msg/');  
        }
   
        // get rating from params (if set)
        $rate = isset($params['rate']) ? $params['rate'] : "NONE";
        
        // get page number and comments per page (if set)
        $page = isset($params['page']) ? $params['page'] : 1;
        $count = isset($params['count']) ? $params['count'] : 10;
        
        // turn commenting off by default
        $user_can_comment = false;

        // Get authentication
        $auth = Zend_Auth::getInstance();

        // If user has identity
        if ($auth->hasIdentity()) 
        {
            // enable comment form, also used as rating permission
            $user_can_comment = true;   
            
            // generate comment form
            $comment_form = new Forms_CommentForm();
            
            // if there is something in POST
            if ($request->isPost())
            {
                // Get comment form data
                $formData = $this->_request->getPost();
                
                // Validate and save comment data
                if ($comment_form->isValid($formData))
                {
                    $user_id = $auth->getIdentity()->user_id;

                    $comment = new Models_Comments();
                    $comment->addComment($id, $user_id, $formData);

                    $comment_form = new Forms_CommentForm();
                } // end if
            } // end if
            $this->view->comment_form = $comment_form;
        } // end if
        
        // get content type of the specific content viewed
        $contentTypesModel = New Models_ContentTypes();
        $contentType = $contentTypesModel->getTypeById($contentData['id_cty_cnt']);
        
        // Get content innovation type / industry / division / group / class
        // and send to view... somehow.
        // TO BE IMPLEMENTED

        // Get content owner id (groups to be implemented later)
        $contentHasUserModel = new Models_ContentHasUser();
        $owner = $contentHasUserModel->getContentOwners($id);
        $ownerId = $owner['id_usr'];

        // Get content owner data
        $userModel = new Models_User();
        $userData = $userModel->getSimpleUserDataById($ownerId);

        // get content owner picture ... to be implemented later
        $user_has_image = $userModel->userHasProfileImage($ownerId);

        // get other content from user.. function needs a looking-over!
        // Also it needs to be separated from this action so the MVC-is correct!
        $moreFromUser = $userModel->getUserContent($ownerId);

        // get (VIEWED) content views (returns a string directly)
        $contentViewsModel = new Models_ContentViews();
        $contentViewsModel->increaseViewCount($id);
        $views = $contentViewsModel->getViewsByContentId($id);

        // get content rating (returns a string directly)
        $contentRatingsModel = new Models_ContentRatings();
        $rating = $contentRatingsModel->getById($id);
        
        // $rate is gotten from params[], 1 and -1 are the only allowed
        if ($rate != "NONE"
            && ($rate == 1 || $rate == -1)
            && $auth->hasIdentity())
        { 
            if($contentRatingsModel->addRating($id, $ownerId, $rate)) {
                $this->view->savedRating = $rate;
            } else {
                $this->flash('rating-failed-msg', '/en/msg/');
            }
        }

        // get content tags - functions returns names as well
        // needs updating to proper MVC?
        $contentHasTagModel = new Models_ContentHasTag();
        $tags = $contentHasTagModel->getContentTags($id);
        //echo "<pre>"; print_r($tags); echo "</pre>"; die;

        // get content links, to be implemented
        $links = array();

        // This functionality needs looking over (code and general idea)
        // get content family (array of children, parents and siblings)
        $contentHasContentModel = new Models_ContentHasContent();
        $family = $contentHasContentModel->getContentFamilyTree($id);

        // split family array to child, parent and sibling arrays (full content)
        $children = array();
        $i = 0;
        if (isset($family['children'])) {
            foreach ($family['children'] as $child) {
                $children[$i] = $contentModel->getDataAsSimpleArray((int)$child);
                $i++;
            }
        }

        $parents = array();
        $i = 0;
        if (isset($family['parents'])) {
            foreach ($family['parents'] as $parent) {
                $parents[$i] = $contentModel->getDataAsSimpleArray((int)$parent);
                $i++;
            }
        }

        $siblings = array(); // not implemented yet in models
        $i = 0;
        if (isset($family['siblings'])) {
            foreach ($family['siblings'] as $sibling) {
                $siblings[$i] = $contentModel->getDataAsSimpleArray((int)$sibling);
                $i++;
            }
        }

        // get comments data 
        // might need some looking over. many comments = memory death
        $commentsModel = new Models_Comments();
        $comments = $commentsModel->getAllByContentId($id);

        // comments pagination
        $paginator = Zend_Paginator::factory($comments);

        // Set comments per page, comment count, set page number
		$paginator->setItemCountPerPage($count);
		$paginator->getItemsByPage($page);
		$paginator->setCurrentPageNumber($page);

        Zend_Paginator::setDefaultScrollingStyle('Sliding');

        $view = new Zend_View();
		$paginator->setView($view);

        // get content industries -- will be updated later.
        $cntHasIndModel = new Models_ContentHasIndustries();
        $hasIndustry = $cntHasIndModel->getIndustryIdOfContent($id);
        
        $industriesModel = new Models_Industries();
        $industriesArray = $industriesModel->getAllContentIndustryIds($hasIndustry);
        
        // roll values to an array
        $industries = array();
        foreach ($industriesArray as $industry) {
            $value = $industriesModel->getNameById($industry);
            $industriesModel->getNameById($industry);

           if (!empty($value)) {
                $industries[] = $value;
            }
        }
        
        // Inject data to view
        $this->view->industries         = $industries;
        $this->view->user_has_image     = $user_has_image;
        $this->view->commentPaginator   = $paginator;
		$this->view->user_can_comment   = $user_can_comment;
        $this->view->contentData        = $contentData;
        $this->view->userData           = $userData;
        $this->view->moreFromUser       = $moreFromUser;
        $this->view->views              = $views;
        $this->view->rating             = $rating;
        $this->view->tags               = $tags;
        $this->view->links              = $links;
        $this->view->parents            = $parents;
        $this->view->children           = $children;
        $this->view->comments           = $comments;
        $this->view->contentType        = $contentType;
        
        // Inject title to view
        $this->view->title = 'view-index-title';
	} // end of view2Action
}
?>