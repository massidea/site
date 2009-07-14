<?php
/**
 *  ContentController -> Viewing content
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
 *  @author         Joel Peltonen & Pekka Piispanen
 *  @copyright      2009 Joel Peltonen & Pekka Piispanen
 *  @license        GPL v2
 *  @version        1.0
 */ 
class ContentController extends Oibs_Controller_CustomController
{
    /**
    *   init
    *   
    *   Initialization of content controller
    *
    */
    public function init()
    {
        parent::init();
        
        $this->view->title = 'content-title';
    } // end of init()

    /**
    *   indexAction
    *
    *   Content index
    *
    */
    public function indexAction()
    {
        //$this->view->title = $this->view->translate('content-add-page_header');
		$this->view->title = "OIBS";
    } // end of indexAction()

    /**
    *   listAction
    *
    *   Lists content by content type. 
    *
    */
    public function listAction()
    {
        // Set array for content data
        $data = array();
        
        // Get requests
        $params = $this->getRequest()->getParams();
        
        // Get content type
        $cty = isset($params['type']) ? $params['type'] : 'all';

        // Get page nummber and items per page
        $page = isset($params['page']) ? $params['page'] : 1;
        $count = isset($params['count']) ? $params['count'] : 10;
        
        // Get list oreder value
        $order = isset($params['order']) ? $params['order'] : 'created';
        
        // Get recent content by type
        $content = new Models_Content();
        $data = $content->listRecent($cty, $page, $count, $order);
        
        $languages = New Models_Languages();
        $id_lng_ind = $languages->getLangIdByLangName($this->view->language);
        
        // Get all industries
        $industries = new Models_Industries();
        $this->view->industries = $industries->getNamesAndIdsById(0, $id_lng_ind);
        
        // if content data is not empty
        if (!empty($data)) {
            // Content pagination
            $paginator = Zend_Paginator::factory($data);
            
            // Set items per page
            $paginator->setItemCountPerPage($count);
            
            // Get items by page
            $paginator->getItemsByPage($page);
            
            // Set current page number
            $paginator->setCurrentPageNumber($page);
            
            Zend_Paginator::setDefaultScrollingStyle('Sliding');
            
            $view = new Zend_View();
            $paginator->setView($view);
            
            // Set paginator for view
            $this->view->contentPaginator = $paginator;
            
            //$this->view->contentPaginator = $data['Content']['Data'];
        } // end if
        
        $this->view->type = $cty;
        $this->view->count = $count;
        $this->view->page = $page;
    } // end of listAction()

