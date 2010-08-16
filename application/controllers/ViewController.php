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
 *  Viewcontroller - class
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
    *   @todo   Include translation and content info for page title
    *   @todo   More from box should show ratings
    *   @todo   If not ajax "more from", at least separate to proper MVC
    *   @todo   Look over comment loading for data being fetched and not shown
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

        $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
		$absoluteBaseUrl = strtolower(trim(array_shift(explode('/', $_SERVER['SERVER_PROTOCOL'])))) . 
    						'://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getBaseUrl();
		
        // get content id from params, if not set or invalid, send a message
        $id = (int)$params['content_id'];
                
        if ($id == 0) {
            $this->flash('content-not-found', $baseUrl.'/'.$this->view->language.'/msg/');   
        }
        
        // Get specific content data -- this could fail? Needs check?
        $contentModel = new Default_Model_Content();
        $contentData = $contentModel->getDataAsSimpleArray($id);
        
        $isTranslated = isset($params['notranslate']) ? false:true;
        
        if($isTranslated)
        {
	        // Translate content data
			$this->gtranslate->setLangFrom($contentData['language_cnt']);
			$contentData = $this->gtranslate->translateContent($contentData);
        }
        
        $filesModel = new Default_Model_Files();
        $files = $filesModel->getFilenamesByCntId($id);
        
        // Get content owner id (groups to be implemented later)
        $contentHasUserModel = new Default_Model_ContentHasUser();
        $owner = $contentHasUserModel->getContentOwners($id);
        $ownerId = $owner['id_usr'];

        // Get authentication
        $auth = Zend_Auth::getInstance();

        // Get user_id
        if ($auth->hasIdentity()) {
            $usrId = $auth->getIdentity()->user_id;
        }
        
        if ($contentData['published_cnt'] == 0 && 
        	$usrId != $ownerId &&
        	!in_array("admin", $this->view->logged_user_roles))
        {
            $this->flash('content-not-found', $baseUrl.'/'.$this->view->language.'/msg/');  
        }
   
        // get rating from params (if set)
        $rate = isset($params['rate']) ? $params['rate'] : "NONE";
        
        // get favourite method, "add" or "remove"
        //$favouriteMethod = isset($params['favourite']) ? $params['favourite'] : "NONE";
        
        // get page number and comments per page (if set)
        $page = isset($params['page']) ? $params['page'] : 1;
        
        $comments = new Oibs_Controller_Plugin_Comments("content", $id);
		$this->view->jsmetabox->append('commentUrls', $comments->getUrls());
        
        // turn commenting off by default
        $user_can_comment = false;
        
        // turn rating off by default
        $user_can_rate = false;

        // user is not owner by default
        $user_is_owner = false;

        // Comment model
        $comment = new Default_Model_Comments();
        
        //$parentId = isset($params['replyto']) ? $params['replyto'] : 0;
        
        // If user has identity
        if ($auth->hasIdentity() && $contentData['published_cnt'] == 1) {
            // enable comment form
            $comments->allowComments(true);
            
            // enable rating if the content was not published by the user
            // (also used for flagging)
            if ($ownerId != $auth->getIdentity()->user_id) {
                $user_can_rate = true;
            }

            // Check if user is owner of content
            if ($ownerId == $auth->getIdentity()->user_id) {
                $user_is_owner = true;
            }
            
            // generate comment form
            //$comment_form = new Default_Form_CommentForm($parentId);
     
            // if there is something in POST
            /*if ($request->isPost()) {
            
                    if($user_id != $ownerId) {
                        $user = new Default_Model_User();
                        $comment_sender = $user->getUserNameById($user_id);
                        
                        $Default_Model_privmsg = new Default_Model_PrivateMessages();
                        $data = array();
                        $data['privmsg_sender_id'] = 0;
                        $data['privmsg_receiver_id'] = $ownerId;
                        $data['privmsg_header'] = 'You have new comment!';
                        $data['privmsg_message'] = '<a href="'.$baseUrl."/".$this->view->language.'/account/view/user/'.$comment_sender.'">'
                        .$comment_sender.'</a> commented your content <a href="'.$baseUrl."/".$this->view->language.'/view/'.$id.'">'.$contentData['title_cnt'].'</a>';
                        $data['privmsg_email'] = '';
                        
                        // Send email to contentowner about new comment
                        // if its allowed
                        $notificationsModel = new Default_Model_Notifications();
						$notifications = $notificationsModel->getNotificationsById($ownerId);

	                    if (in_array('comment', $notifications)) {
	                    	
	                    	$emailNotification = new Oibs_Controller_Plugin_Email();
	                    	$emailNotification->setNotificationType('comment')
	                    					   ->setSenderId($user_id)
	                    					   ->setReceiverId($ownerId)
	                    					   ->setParameter('URL', $absoluteBaseUrl."/en")
	                    					   ->setParameter('SENDER-NAME', $comment_sender)
	                    					   ->setParameter('CONTENT-ID', $id)
	                    					   ->setParameter('CONTENT-TITLE', $contentData['title_cnt'])
	                    					   ->setParameter('COMMENT', $formData['comment_message']);
	                    					   
							if ($emailNotification->isValid()) {
								$emailNotification->send();
							} else {
								//echo $emailNotification->getErrorMessage(); die;
							}
	                    }
                        
                        $Default_Model_privmsg->addMessage($data);
                } // end if
            }*/ // end if
        } // end if
        
        // get content type of the specific content viewed
        $contentTypesModel = New Default_Model_ContentTypes();
        $contentType = $contentTypesModel->getTypeById($contentData['id_cty_cnt']);
        
        // Get content innovation type / industry / division / group / class
        // and send to view... somehow.
        // TO BE IMPLEMENTED

        // Get content owner data
        $userModel = new Default_Model_User();
        $userData = $userModel->getSimpleUserDataById($ownerId);

        // get content owner picture ... to be implemented later
        $userImage = $userModel->getUserImageData($ownerId);

        // get (VIEWED) content views (returns a string directly)
        $contentViewsModel = new Default_Model_ContentViews();
        if (! $this->alreadyViewed($id)) {
			$contentViewsModel->increaseViewCount($id);
        }
        $views = $contentViewsModel->getViewsByContentId($id);
        
        $languagesModel = new Default_Model_Languages();
        $languageName = $languagesModel->getLanguageByLangCode($contentData['language_cnt']);
        $gtranslateLangPair = $this->gtranslate->getLangPair();

        // get content tags - functions returns names as well
        // needs updating to proper MVC?
        $contentHasTagModel = new Default_Model_ContentHasTag();
        $tags = $contentHasTagModel->getContentTags($id);
        
        if($isTranslated)
        {
			$tags = $this->gtranslate->translateTags($tags);
        }

        // get content links, to be implemented
        $links = array();

        // Get all content campaigns
        $campaignHasContentModel = new Default_Model_CampaignHasContent();
        $campaigns = $campaignHasContentModel->getContentCampaigns($id);

        // This functionality needs looking over (code and general idea)
        // get content family (array of children, parents and siblings)
        $contentHasContentModel = new Default_Model_ContentHasContent();
        $family = $contentHasContentModel->getContentFamilyTree($id);
        
        // split family array to child, parent and sibling arrays (full content)
        $children = array();
        $children_siblings = array();
        
        if (isset($family['children'])) {
            foreach ($family['children'] as $child) {
                $contenttypeid = $contentModel->getContentTypeIdByContentId((int)$child);
                $contenttype = $contentTypesModel->getTypeById($contenttypeid);
                
                if($contenttype == "idea") {
                    $children[] = $contentModel->getDataAsSimpleArray((int)$child);
                } else {
                    $children_siblings[] = $contentModel->getDataAsSimpleArray((int)$child);
                }
                // $i++;
            }
        }

        $parents = array();
        $parent_siblings = array();
        
        if (isset($family['parents'])) {
            foreach ($family['parents'] as $parent) {
                $contenttypeid = $contentModel->getContentTypeIdByContentId((int)$parent);
                $contenttype = $contentTypesModel->getTypeById($contenttypeid);
                
                if($contenttype == "idea") {
                    $parents[] = $contentModel->getDataAsSimpleArray((int)$parent);
                } else {
                    $parent_siblings[] = $contentModel->getDataAsSimpleArray((int)$parent);
                }
            }
        }
            
        // Here we get the rival solutions for a solution
        $rivals = array();
        if($contentType == "idea" && isset($family['parents'])) {
            $i = 0;
            // First here is checked the parents of this solution (=the problem
            // or the future info)
            foreach ($family['parents'] as $parent) {
                // Get the family of the problem or future info
                $parents_family = $contentHasContentModel->getContentFamilyTree((int)$parent);
                
                // Get the children of the problem or future info
                if(isset($parents_family['children'])) {
                    // Going through the children
                    foreach($parents_family['children'] as $parent_child) {
                        // Those children are rivals which are not this solution
                        // which is currently viewed
                        if((int)$parent_child != $id) {
                            $rivals[$i] = $contentModel->getDataAsSimpleArray((int)$parent_child);
                        }
                    }
                }
                $i++;
            }
        }

        // get comments data 
        // $commentList = $comment->getAllByContentId($id, $page, $count);
        /*$commentList = $comment->getCommentsByContent($id);
        
        $commentsSorted = array();
        $this->getCommentChilds($commentList, $commentsSorted, 0, 0, 3);
        
        // Get total comment count
        $commentCount = $comment->getCommentCountByContentId($id);
        
        // Calculate total page count
        $pageCount = ceil($commentCount / $count);
        
        // Custom pagination to fix memory error on large amount of data
        $paginator = new Zend_View();
        $paginator->setScriptPath('../application/views/scripts');
        $paginator->pageCount = $pageCount;
        $paginator->currentPage = $page;
        $paginator->pagesInRange = 10;*/
        
        // get content industries -- will be updated later.
        $cntHasIndModel = new Default_Model_ContentHasIndustries();
        $hasIndustry = $cntHasIndModel->getIndustryIdOfContent($id);
        
        $industriesModel = new Default_Model_Industries();
        $industriesArray = $industriesModel->getAllContentIndustryIds($hasIndustry);
        
        // roll values to an array
        /*$industries = array();
        foreach ($industriesArray as $industry) {
            $value = $industriesModel->getNameById($industry);
            // $industriesModel->getNameById($industry);

           if (!empty($value)) {
                $industries[] = $value;
            }
        }*/
        
        // Check if and when the content is modified and if its more than 10minutes ago add for the view
        $dateCreated = strtotime( $contentData['created_cnt'] );
        $dateModified = strtotime( $contentData['modified_cnt'] );
        $modified = 0;
        if ( ($dateModified-$dateCreated)/60 > 10) {
        	$modified = $contentData['modified_cnt'];
        }

        $comments->loadComments();
        
        // Inject data to view
        $this->view->files 				= $files;
        $this->view->id					= $id;
        $this->view->userImage          = $userImage;
        //$this->view->commentPaginator   = $paginator;
        //$this->view->commentData        = $commentsSorted;
		//$this->view->user_can_comment   = $user_can_comment;
		$this->view->comments 			= $comments;
		$this->view->user_can_rate      = $user_can_rate;
        $this->view->user_is_owner      = $user_is_owner;
        $this->view->usrId              = $usrId;
        $this->view->contentData        = $contentData;
        $this->view->modified			= $modified;
        $this->view->userData           = $userData;
        $this->view->views              = $views;
        $this->view->isTranslated		= $isTranslated;
        $this->view->languageName		= $languageName;
        $this->view->gtranslateLangPair	= $gtranslateLangPair;
        $this->view->tags               = $tags;
        $this->view->links              = $links;
        $this->view->parents            = $parents;
        $this->view->parent_siblings    = $parent_siblings;
        $this->view->children           = $children;
        $this->view->children_siblings  = $children_siblings;
        $this->view->rivals             = $rivals;
        //$this->view->comments           = $commentCount;
        $this->view->contentType        = $contentType;
        //$this->view->count              = $count;
        $this->view->campaigns          = $campaigns;
        
        // Inject title to view
        $this->view->title = $this->view->translate('index-home') . " - " . $contentData['title_cnt'];
	} // end of view2Action
    

}