    /**
    *   addAction
    *
    *   Adds new content by content type.
    *
    */
    public function addAction()
    {
        // Get authentication
        $auth = Zend_Auth::getInstance();
        // If user has identity
        if ($auth->hasIdentity())
        {
            // Get requests
            $params = $this->getRequest()->getParams();
            
            // Get content type
            $contenttype = isset($params['contenttype']) 
                                ? $params['contenttype'] : '';
            
            // Get all content types from the database
            $model_content_types = new Models_ContentTypes();
            $content_types = $model_content_types->getAllNamesAndIds();

            // Setting the variable first to be true
            $invalid_contenttype = true;
            
            // If set content type exists in database, invalid_contenttype 
            // is set to false
            foreach($content_types as $cty){
                if($contenttype == $cty['key_cty'])
                {
                    $invalid_contenttype = false;
                }
            }
            
            // Variable $relatesto_id is set to 0, just in case that user is not
            // adding idea and there's no relatesto_id set
            $relatestoid = 0;
            
            if($invalid_contenttype)
            {
                $message = 'content-add-contenttype-missing-or-invalid';
                $this->flash($message, '/'.$this->view->language.'/msg/');
            }
            elseif($contenttype == "idea")
            {
                $relatestoid = isset($params['relatestoid']) 
                                    ? $params['relatestoid'] : 0;
                                    
                if($relatestoid == 0)
                {
                    $message = 'content-add-relatesto-id-missing';
                    $this->flash($message, '/'.$this->view->language.'/msg/');
                }
                else
                {
                    $content = new Models_Content();
                    // Checking if the content that idea is related to exists
                    $content_exists = 
                                $content->checkIfContentExists(
                                        $relatestoid
                                );
                    if(!$content_exists)
                    {
                        $message = 'content-add-invalid-related-content';
                        $this->flash(
                            $message, '/'.$this->view->language.'/msg/'
                        );
                    }
                }
            }
            
            $this->view->contenttype = $this->view->translate($contenttype);
			$this->view->short_contenttype = $contenttype;
            
            // Content type id is needed when adding content  to database
            $content_type_id = $model_content_types->getIdByType($contenttype);
            
            // Creating array for form data
            $form_data = array();
            
            // Adding data to form_data
            $form_data['content_type'] = $content_type_id;
            $form_data['content_relatesto_id'] = $relatestoid;
            
            $model_innovation_types = new Models_InnovationTypes();
            $innovation_types = $model_innovation_types->getAllNamesAndIds();
            
            $form_data['InnovationTypes'] = array();
            $form_data['InnovationTypes'][0] = 
                        $this->view->translate("content-add-select-innovation");
            foreach($innovation_types as $ivt)
            {
                $form_data['InnovationTypes'][$ivt['id_ivt']] = 
                                                            $ivt['name_ivt'];
            } // end foreach
            if(empty($form_data['InnovationTypes']))
            {
                $form_data['InnovationTypes'] = array(0 => '----');
            }
            
            $languages = New Models_Languages();
            $id_lng_ind = $languages->getLangIdByLangName($this->view->language);
            
            $model_industries = new Models_Industries();
            $industries = $model_industries->getNamesAndIdsById(0, $id_lng_ind);
            
            $form_data['Industries'] = array();
            $form_data['Industries'][0] = 
                        $this->view->translate("content-add-select-industry");
            foreach($industries as $ind)
            {
                $form_data['Industries'][$ind['id_ind']] = $ind['name_ind'];
            } // end foreach
            if(empty($form_data['Industries']))
            {
                $form_data['Industries'] = array(0 => '----');
            }
            
            // The id of first industry listed is needed when listing the 
            // divisions for the first time
            $first_industry_id = $model_industries->getIndustryId();
            $divisions = $model_industries
                                    ->getNamesAndIdsById($first_industry_id, $id_lng_ind);
            
            $form_data['Divisions'] = array();
            $form_data['Divisions'][0] = $this->view->translate(
                                    "content-add-select-division-no-industry"
                                    );
            
            $form_data['Groups'] = array();
            $form_data['Groups'][0] = $this->view->translate(
                                        "content-add-select-group-no-division"
                                    );
            
            $form_data['Classes'] = array();
            $form_data['Classes'][0] = $this->view->translate(
                                        "content-add-select-class-no-group"
                                    );
            
            // Form for content adding
            $form = new Forms_AddContentForm(null, $form_data, $this->view->language);
            $this->view->form = $form;
            
            // Get requests
            if($this->getRequest()->isPost())
            {
                // Get content data
                $data = $this->getRequest()->getPost();
                // Content keywords
                $keywords = split(', ', trim($data['content_keywords']));
                $data['content_keywords'] = array_unique($keywords);
                
                // Get user id
                $data['User']['id_usr'] = $auth->getIdentity()->user_id;
                
                if($data['content_group'] == 0)
                {
                    $data['content_industry_id'] = $data['content_division'];
                }
                elseif($data['content_class'] == 0)
                {
                    $data['content_industry_id'] = $data['content_group'];
                }
                elseif($data['content_class'] != 0)
                {
                    $data['content_industry_id'] = $data['content_class'];
                }
                
                // Add a new content
                $content = new Models_Content();
                if($content->addContent($data))
                {
                    $message = 'content-add-successful';
                }
                else
                {
                    $message = 'content-add-not-successful';
                }
                $this->flash($message, '/'.$this->view->language.'/msg/');
            } // end if
        } // end if
        else
        {
            // If not logged, redirecting to system message page
            $message = 'content-add-not-logged';
            $this->flash($message, '/'.$this->view->language.'/msg/');
        } // end else
    } // end of addAction()
    
    public function previewAction()
    {
        // Get authentication
        $auth = Zend_Auth::getInstance();
        // If user has identity
        if ($auth->hasIdentity())
        {
            // Get requests
            if($this->getRequest()->isPost())
            {
                // Get content data
                $data = $this->getRequest()->getPost();
                // Content keywords
                //$keywords = split(', ', trim($data['content_keywords']));
                //$data['content_keywords'] = array_unique($keywords);
                
                // Get user id
                $data['User']['id_usr'] = $auth->getIdentity()->user_id;
                
                $this->view->data = $data;
                
                /*// Add a new content
                $content = new Models_Content();
                if($content->addContent($data))
                {
                    $message = 'content-add-successful';
                }
                else
                {
                    $message = 'content-add-not-successful';
                }
                
                $this->flash($message, '/'.$this->view->language.'/msg/');*/
            }
        } // end if
        else
        {
            // If not logged, redirecting to system message page
            $message = 'content-add-not-logged';
            $this->flash($message, '/'.$this->view->language.'/msg/');
        } // end else
    }

    /**
    *   editAction
    *
    *   Edit content
    *
    */
    public function editAction()
    {
        // Get authentication
        $auth = Zend_Auth::getInstance();
        // If user has identity
        if ($auth->hasIdentity())
        {
            // Get requests
            $params = $this->getRequest()->getParams();
            
            // Get content type
            $content_id = isset($params['content_id']) 
                                ? $params['content_id'] : 0;
                                
            $userid = $auth->getIdentity()->user_id;
            
            $cntHasUsr = New Models_ContentHasUser();
            $owners = $cntHasUsr->getContentOwners($content_id);
                
            $found_owner = false;
            foreach ($owners as $owner) 
            {
                if($owner == $userid)
                {
                    $found_owner = true;
                }
            }
            
            if($found_owner)
            {
                if($content_id != 0)
                {
                    $content = New Models_Content();
                    $data = $content->getDataAsSimpleArray($content_id);
                    
                    // Creating array for form data
                    $form_data = array();
                    
                    // Adding content type to form
                    $form_data['content_type'] = $data['id_cty_cnt'];
                    
                    // Adding content id to form
                    $form_data['content_id'] = $content_id;
                    
                    // Getting innovation types from the database
                    $model_innovation_types = new Models_InnovationTypes();
                    $innovation_types = $model_innovation_types->getAllNamesAndIds();
                    
                    // Getting the innovation type of the content
                    $model_CntHasIvt = new Models_ContentHasInnovationTypes();
                    $form_data['selected_ivt'] = $model_CntHasIvt->getInnovationTypeIdOfContent($data['id_cnt']);
                    
                    // Adding all innovation types to form
                    $form_data['InnovationTypes'] = array();
                    $form_data['InnovationTypes'][0] = 
                                $this->view->translate("content-add-select-innovation");
                    foreach($innovation_types as $ivt)
                    {
                        $form_data['InnovationTypes'][$ivt['id_ivt']] = 
                                                                    $ivt['name_ivt'];
                    } // end foreach
                    
                    if(empty($form_data['InnovationTypes']))
                    {
                        $form_data['InnovationTypes'] = array(0 => '----');
                    }
                    
                    $languages = New Models_Languages();
                    $id_lng_ind = $languages->getLangIdByLangName($this->view->language);
                    
                    // Getting industries from the database
                    $model_industries = new Models_Industries();
                    $industries = $model_industries->getNamesAndIdsById(0, $id_lng_ind);
                    
                    // Getting the industry of the content
                    $model_CntHasInd = new Models_ContentHasIndustries();
                    $cntInd = $model_CntHasInd->getIndustryIdOfContent($data['id_cnt']);

                    // Getting all industries of the content
                    $industry_ids = $model_industries->getAllContentIndustryIds($cntInd);
                    
                    // Adding all industries of the content to form
                    $form_data['selected_industry'] = $industry_ids[0];
                    $form_data['selected_division'] = $industry_ids[1];
                    $form_data['selected_group'] = $industry_ids[2];
                    $form_data['selected_class'] = $industry_ids[3];
                    
                    // Adding all industries to form
                    $form_data['Industries'] = array();
                    $form_data['Industries'][0] = 
                                $this->view->translate("content-add-select-industry");
                    foreach($industries as $ind)
                    {
                        $form_data['Industries'][$ind['id_ind']] = $ind['name_ind'];
                    } // end foreach
                    
                    if(empty($form_data['Industries']))
                    {
                        $form_data['Industries'] = array(0 => '----');
                    }
                    
                    // Adding all divisions to form
                    $form_data['Divisions'] = array();
                    $form_data['Divisions'][0] = $this->view->translate(
                                            "content-add-select-division-no-industry"
                                            );
                    
                    $divisions = $model_industries
                                            ->getNamesAndIdsById($industry_ids[0], $id_lng_ind);

                    foreach($divisions as $div)
                    {
                        $form_data['Divisions'][$div['id_ind']] = $div['name_ind'];
                    } // end foreach
                    
                    // Adding all groups to form
                    $form_data['Groups'] = array();
                    $form_data['Groups'][0] = $this->view->translate(
                                                "content-add-select-group"
                                            );
                    
                    $groups = $model_industries
                                            ->getNamesAndIdsById($industry_ids[1], $id_lng_ind);

                    foreach($groups as $grp)
                    {
                        $form_data['Groups'][$grp['id_ind']] = $grp['name_ind'];
                    } // end foreach
                    
                    $form_data['Classes'] = array();

                    // If there's no group selected
                    if($industry_ids[2] == 0)
                    {
                        $form_data['Classes'][0] = $this->view->translate(
                                                "content-add-select-class-no-group"
                                            );
                    }
                    // And if there is a group selected..
                    else
                    {
                        $form_data['Classes'][0] = $this->view->translate(
                                                "content-add-select-class"
                                            );
                                            
                        $classes = $model_industries
                                            ->getNamesAndIdsById($industry_ids[2], $id_lng_ind);

                        foreach($classes as $class)
                        {
                            $form_data['Classes'][$class['id_ind']] = $class['name_ind'];
                        } // end foreach
                    }
                    
                    $model_CntHasTag = New Models_ContentHasTag();
                    $keywords = $model_CntHasTag->getContentTags($data['id_cnt']);
                    
                    $tags = "";
                    
                    $count = count($keywords);
                    $i = 1;
                    foreach($keywords as $keyword)
                    {
                        if($count != $i)
                        {
                            $tags .= $keyword['name_tag'] . ", ";
                        }
                        else
                        {
                            $tags .= $keyword['name_tag'];
                        }
                        $i++;
                    }
                    
                    $form_data['content_header'] = stripslashes($data['title_cnt']);
                    $form_data['content_keywords'] = stripslashes($tags);
                    $form_data['content_textlead'] = stripslashes($data['lead_cnt']);
                    $form_data['content_text'] = stripslashes($data['body_cnt']);
                    
                    // Form for content adding
                    $form = new Forms_EditContentForm(null, $form_data, $this->view->language);
                    $this->view->form = $form;
                    
                    // Get requests
                    if($this->getRequest()->isPost())
                    {
                        // Get content data
                        $data = $this->getRequest()->getPost();
                        
                        // Content keywords
                        $keywords = split(', ', trim($data['content_keywords']));
                        $data['content_keywords'] = array_unique($keywords);
                        
                        if($data['content_group'] == 0)
                        {
                            $data['content_industry_id'] = $data['content_division'];
                        }
                        elseif($data['content_class'] == 0)
                        {
                            $data['content_industry_id'] = $data['content_group'];
                        }
                        elseif($data['content_class'] != 0)
                        {
                            $data['content_industry_id'] = $data['content_class'];
                        }
                        
                        // Update the edited content to database
                        $content = new Models_Content();
                        if($content->editContent($data))
                        {
                            $message = 'content-edit-successful';
                        }
                        else
                        {
                            $message = 'content-edit-not-successful';
                        }
                        $this->flash($message, '/'.$this->view->language.'/msg/');
                    }
                }
                else
                {
                    $message = 'content-edit-no-id';
                    $this->flash($message, '/'.$this->view->language.'/msg/');
                }
            }
            else
            {
                $message = 'content-edit-not-owner';
                $this->flash($message, '/'.$this->view->language.'/msg/');
            }
        }
        else
        {
            // If not logged, redirecting to system message page
            $message = 'content-edit-not-logged';
            $this->flash($message, '/'.$this->view->language.'/msg/');
        } // end else
    }

    /**
    *   removeAction
    *
    *   Remove content
    *
    */
    public function removeAction()
    {
        $params = $this->getRequest()->getParams();
        $content_id = (int)$params['content_id'];
        
        $auth = Zend_Auth::getInstance();
        
        if ($auth->hasIdentity())
        {
            $userid = $auth->getIdentity()->user_id;
            
            $content = new Models_Content();
            if($content->checkIfContentExists($content_id))
            {
                $cntHasUsr = New Models_ContentHasUser();
                $owners = $cntHasUsr->getContentOwners($content_id);
                
                $found_owner = false;
                foreach ($owners as $owner) {
                    if($owner == $userid)
                    {
                        $found_owner = true;
                    }
                }

                if($found_owner)
                {
                    $contentRemoveSuccessful = true;
                    
                    if(!$content->removeContent($content_id))
                    {
                        $contentRemoveSuccessful = false;
                    }
                    
                    $cntHasTag = new Models_ContentHasTag();
                    $tags = $cntHasTag->getContentTags($content_id);
                    
                    $tag = new Models_Tags();
                    foreach($tags as $id_tag)
                    {
                        if(!$cntHasTag->checkIfOtherContentHasTag($id_tag['id_tag'], $content_id))
                        {
                            $tag->removeTag($id_tag['id_tag']);
                        }
                    }
                    
                    if(!$cntHasTag->removeContentTags($content_id))
                    {
                        $contentRemoveSuccessful = false;
                    }
                    
                    $cntHasUsr = new Models_ContentHasUser();
                    if(!$cntHasUsr->removeUserFromContent($content_id))
                    {
                        $contentRemoveSuccessful = false;
                    }
                    
                    $cntHasIvt = new Models_ContentHasInnovationTypes();
                    if(!$cntHasIvt->removeInnovationTypesFromContent($content_id))
                    {
                        $contentRemoveSuccessful = false;
                    }
                    
                    $cntHasInd = new Models_ContentHasIndustries();
                    if(!$cntHasInd->removeIndustriesFromContent($content_id))
                    {
                        $contentRemoveSuccessful = false;
                    }
                    
                    if($contentRemoveSuccessful == true)
                    {
                        $message = 'content-remove-successful';
                        $this->flash($message, '/'.$this->view->language.'/msg/');
                    }
                    else
                    {
                        $message = 'content-remove-not-successful';
                        $this->flash($message, '/'.$this->view->language.'/msg/');
                    }
                }
                else
                {
                    $message = 'content-remove-not-owner';
                    $this->flash($message, '/'.$this->view->language.'/msg/');
                }
            }
            else
            {
                $message = 'content-remove-invalid-content-id';
                $this->flash($message, '/'.$this->view->language.'/msg/');
            }
        }
        else
        {
            $message = 'content-remove-not-authed';
            $this->flash($message, '/'.$this->view->language.'/msg/');
        }
        
        /*try {
            $this->flash('This is a test flash', '/content');
        } catch (Zend_Db_Exception $e) { 
            // respond accordingly 
            echo '<h1>ERROR</h1><pre><br />';
            print_r($e);
            echo '</pre>';
        }*/
    } // end of removeAction

    /**
    *   publishAction
    *
    *   Set a content to published by id
    *
    *   @param id   integer     ID of content to be published
    */
    public function publishAction()
    {
        $params = $this->getRequest()->getParams();
        $content_id = (int)$params['content_id'];
        
        $auth = Zend_Auth::getInstance();
        
        if ($auth->hasIdentity())
        {
            $userid = $auth->getIdentity()->user_id;
            
            $content = new Models_Content();
            if($content->checkIfContentExists($content_id))
            {
                $cntHasUsr = New Models_ContentHasUser();
                $owners = $cntHasUsr->getContentOwners($content_id);
                
                $found_owner = false;
                foreach ($owners as $owner) {
                    if($owner == $userid)
                    {
                        $found_owner = true;
                    }
                }

                if($found_owner)
                {
                    if($content->publishContent($content_id))
                    {
                        $message = 'content-publish-successful';
                        $this->flash($message, '/'.$this->view->language.'/msg/');
                    }
                    else
                    {
                         $message = 'content-publish-not-successful';
                        $this->flash($message, '/'.$this->view->language.'/msg/');
                    }
                }
                else
                {
                    $message = 'content-publish-not-owner';
                    $this->flash($message, '/'.$this->view->language.'/msg/');
                }
            }
            else
            {
                $message = 'content-publish-invalid-content-id';
                $this->flash($message, '/'.$this->view->language.'/msg/');
            }
        }
        else
        {
            $message = 'content-publish-not-authed';
            $this->flash($message, '/'.$this->view->language.'/msg/');
        }
    } // end of publishAction

    /**
    *   viewAction
    *
    *   Gets content for view.
    *
    *   @param  page    integer     Page number for paginator
    *   @param  count   integer     Count of content for paginator
    *   @param  id      integer     ID of content
    *
    */
    public function viewAction()
    {
        // Get requests
        $request = $this->getRequest();
        $params = $request->getParams();
        
        // Get page number and comments per page
        $page = isset($params['page']) ? $params['page'] : 1;
        $count = isset($params['count']) ? $params['count'] : 20;
        
        $id = (int)$params['id'];
        $user_can_comment = false;

        // Get authentication
        $auth = Zend_Auth::getInstance();
        
        // If user has identity
        if ($auth->hasIdentity()) 
        {
            $user_can_comment = true;
            
            // Comment form
            $comment_form = new Forms_CommentForm();
            if ($request->isPost())
            {
                // Get comment form
                $formData = $this->_request->getPost();
                
                if ($comment_form->isValid($formData))
                {
                    $user_id = $auth->getIdentity()->user_id;
                    
                    $comment = new Models_Comments();
                    $comment->addComment($id, $user_id, $formData);
                    
                    //$comment_form = new Forms_CommentForm();
                } // end if
            } // end if
            $this->view->comment_form = $comment_form;
        } // end if

        // Get content data
        $content = new Models_Content();
        
        $content->increaseViewCount($id);
        $data = $content->getById($id);
        
        // Get user thumb image
        $user = new Models_user();
        $this->view->user_has_image = $user->userHasProfileImage($data['Content']['Poster']['id_usr']['id_usr']);
        
        // Get user content
        $user_content = $user->getUserContent($data['Content']['Poster']['id_usr']['id_usr']);

        // Comment pagination
        $paginator = Zend_Paginator::factory($data['Comments']['Data']);
        
        // Set comments per page, comment count, set page number
        $paginator->setItemCountPerPage($count);
        $paginator->getItemsByPage($page);
        $paginator->setCurrentPageNumber($page);
        
        Zend_Paginator::setDefaultScrollingStyle('Sliding');
        
        $view = new Zend_View();
        $paginator->setView($view);
        
        // Set paginator and content data to view
        $this->view->commentPaginator = $paginator;
        $this->view->content = $data;
        $this->view->user_can_comment = $user_can_comment;
        $this->view->user_content = $user_content;
    } // end of viewAction

    /**
    *   divisionAction  Imports data for ajax
    */
    public function divisionAction()
    {
        // Set views layout to empty
        $this->_helper->layout()->setLayout('empty');
        
        // Get requests
        $params = $this->getRequest()->getParams();
        
        // Get industry id
        $industryid = isset($params['industry']) ? $params['industry'] : '0';
        
        $languages = New Models_Languages();
        $id_lng_ind = $languages->getLangIdByLangName($this->view->language);
        
        $model_industries = new Models_Industries();
        $divisions = $model_industries->getNamesAndIdsById($industryid, $id_lng_ind);
        $this->view->divisions = $divisions;
    }

    /**
    *   groupAction Imports data for ajax
    */
    public function groupAction()
    {
        // Set views layout to empty
        $this->_helper->layout()->setLayout('empty');
        
        // Get requests
        $params = $this->getRequest()->getParams();
        
        // Get division id
        $divisionid = isset($params['division']) ? $params['division'] : '0';
        
        $languages = New Models_Languages();
        $id_lng_ind = $languages->getLangIdByLangName($this->view->language);
        
        $model_industries = new Models_Industries();
        $groups = $model_industries->getNamesAndIdsById($divisionid, $id_lng_ind);
        $this->view->groups = $groups;
    }

    /*
    *   classAction Imports data for ajax
    */
    public function classAction()
    {
        // Set views layout to empty
        $this->_helper->layout()->setLayout('empty');
        
        // Get requests
        $params = $this->getRequest()->getParams();
        
        // Get group id
        $groupid = isset($params['group']) ? $params['group'] : '0';
        
        $languages = New Models_Languages();
        $id_lng_ind = $languages->getLangIdByLangName($this->view->language);
        
        $model_industries = new Models_Industries();
        $classes = $model_industries->getNamesAndIdsById($groupid, $id_lng_ind);
        $this->view->classes = $classes;
    }
}
?>