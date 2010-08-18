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

		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->addActionContext('list', 'xml')->initContext();
		$this->baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
		$this->view->title = 'content-title';
	} // end of init()

	/**
	 *   indexAction
	 *
	 *   Content index -- redicrects user to help/guidelines for adding content
	 */
	public function indexAction()
	{
		//$this->view->title = "Massidea.org";

		//if (!$this->view->authenticated){

		$url = $this->_urlHelper->url(array('controller' => 'help',
                                            'action' => 'guidelines',
                                            'language' => $this->view->language), 
                                            'lang_default', true); 
			
		$this->_redirect($url);

		//}
	}

	/**
	 *   listAction
	 *
	 *   Lists content by content type.
	 *
	 */
	public function listAction()
	{
		
		$url = $this->_urlHelper->url(array('controller' => 'index',
                                            'language' => $this->view->language), 
                                            'lang_default', true); 

		// Get cache from registry
		$cache = Zend_Registry::get('cache');

		// Set array for content data
		$data = array();

		// Get requests
		$params = $this->getRequest()->getParams();

		// Get content type
		$cty = isset($params['type']) ? $params['type'] : 'all';
		if($cty != "idea" && $cty != "finfo" && $cty != "problem") $this->_redirect($url);
		
		// Get page nummber and items per page
		$page = isset($params['page']) ? $params['page'] : 1;
		$count = isset($params['count']) ? $params['count'] : 15;

		// Get list oreder value
		$order = isset($params['order']) ? $params['order'] : 'created';
		$ind = isset($params['ind']) ? $params['ind'] : 0;

		// Get current language id
		// $languages = new Default_Model_Languages();
		// $idLngInd = $languages->getLangIdByLangName($this->view->language);

		// Get recent content by type
		$contentModel = new Default_Model_Content();
		$data = $contentModel->listRecent($cty, $page, $count, $order, $this->view->language, $ind);
		$results = array();

		// gather other content data and insert to results array
		if(isset($data[0])) {
			$contentHasTagModel = new Default_Model_ContentHasTag();
			// $contentRatingsModel = new Default_Model_ContentRatings();

			$i = 0;
			foreach ($data as $content) {
				$results[$i] = $content;
				$results[$i]['tags'] = $contentHasTagModel
				->getContentTags($content['id_cnt']);
				//$results[$i]['ratingdata'] = $contentRatingsModel
				//                        ->getPercentagesById($content['id_cnt']);
				$i++;
			}
		}

		// Get total content count
		$contentCount = $contentModel->getContentCountByContentType($cty, $this->view->language);

		// Calculate total page count
		$pageCount = ceil($contentCount / $count);
		
		// Most viewed content
		$mostViewedData = $contentModel->getMostViewedType($cty, $page, $count, 'views', 'en', $ind);

		// Get all industries
		//$industries = new Default_Model_Industries();
		//$this->view->industries = $industries->getNamesAndIdsById($ind, $idLngInd);

		// Get industry data by id
		//$this->view->industryParent = $industries->getById($ind);

		// Load most popular tags from cache
		if(!$result = $cache->load('IndexTags')) {
			$tagsModel = new Default_Model_Tags();
			$tags = $tagsModel->getPopular(20);
			/*
			 // resize tags
			 foreach ($tags as $k => $tag) {
			 $size = round(50 + ($tag['count'] * 30));
			 if ($size > 300) {
			 $size = 300;
			 }
			 $tags[$k]['tag_size'] = $size;
			 }
			 */

			// Action helper for tags
			$tags = $this->_helper->tagsizes->tagCalc($tags);

			//Action helper for define is tag running number divisible by two
            $tags = $this->_helper->tagsizes->isTagDivisibleByTwo($tags);
            
			// Save most popular tags data to cache
			$cache->save($tags, 'IndexTags');
		} else {
			$tags = $result;
		}

		// Custom pagination to fix memory error on large amount of data
		/*
		$paginator = new Zend_View();
		$paginator->setScriptPath('../application/views/scripts');
		$paginator->pageCount = $pageCount;
		$paginator->currentPage = $page;
		$paginator->pagesInRange = 10;
		*/

		// Send to view
		$this->view->tags = $tags;
		//$this->view->contentPaginator = $paginator;
		$this->view->contentData = $results;
		$this->view->type = $cty;
		$this->view->count = $count;
		$this->view->page = $page;
		$this->view->contentCount = $contentCount;
		$this->view->ind = $ind;
		$this->view->mostViewedData = $mostViewedData;

		// RSS type for the layout
		$this->view->rsstype = $cty;
			
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
		if ($auth->hasIdentity()) {
			// Get requests
			$params = $this->getRequest()->getParams();

			// Get content type
			$contentType = isset($params['contenttype']) ? $params['contenttype'] : '';
			$relatesToId = isset($params['relatestoid']) ? $params['relatestoid'] : 0;

			// Get all content types from the database
			$modelContentTypes = new Default_Model_ContentTypes();
			//$contentTypes = $modelContentTypes->getAllNamesAndIds();
			$validContentType = $modelContentTypes->contentTypeExists($contentType);

			/*
			 // Setting the variable first to be true
			 $invalidContentType = true;

			 // If set content type exists in database, invalidContentType
			 // is set to false
			 foreach($contentTypes as $cty){
			 if($contentType == $cty['key_cty']) {
			 $invalidContentType = false;
			 }
			 }
			 */

			// Variable $relatesToId is set to 0, just in case that user is not
			// adding idea and there's no relatesto_id set

			$content = new Default_Model_Content();

			if(!$validContentType) {
				$message = 'content-add-contenttype-missing-or-invalid';
				$url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index',
                                                'language' => $this->view->language),
                                         		'lang_default', true); 

				$this->flash($message, $url);
			} elseif($relatesToId != 0) {

				/*if($relatesToId == 0) {
				 $message = 'content-add-relatesto-id-missing';
				 $url = $this->_urlHelper->url(array('controller' => 'msg',
				 'action' => 'index',
				 'language' => $this->view->language),
				 'lang_default', true);

				 $this->flash($message, $url);
				 } */

				// Checking if the content that idea is related to exists
				$contentExists = $content->checkIfContentExists($relatesToId);

				if(!$contentExists) {
					$message = 'content-add-invalid-related-content';
					$url = $this->_urlHelper->url(array('controller' => 'msg',
                                                        'action' => 'index',
                                                        'language' => $this->view->language),
                                                  		'lang_default', true); 

					$this->flash($message, $url);
				}
			}

			$this->view->contenttype = $this->view->translate($contentType);
			$this->view->short_contenttype = $contentType;
			$this->view->relatesToId = $relatesToId;

			if($relatesToId != 0) {
				$relatesToTypeId = $content->getContentTypeIdByContentId($relatesToId);
				$this->view->relatesToType = $modelContentTypes->getTypeById($relatesToTypeId);
				$this->view->relatesToHeader = $content->getContentHeaderByContentId($relatesToId);
			}

			// Content type id is needed when adding content  to database
			$contentTypeId = $modelContentTypes->getIdByType($contentType);

			// Cacheing of formData
			$cache = Zend_Registry::get('cache');
			$formDataCacheTag = 'formData_'.$contentType.'_'.$this->view->language;

			if (!($formData = $cache->load($formDataCacheTag) ) || $relatesToId != 0) {
				// Creating array for form data
				$formData = array();

				// Adding data to formData
				$formData['content_type'] = $contentTypeId;
				$formData['content_relatesto_id'] = $relatesToId;

				// Content classifications
				$modelFutureinfoClasses = new Default_Model_FutureinfoClasses();
				$futureinfoClasses = $modelFutureinfoClasses->getAllNamesAndIds();

				$formData['FutureinfoClasses'] = array();
				$formData['FutureinfoClasses'][0] = $this->view->translate("content-add-select-finfo-classification");

				foreach($futureinfoClasses as $fic) {
					$formData['FutureinfoClasses'][$fic['id_fic']] = $fic['name_fic'];
				} // end foreach

				if(empty($formData['FutureinfoClasses'])) {
					$formData['FutureinfoClasses'] = array(0 => '----');
				}

				$modelInnovationTypes = new Default_Model_InnovationTypes();
				$innovationTypes = $modelInnovationTypes->getAllNamesAndIds();

				$formData['InnovationTypes'] = array();
				$formData['InnovationTypes'][0] =
				$this->view->translate("content-add-select-innovation");

				foreach($innovationTypes as $ivt) {
					$formData['InnovationTypes'][$ivt['id_ivt']] =
					$ivt['name_ivt'];
				} // end foreach

				if(empty($formData['InnovationTypes'])) {
					$formData['InnovationTypes'] = array(0 => '----');
				}

				$languages = New Default_Model_Languages();
				$idLngInd = $languages->getLangIdByLangName($this->view->language);
				$allLanguages = $languages->getAllNamesAndIds();

				$formData['languages'] = array();
				$formData['languages'][0] = $this->view->translate("content-add-select-language");
				foreach($allLanguages as $lng) {
					$formData['languages'][$lng['id_lng']] = $lng['name_lng'];
				}

				$modelIndustries = new Default_Model_Industries();
				$industries = $modelIndustries->getNamesAndIdsById(0, $idLngInd);

				$formData['Industries'] = array();
				$formData['Industries'][0] =
				$this->view->translate("content-add-select-industry");

				foreach($industries as $ind) {
					$formData['Industries'][$ind['id_ind']] = $ind['name_ind'];
				} // end foreach

				if(empty($formData['Industries'])) {
					$formData['Industries'] = array(0 => '----');
				}

				// The id of first industry listed is needed when listing the
				// divisions for the first time
				$firstIndustryId = $modelIndustries->getIndustryId();
				$divisions = $modelIndustries->getNamesAndIdsById(
				$firstIndustryId, $idLngInd
				);

				$formData['Divisions'] = array();
				$formData['Divisions'][0] = $this->view->translate("content-add-select-division-no-industry");

				$formData['Groups'] = array();
				$formData['Groups'][0] = $this->view->translate("content-add-select-group-no-division");

				$formData['Classes'] = array();
				$formData['Classes'][0] = $this->view->translate("content-add-select-class-no-group");
				
				$cache->save($formData, $formDataCacheTag);
			}


			$formCacheTag = 'form_'.$contentType.'_'.$this->view->language;
			// Form for content adding, cacheing if not cached.
			// Generate new form if is post because cache will save post parameters and fail
			if ($this->getRequest()->isPost()) {
				$form = new Default_Form_AddContentForm(null, $formData, $this->view->language, $contentType);
			}

			elseif (!($form = $cache->load($formCacheTag))  ) {
				$form = new Default_Form_AddContentForm(null, $formData, $this->view->language, $contentType);
					
				$cache->save($form, $formCacheTag);
			}
			
			$this->view->form = $form;

			// Get requests
			if($this->getRequest()->isPost()) {
					
				// Get content data
				$data = $this->getRequest()->getPost();

				// If form data is valid, handle database insertions
				if ($form->isValid($data)) {

					// If form data is going to be published
					if(isset($data['content_publish']) && $data['content_publish'] != '') {
						$data['publish'] = 1;
						$message_error = 'content-publish-not-successful';
					}
					// If form data is going to be saved
					elseif(isset($data['content_save']) && $data['content_save'] != '') {
						$data['publish'] = 0;
						$message_error = 'content-save-not-successful';
					}

					// Content keywords
					/* FIXED: split() is deprecated in PHP 5.3.0 -> and removed in
					 * PHP 6.0, so changed to explode(). Also trim(array) doesn't
					 * trim array values, so this is done with foreach now.
					 */
					$keywords = array();
					foreach(explode(',', $data['content_keywords']) as $keyword) {
						if(trim($keyword) != "") {
							$keywords[] = strip_tags(trim($keyword));
						}
					}
					$data['content_keywords'] = array_unique($keywords);

					// Related companies
					$relatedCompanies = array();
					foreach(explode(',', $data['content_related_companies']) as $relatedCompany) {
						if(trim($relatedCompany) != "") {
							$relatedCompanies[] = strip_tags(trim($relatedCompany));
						}
					}
					$data['content_related_companies'] = array_unique($relatedCompanies);

					// Get user id
					$data['User']['id_usr'] = $auth->getIdentity()->user_id;

					if($data['content_division'] == 0) {
						$data['content_industry_id'] = $data['content_industry'];
					} elseif($data['content_group'] == 0) {
						$data['content_industry_id'] = $data['content_division'];
					} elseif($data['content_class'] == 0) {
						$data['content_industry_id'] = $data['content_group'];
					} elseif($data['content_class'] != 0) {
						$data['content_industry_id'] = $data['content_class'];
					}

					$languages = new Default_Model_Languages();

					if($data['content_language'] == 0) {
						$data['content_language'] = $this->view->language;
					}
					else {
						$data['content_language'] = $languages->getLangNameByLangId($data['content_language']);
					}

					$data['files'] = $_FILES['content_file_upload'];
					// Add a new content
					$content = new Default_Model_Content();
					$add = $content->addContent($data);

					if(!$add) {
						$add_successful = false;
					} else {
						$add_successful = true;
					} // end if

					$url = $this->_urlHelper->url(array('controller' => 'msg',
                                                       	'action' => 'index',
                                                        'language' => $this->view->language),
                                                  		'lang_default', true);

					if($add_successful) {
						if($data['publish'] == 1) {
							$url = $this->_urlHelper->url(array('content_id' => $add,
                                         						'language' => $this->view->language), 
                                         						'content_shortview', true);
							$this->_redirect($url);
						}
						else {
							$userpage = $this->_urlHelper->url(array('controller' => 'account',
                                                         			 'action' => 'view', 
                                                         			 'user' => $auth->getIdentity()->username, 
			                                                         'language' =>  $this->view->language), 
            			                                             'lang_default', true);
							$savedTab = $this->_urlHelper->url(array('controller' => 'account',
                                                   				     'action' => 'view', 
                                                         			 'user' => $auth->getIdentity()->username,
                                                         			 'type' => 'saved',
                                                         			 'language' =>  $this->view->language), 
                                                         			 'lang_default', true);

							$message_ok = $this->view->translate('content-save-successful');
							$message_ok .= ' ('.$content->getContentHeaderByContentId($add).')';
							$message_ok .= '<br /><br />' . $this->view->translate('content-save-successful2');
							$message_ok .= ' <a href="'.$userpage.'">'.$this->view->translate('content-save-successful3').'</a>';
							$message_ok .= ' ' . $this->view->translate('content-save-successful4');
							$message_ok .= ' <a href="'.$savedTab.'">'.$this->view->translate('content-save-successful5').'</a>.';
							$this->flash($message_ok, $url);
						}
					}
					else {
						$this->flash($message_error, $url);
					}
				}
			} // end if

		} else {
			// If not logged, redirecting to system message page
			$message = 'content-add-not-logged';

			$url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index',
                                                'language' => $this->view->language),
                                          'lang_default', true); 

			$this->flash($message, $url);
		} // end if
	} // end of addAction()

	/**
	 *   makelinksAction
	 *
	 *   Make content link to content.
	 *
	 */
	public function makelinksAction() {
		// Get authentication
		$auth = Zend_Auth::getInstance();
		$absoluteBaseUrl = strtolower(trim(array_shift(explode('/', $_SERVER['SERVER_PROTOCOL'])))) .
    						'://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getBaseUrl();

		// If user has identity
		if ($auth->hasIdentity())
		{
			// Get requests
			$params = $this->getRequest()->getParams();

			// Get content type
			$contenttype = isset($params['contenttype']) ? $params['contenttype'] : '';

			$relatestoid = isset($params['parentid']) ? $params['parentid'] : '';

			$linkedcontentid = isset($params['childid']) ? $params['childid'] : '';

			if($this->validateLinking($contenttype, $relatestoid, $linkedcontentid)) {
				$model_cnt_has_cnt = new Default_Model_ContentHasContent();
				$model_cnt_has_cnt->addContentToContent($relatestoid, $linkedcontentid);

				// Send email to owner of content about a new link
				// if user allows linking notifications

				$userModel = new Default_Model_User();
				$owner = $userModel->getContentOwner($relatestoid);

				$notificationsModel = new Default_Model_Notifications();
				$notifications = $notificationsModel->getNotificationsById($owner['id_usr']);

				if (in_array('link', $notifications)) {
					$cntModel = new Default_Model_Content();
					$originalHeader = $cntModel->getContentHeaderByContentId($relatestoid);
					$linkedHeader =  $cntModel->getContentHeaderByContentId($linkedcontentid);

					$senderId = $auth->getIdentity()->user_id;
					$senderName = $auth->getIdentity()->username;
					$emailNotification = new Oibs_Controller_Plugin_Email();
					$emailNotification->setNotificationType('link')
					->setSenderId($auth->getIdentity()->user_id)
					->setReceiverId($owner['id_usr'])
					->setParameter('URL', $absoluteBaseUrl."/en")
					->setParameter('SENDER-NAME', $senderName)
					->setParameter('LINKED-ID', $linkedcontentid)
					->setParameter('LINKED-TITLE', $linkedHeader)
					->setParameter('ORIGINAL-ID', $relatestoid)
					->setParameter('ORIGINAL-TITLE', $originalHeader);

					if ($emailNotification->isValid()) {
						$emailNotification->send();
					} else {
						//echo $emailNotification->getErrorMessage(); die;
					}
				}


				$url = $this->_urlHelper->url(array('controller' => 'view',
                                                    'action' => $relatestoid, 
                                                    'language' => $this->view->language), 
                                              		'lang_default', true);

				$message = "content-linked-successfully";
				$this->flash($message, $url);
			}
		}
		else {
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
	 *   removelinksAction
	 *
	 *   Remove content link from content.
	 *
	 *   @author Mikko Korpinen
	 */
	public function removelinksAction() {
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

			$relatestoid = isset($params['parentid'])
			? $params['parentid'] : '';

			$linkedcontentid = isset($params['childid'])
			? $params['childid'] : '';

			$model_cnt_has_cnt = new Default_Model_ContentHasContent();
			$model_cnt_has_cnt->removeContentFromContent($relatestoid, $linkedcontentid);

			$message = 'content-unlink-successful';

            // TODO:
            // Tell the user that the unlink was created.

            // Redirect back to the user page
            $redirectUrl = $this->_urlHelper->url(array('controller' => 'account',
                                                        'action' => 'view',
                                                        'user' => $auth->getIdentity()->username,
                                                        'language' => $this->language),
                                                  'lang_default', true);
            $this->_redirector->gotoUrl($redirectUrl);

            /*
			$url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index',
                                                'language' => $this->view->language),
                                                'lang_default', true);

			$this->flash($message, $url); */
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
	 *   linkAction
	 *
	 *   Get user contents which are related to particular content type
	 *
	 *   @author ???
	 *   @author 2010 Mikko Korpinen
	 *
	 */
	public function linkAction() {
		// Get authentication
		$auth = Zend_Auth::getInstance();
		// If user has identity
		if ($auth->hasIdentity())
		{
			// Get requests
			$params = $this->getRequest()->getParams();

			// Get content type
			$contenttype = isset($params['contenttype']) ? $params['contenttype'] : '';
			$relatestoid = isset($params['relatestoid']) ? $params['relatestoid'] : '';

			if($this->validateLinking($contenttype, $relatestoid, -1)) {
				$model_content_types = new Default_Model_ContentTypes();
				$model_cnt_has_cnt = new Default_Model_ContentHasContent();

				$id_usr = $auth->getIdentity()->user_id;
				$id_cty = $model_content_types->getIdByType($contenttype);

				$userModel = new Default_Model_User();
				$userContents = $userModel->getUserContent($id_usr);

				$contents = array();

				// If user have not this types content then set false
				$hasUserContents = true;

				if(!$this->checkIfArrayHasKeyWithValue($userContents, "id_cty_cnt", $id_cty)) {
					$this->view->linkingContentType = $contenttype;
					$hasUserContents = false;
				} else {
					foreach($userContents as $content) {
						if(!$model_cnt_has_cnt->checkIfContentHasContent($relatestoid, $content['id_cnt']) &&
						!$model_cnt_has_cnt->checkIfContentHasContent($content['id_cnt'], $relatestoid)) {
							if($content['id_cty_cnt'] == $id_cty && $content['id_cnt'] != $relatestoid) {
								$contents[] = $content;
							}
						}
					}
					$this->view->relatesToId = $relatestoid;
					$this->view->linkingContentType = $contenttype;
					$this->view->contents = $contents;
					$this->view->hasUserContents = $hasUserContents;
				}
			}
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
	 *   unlinkAction
	 *
	 *   Get user contents which are related to particular content
	 *
	 *   @author 2010 Mikko Korpinen
	 *
	 */
	public function unlinkAction() {
		// Get authentication
		$auth = Zend_Auth::getInstance();
		// If user has identity
		if ($auth->hasIdentity())
		{
			// Get requests
			$params = $this->getRequest()->getParams();

			$relatestoid = isset($params['relatestoid'])
			? $params['relatestoid'] : '';

			$contenttype = '';
			$contents = array();

			$model_content = new Default_Model_Content();
			$contentexists = $model_content->checkIfContentExists($relatestoid);

			if ($contentexists) {
				$relatesToContent = $model_content->getDataAsSimpleArray($relatestoid);
				$this->view->relatesToContentTitle = $relatesToContent['title_cnt'];

				$model_content_types = new Default_Model_ContentTypes();
				$model_cnt_has_cnt = new Default_Model_ContentHasContent();

				$contenttype = $model_content_types->getTypeById($relatesToContent['id_cty_cnt']);

				$contentContents = $model_cnt_has_cnt->getContentContents($relatestoid);

				$id_usr = $auth->getIdentity()->user_id;
			}
			$this->view->contentexists = $contentexists;
			$this->view->relatesToId = $relatestoid;
			$this->view->linkingContentType = $contenttype;
			$this->view->contents = $contentContents;
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
	 *  This function validates linking before linking is made
	 *
	 */
	public function validateLinking($contenttype, $relatestoid, $linkedcontentid = -1) {
		// Get all content types from the database
		$model_content_types = new Default_Model_ContentTypes();
		$model_content = new Default_Model_Content();
		$model_cnt_has_usr = new Default_Model_ContentHasUser();
		$model_cnt_has_cnt = new Default_Model_ContentHasContent();

		$content_types = $model_content_types->getAllNamesAndIds();

		// Setting the variable first to be true
		$invalid_contenttype = true;

		// If set content type exists in database, invalid_contenttype
		// is set to false
		foreach($content_types as $cty){
			if($contenttype == $cty['key_cty']) {
				$invalid_contenttype = false;
			}
		}

		// Setting the variable first to be true
		$invalid_relatestoid = true;

		// If related content exists in the database, invalid_relatestoid
		// is set to false
		if($model_content->checkIfContentExists($relatestoid)) {
			$invalid_relatestoid =  false;
			$relatesToContent = $model_content->getDataAsSimpleArray($relatestoid);

			$this->view->relatesToContentTitle = $relatesToContent['title_cnt'];
			$this->view->relatesToContentTitle = $relatesToContent['title_cnt'];
			$this->view->relatesToContentContentType = $model_content_types->getTypeById($relatesToContent['id_cty_cnt']);
		}

		if(!$invalid_contenttype && !$invalid_relatestoid) {
			if($linkedcontentid == -1) {
				return true;
			} else {
				$linkedContent = $model_content->getContentRow($linkedcontentid);
				if($linkedContent['published_cnt'] != 0) {
					if($model_content->checkIfContentExists($linkedcontentid)) {
						// User can not link content with themselves
						if ($relatestoid == $linkedcontentid) {
							$message = 'content-link-themselves';

							$url = $this->_urlHelper->url(array('controller' => 'msg',
                                                                'action' => 'index',
                                                                'language' => $this->view->language),
                                                          		'lang_default', true);

							$this->flash($message, $url);
						}

						$auth = Zend_Auth::getInstance();
						$id_usr = $auth->getIdentity()->user_id;

						// If user owns the content that is going to be linked, returning true
						if($model_cnt_has_usr->contentHasOwner($id_usr, $linkedcontentid)) {
							return true;
						} else {
							$message = 'content-link-not-content-owner';

							$url = $this->_urlHelper->url(array('controller' => 'msg',
                                                                'action' => 'index', 
                                                                'language' => $this->view->language), 
                                                          		'lang_default', true);

							$this->flash($message, $url);
						}
					} else {
						$message = 'content-link-linked-content-not-exist';

						$url = $this->_urlHelper->url(array('controller' => 'msg',
                                                            'action' => 'index', 
                                                            'language' => $this->view->language), 
                                                      		'lang_default', true);

						$this->flash($message, $url);
					}
				} else {
					$message = 'content-link-not-published';

					$url = $this->_urlHelper->url(array('controller' => 'msg',
                                                    	'action' => 'index', 
                                                   	 	'language' => $this->view->language), 
                                              			'lang_default', true);

					$this->flash($message, $url);
				}
			}
		} else {
			if($invalid_contenttype) {
				$message = 'content-link-invalid-contenttype';

				$url = $this->_urlHelper->url(array('controller' => 'msg',
                                                    'action' => 'index', 
                                                    'language' => $this->view->language), 
                                              		'lang_default', true);

				$this->flash($message, $url);
			} elseif($invalid_relatestoid) {
				$message = 'content-link-invalid-relatestoid';

				$url = $this->_urlHelper->url(array('controller' => 'msg',
                                                    'action' => 'index', 
                                                    'language' => $this->view->language), 
                                              		'lang_default', true);

				$this->flash($message, $url);
			}
		}
	}

	public function previewAction()
	{
		// Get authentication
		$auth = Zend_Auth::getInstance();
		
		// Disable layout to be rendered
		$this->_helper->layout->disableLayout();
		
		// If user has authenticated
		if($auth->hasIdentity())
		{
			// Get user data
			$userId = $auth->getIdentity()->user_id;
			$userName = $auth->getIdentity()->username;
			$userModel = new Default_Model_User();
			$userData = $userModel->getSimpleUserDataById($userId);
			
			// Get requests
			if($this->getRequest()->isPost())
			{
				// Get POST data and convert it to UTF-8 compatible html entities
				$rawpostData = $this->getRequest()->getPost();
				foreach($rawpostData as $key => $value)
					$postData[$key] = htmlentities($value, ENT_QUOTES, "UTF-8");

				// Set today's date and time
				$today = date('Y-m-d H:i:m');
	
				// Get content type of the specific content viewed
				$contentTypesModel = New Default_Model_ContentTypes();
				$contentType = $contentTypesModel->getTypeById($postData['content_type']);
	
				// Reformat preview data
				$contentData =
				array('id_cnt' 					=> 'preview',
						  'id_cty_cnt' 				=> $postData['content_type'],
						  'title_cnt' 				=> $postData['content_header'],
						  'lead_cnt' 				=> $postData['content_textlead'],
						  'language_cnt' 			=> $postData['content_language'],
						  'body_cnt' 				=> $postData['content_text'],
						  'research_question_cnt' 	=> $postData['content_research'],
						  'opportunity_cnt' 		=> $postData['content_opportunity'],
						  'threat_cnt' 				=> $postData['content_threat'],
						  'solution_cnt' 			=> $postData['content_solution'],
						  'references_cnt' 			=> $postData['content_references'],
						  'views_cnt' 				=> 0,
						  'published_cnt' 			=> 1,
						  'created_cnt' 			=> $today,
						  'modified_cnt' 			=> $today,
						  'id_usr' 					=> $userId,
						  'login_name_usr' 			=> $userName,
						  'key_cty' 				=> $postData['content_type'],
						  'name_cty'				=> $contentType
				);

				// Reformat tags
				$rawtags = explode(",", $postData['content_keywords']);
				foreach($rawtags as $rawtag)
					$tags[count($tags)]['name_tag'] = $rawtag;
	
				// Get form
				$form = new Default_Form_PreviewContentForm();
	
				// Inject previewdata to view
				$this->view->previewMode		= 1;
				$this->view->files 				= null;
				$this->view->id					= 'preview';
				//$this->view->industries         = $industries;
				//$this->view->userImage          = $userImage;
				//$this->view->commentPaginator   = $paginator;
				//$this->view->commentData        = $commentsSorted;
				//$this->view->user_can_comment   = $user_can_comment;
				$this->view->contentData        = $contentData;
				//$this->view->modified			= $contentData['modified_cnt'];
				$this->view->userData           = $userData;
				//$this->view->moreFromUser       = $moreFromUser;
				$this->view->views              = $contentData['views_cnt'];
				//$this->view->rating             = $rating;
				$this->view->tags               = $tags;
				//$this->view->links              = $links;
				//$this->view->parents            = $parents;
				//$this->view->parent_siblings    = $parent_siblings;
				//$this->view->children           = $children;
				//$this->view->children_siblings  = $children_siblings;
				//$this->view->rivals             = $rivals;
				//$this->view->comments           = $commentCount;
				$this->view->contentType        = $contentType;
				//$this->view->count              = $count;
				$this->view->form				= $form;
				//$this->view->favourite			= $favourite;
	
				// Inject title to view
				$this->view->title = $this->view->translate('index-home') . " - " . $contentData['title_cnt'];
			}
		}
		else
		{
			$message = 'content-preview-not-logged-in';

			$url = $this->_urlHelper->url(array('controller' => 'msg',
	                                            'action' => 'index', 
	                                            'language' => $this->view->language), 
	                                            'lang_default', true);

			$this->flash($message, $url);
		}
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
		if ($auth->hasIdentity()) {
			// Get requests
			$params = $this->getRequest()->getParams();

			// Get session data
			$previewSession = new Zend_Session_Namespace('contentpreview');

			// If preview
			$backFromPreview = isset($previewSession->backFromPreview) ? $previewSession->backFromPreview : 0;
			$preview = isset($params['preview']) ? 1:0;
			if($preview)
			{
				$previewSession->unsetAll();
				$previewSession->previewData = $params;
				$backToUrl = $this->getRequest()->getRequestUri();
				$previewSession->backToUrl = $backToUrl;

				$url = $this->_urlHelper->url(array('controller' => 'content',
													'action' => 'preview',
													'language' => $this->view->language),
													'lang_default', true);
				$this->_redirect($url);
			}

			// Get content type
			$contentId = isset($params['content_id'])
			? $params['content_id'] : 0;

			$userId = $auth->getIdentity()->user_id;

			$cntHasUsr = New Default_Model_ContentHasUser();
			$userIsOwner = $cntHasUsr->contentHasOwner($userId, $contentId);

			if($userIsOwner) {
				if($contentId != 0) {
					$content = New Default_Model_Content();
					$data = $content->getDataAsSimpleArray($contentId);

					// Creating array for form data
					$formData = array();

					// Adding content type to form
					$formData['content_type'] = $data['id_cty_cnt'];

					// Adding content id to form
					$formData['content_id'] = $contentId;

					$formData['content_header'] = stripslashes($data['title_cnt']);

					$modelCntHasTag = New Default_Model_ContentHasTag();
					$keywords = $modelCntHasTag->getContentTags($data['id_cnt']);

					$tags = "";
					$tagCount = count($keywords);

					for($i = 0; $i < $tagCount; $i++) {
						$tags .= $keywords[$i]['name_tag'];
						if ($i != $tagCount - 1) {
							$tags .= ', ';
						}
					}

					$formData['content_keywords'] = stripslashes($tags);
					$formData['content_textlead'] = stripslashes($data['lead_cnt']);
					$formData['content_text'] = stripslashes($data['body_cnt']);

					$modelCntHasRec = New Default_Model_ContentHasRelatedCompany();
					$relComps = $modelCntHasRec->getContentRelComps($data['id_cnt']);

					$recs = "";
					$recCount = count($relComps);

					for($i = 0; $i < $recCount; $i++) {
						$recs .= $relComps[$i]['name_rec'];
						if ($i != $recCount - 1) {
							$recs .= ', ';
						}
					}

					$formData['content_related_companies'] = stripslashes($recs);
					$formData['content_research'] = $data['research_question_cnt'];
					$formData['content_opportunity'] = $data['opportunity_cnt'];
					$formData['content_threat'] = $data['threat_cnt'];
					$formData['content_solution'] = $data['solution_cnt'];

					$formData['published_cnt'] = $data['published_cnt'];

					$formData['content_references'] = $data['references_cnt'];

					$languages = New Default_Model_Languages();
					$idLngInd = $languages->getLangIdByLangName($this->view->language);
					$allLanguages = $languages->getAllNamesAndIds();

					$formData['languages'] = array();
					$formData['languages'][0] = $this->view->translate("content-add-select-language");
					foreach($allLanguages as $lng) {
						$formData['languages'][$lng['id_lng']] = $lng['name_lng'];
					}

					$finfoClasses = new Default_Model_FutureinfoClasses();
					$allClasses = $finfoClasses->getAllNamesAndIds();

					$formData['FutureinfoClasses'] = array();
					$formData['FutureinfoClasses'][0] = $this->view->translate("content-add-select-finfo-classification");
					foreach($allClasses as $class) {
						$formData['FutureinfoClasses'][$class['id_fic']] = $class['name_fic'];
					}

					// Getting innovation types from the database
					$modelInnovationTypes = new Default_Model_InnovationTypes();
					$innovationTypes = $modelInnovationTypes->getAllNamesAndIds();

					// Getting the innovation type of the content
					$modelCntHasIvt = new Default_Model_ContentHasInnovationTypes();
					$formData['selected_ivt'] =
					$modelCntHasIvt->getInnovationTypeIdOfContent($data['id_cnt']);

					// Adding all innovation types to form
					$formData['InnovationTypes'] = array();
					$formData['InnovationTypes'][0] =
					$this->view->translate("content-add-select-innovation");

					foreach($innovationTypes as $ivt) {
						$formData['InnovationTypes'][$ivt['id_ivt']] =
						$ivt['name_ivt'];
					} // end foreach

					if(empty($formData['InnovationTypes'])) {
						$formData['InnovationTypes'] = array(0 => '----');
					}

					$languages = New Default_Model_Languages();
					$idLngInd = $languages->getLangIdByLangName($this->view->language);

					// Getting language of the content
					$formData['content_language'] = $languages->getLangIdByLangName($data['language_cnt']);

					// Getting the industry of the content
					$modelCntHasInd = new Default_Model_ContentHasIndustries();
					$cntInd = $modelCntHasInd->getIndustryIdOfContent($data['id_cnt']);

					// Getting industries from the database
					$modelIndustries = new Default_Model_Industries();
					$industries = $modelIndustries->getNamesAndIdsById(0, $idLngInd);

					// Getting all industries of the content
					$industryIds = $modelIndustries->getAllContentIndustryIds($cntInd);
					$formData['industryIds'] = $industryIds;

					// Adding all industries of the content to form
					$formData['selected_industry'] = $industryIds[0];
					$formData['selected_division'] = $industryIds[1];
					$formData['selected_group'] = $industryIds[2];
					$formData['selected_class'] = $industryIds[3];

					// Adding all industries to form
					$formData['Industries'] = array();
					$formData['Industries'][0] =
					$this->view->translate("content-add-select-industry");

					foreach($industries as $ind) {
						$formData['Industries'][$ind['id_ind']] = $ind['name_ind'];
					} // end foreach

					if(empty($formData['Industries'])) {
						$formData['Industries'] = array(0 => '----');
					}

					// Adding all divisions to form
					$formData['Divisions'] = array();
					$formData['Divisions'][0] = $this->view->translate("content-add-select-division-no-industry");

					if($industryIds[0] != 0) {
						$divisions = $modelIndustries
						->getNamesAndIdsById($industryIds[0], $idLngInd);

						foreach($divisions as $div) {
							$formData['Divisions'][$div['id_ind']] = $div['name_ind'];
						} // end foreach
					}

					// Adding all groups to form
					$formData['Groups'] = array();
					$formData['Groups'][0] = $this->view->translate("content-add-select-group-no-division");
					if($industryIds[1] != 0) {
						$groups = $modelIndustries->getNamesAndIdsById($industryIds[1], $idLngInd);

						foreach($groups as $grp) {
							$formData['Groups'][$grp['id_ind']] = $grp['name_ind'];
						} // end foreach
					}

					$formData['Classes'] = array();
					$formData['Classes'][0] = $this->view->translate("content-add-select-class-no-group");

					// If there's no group selected
					if($industryIds[2] != 0) {
						$classes = $modelIndustries->getNamesAndIdsById($industryIds[2], $idLngInd);

						foreach($classes as $class) {
							$formData['Classes'][$class['id_ind']] = $class['name_ind'];
						} // end foreach
					}

					$modelContentTypes = new Default_Model_ContentTypes();
					$contentType = $modelContentTypes->getTypeById($data['id_cty_cnt']);
					$this->view->short_contenttype = $contentType;

					$title_cnt = $content->getContentHeaderByContentId($data['id_cnt']);

					$this->view->contentHeader = $title_cnt;

					// Get contents filenames from database
					$filesModel = new Default_Model_Files();
					$filenames = $filesModel->getFilenamesByCntId($contentId);
					$formData['filenames'] = $filenames;

					// Form for content adding
					$form = new Default_Form_EditContentForm(null, $formData, $contentId, $contentType, $this->view->language);
					$form->populate($formData);
					$this->view->form = $form;
					$url = $this->_urlHelper->url(array('controller' => 'msg',
                                                        'action' => 'index', 
                                                        'language' => $this->view->language),
                                                  		'lang_default', true);

					// populate form
					if($backFromPreview)
					{
						// Get previewdata and populate it to form
						$previewData = $previewSession->previewData;
						$form->populate($previewData);

						// Delete session data
						$previewSession->unsetAll();
					}

					// If posted
					if($this->getRequest()->isPost()) {
						// Get content data
						$data = $this->getRequest()->getPost();
						// Content id
						$data['content_id'] = $contentId;

						// If form data is valid, handle database insertions
						if($form->isValid($data)) {
							// If form data is going to be published

							if(isset($data['content_publish'])) {
								$data['publish'] = 1;
								$message_error = 'content-publish-not-successful';
							}
							// If form data is going to be saved
							elseif(isset($data['content_save'])) {
								$data['publish'] = 0;
								$message_error = 'content-save-not-successful';
							}

							// Content keywords
							/* FIXED: split() is deprecated in PHP 5.3.0 -> and removed in
							 * PHP 6.0, so changed to explode(). Also trim(array) doesn't
							 * trim array values, so this is done with foreach now.
							 */
							$keywords = array();
							foreach(explode(',', $data['content_keywords']) as $keyword) {
								if(trim($keyword) != "") {
									$keywords[] = strip_tags(trim($keyword));
								}
							}

							$data['content_keywords'] = array_unique($keywords);

							// Related companies
							$relatedCompanies = array();
							foreach(explode(',', $data['content_related_companies']) as $relatedCompany) {
								if(trim($relatedCompany) != "") {
									$relatedCompanies[] = strip_tags(trim($relatedCompany));
								}
							}

							$data['content_related_companies'] = array_unique($relatedCompanies);

							// Get user id
							$data['User']['id_usr'] = $auth->getIdentity()->user_id;

							if($data['content_division'] == 0) {
								$data['content_industry_id'] = $data['content_industry'];
							} elseif($data['content_group'] == 0) {
								$data['content_industry_id'] = $data['content_division'];
							} elseif($data['content_class'] == 0) {
								$data['content_industry_id'] = $data['content_group'];
							} elseif($data['content_class'] != 0) {
								$data['content_industry_id'] = $data['content_class'];
							}

							if($data['content_language'] == 0) {
								$data['content_language'] = $this->view->language;
							} else {
								$data['content_language'] = $languages->getLangNameByLangId($data['content_language']);
							}

							//echo "<pre>"; print_r($data); echo "</pre>"; die();

							$data['files'] = $_FILES['content_file_upload'];

							// Edit content
							$content = new Default_Model_Content();
							$edit = $content->editContent($data);

							$url = $this->_urlHelper->url(array('controller' => 'msg',
                                                                'action' => 'index',
                                                                'language' => $this->view->language),
                                                          		'lang_default', true);

							if($edit) {
								$favourite = new Default_Model_UserHasFavourites();
								$favouriteEdited = $favourite->setFavouriteModifiedTrue($edit);

								if($data['publish'] == 1) {
									$url = $this->_urlHelper->url(array('content_id' => $edit,
                                                                        'language' => $this->view->language), 
                                                                  'content_shortview', true);
									$this->_redirect($url);
								} else {
									$message_ok = $this->view->translate('content-save-successful');
									$message_ok .= ' ('.$content->getContentHeaderByContentId($edit).')';
									$message_ok .= '<br /><br />' . $this->view->translate('content-save-successful2');
									$userpage = $this->_urlHelper->url(array('controller' => 'account',
                                                                             'action' => 'view', 
                                                                             'user' => $auth->getIdentity()->username, 
                                                                             'language' =>  $this->view->language), 
                                                                       'lang_default', true);
									$message_ok .= ' <a href="'.$userpage.'">'.$this->view->translate('content-save-successful3').'</a>';
									$message_ok .= ' ' . $this->view->translate('content-save-successful4');
									$this->flash($message_ok, $url);
								}
							} else {
								$this->flash($message_error, $url);
							}
						} else {
							// What is this?
							//Zend_Debug::dump($form); die;
						}

						/*
						 // Content keywords
						 $keywords = split(', ', trim($data['content_keywords']));
						 $data['content_keywords'] = array_unique($keywords);

						 if($data['content_group'] == 0) {
						 $data['content_industry_id'] = $data['content_division'];
						 } elseif($data['content_class'] == 0) {
						 $data['content_industry_id'] = $data['content_group'];
						 } elseif($data['content_class'] != 0) {
						 $data['content_industry_id'] = $data['content_class'];
						 }

						 // Update the edited content to database
						 $content = new Default_Model_Content();
						 if($content->editContent($data)) {
						 $message = 'content-edit-successful';
						 } else {
						 $message = 'content-edit-not-successful';
						 }
						 $this->flash($message, $url);*/
					}
				} else {
					$message = 'content-edit-no-id';
					$this->flash($message, $url);
				}
			} else {
				$message = 'content-edit-not-owner';
				$this->flash($message, $url);
			}
		} else {
			// If not logged, redirecting to system message page
			$message = 'content-edit-not-logged';
			$this->flash($message, $url);
		} // end else
	}

	/**
	 *   removeAction
	 *
	 *   Remove content
	 *   @author ???? ? ? & 2010 Mikko Korpinen
	 */
	public function removeAction()
	{
		$params = $this->getRequest()->getParams();
		$contentId = (int)$params['content_id'];

		$auth = Zend_Auth::getInstance();

		// Get cache from registry
		$cache = Zend_Registry::get('cache');
		// Recent posts id
		$cachePosts = 'IndexPosts_' . $this->view->language;

		if ($auth->hasIdentity()) {
			$userId = $auth->getIdentity()->user_id;

			$content = new Default_Model_Content();
			$url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index',
                                                'language' => $this->view->language),
                                                'lang_default', true);

			if($content->checkIfContentExists($contentId)) {
				$cntHasUsr = new Default_Model_ContentHasUser();
				$userIsOwner = $cntHasUsr->contentHasOwner($userId, $contentId);

				if($userIsOwner) {

					$contentRemoveSuccessful = true;

					// Remove content and all dependign stuff
					$content = new Default_Model_Content();
					$contentRemoveChecker = $content->removeContentAndDepending($contentId);

					// Remove recent post cache
					$cache->remove($cachePosts);


					foreach($contentRemoveChecker as $crc) {
						if (!$crc) {
							$contentRemoveSuccessful = false;
							break;
						}
					}

					if($contentRemoveSuccessful == true) {
						$message = 'content-remove-successful';
						$this->flash($message, $url);
					} else {
						$message = $this->view->translate('content-remove-not-successful') . '<br />';
						// User don't have to see these explanations
						/*
						 if(!$contentRemoveChecker['removeContentFromCampaign']) $message .= $this->view->translate('content-remove-removeContentFromCampaign') . '<br />';
						 if(!$contentRemoveChecker['removeContentFromContent']) $message .= $this->view->translate('content-remove-removeContentFromContent-not-successful') . '<br />';
						 if(!$contentRemoveChecker['removeContentFromFutureinfoClasses']) $message .= $this->view->translate('content-remove-removeContentFromFutureinfoClasses-not-successful') . '<br />';
						 if(!$contentRemoveChecker['removeContentFromIndustries']) $message .= $this->view->translate('content-remove-removeContentFromIndustries-not-successful') . '<br />';
						 if(!$contentRemoveChecker['removeContentFromInnovationTypes']) $message .= $this->view->translate('content-remove-removeContentFromInnovationTypes-not-successful') . '<br />';
						 if(!$contentRemoveChecker['removeContentFromRelatedCompanies']) $message .= $this->view->translate('content-remove-removeContentFromRelatedCompanies-not-successful') . '<br />';
						 if(!$contentRemoveChecker['removeContentRelatedCompanies']) $message .= $this->view->translate('content-remove-removeContentRelatedCompanies-not-successful') . '<br />';
						 if(!$contentRemoveChecker['removeContentFromTags']) $message .= $this->view->translate('content-remove-removeContentFromTags-not-successful') . '<br />';
						 if(!$contentRemoveChecker['removeContentTags']) $message .= $this->view->translate('content-remove-removeContentTags-not-successful') . '<br />';
						 if(!$contentRemoveChecker['removeContentFromUser']) $message .= $this->view->translate('content-remove-removeContentFromUser-not-successful') . '<br />';
						 if(!$contentRemoveChecker['removeContentViews']) $message .= $this->view->translate('content-remove-removeContentViews-not-successful') . '<br />';
						 if(!$contentRemoveChecker['removeContentFlags']) $message .= $this->view->translate('content-remove-removeContentFlags-not-successful') . '<br />';
						 if(!$contentRemoveChecker['removeContentCommentFlags']) $message .= $this->view->translate('content-remove-removeContentCommentFlags-not-successful') . '<br />';
						 if(!$contentRemoveChecker['removeContentRatings']) $message .= $this->view->translate('content-remove-removeContentRatings-not-successful') . '<br />';
						 if(!$contentRemoveChecker['removeContentFiles']) $message .= $this->view->translate('content-remove-removeContentFiles-not-successful') . '<br />';
						 if(!$contentRemoveChecker['removeUserHasFavorites']) $message .= $this->view->translate('content-remove-removeUserHasFavorites-not-successful') . '<br />';
						 if(!$contentRemoveChecker['removeContent']) $message .= $this->view->translate('content-remove-removeContent-content-not-successful') . '<br />';
						 if(!$contentRemoveChecker['removeContentComments']) $message .= $this->view->translate('content-remove-removeContentComments-not-successful') . '<br />';
						 */

						$this->flash($message, $url);
					}
				} else {
					$message = 'content-remove-not-owner';
					$this->flash($message, $url);
				}
			} else {
				$message = 'content-remove-invalid-content-id';
				$this->flash($message, $url);
			}
		} else {
			$message = 'content-remove-not-authed';
			$this->flash($message, $url);
		}
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
		$contentId = (int)$params['content_id'];

		$auth = Zend_Auth::getInstance();

		$username = null;
		$message = '';

		if ($auth->hasIdentity()) {
			$userId = $auth->getIdentity()->user_id;
			$username = $auth->getIdentity()->username;

			$content = new Default_Model_Content();
			$url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index', 
                                                'language' => $this->view->language),
                                          'lang_default', true);

			if($content->checkIfContentExists($contentId)) {

				//$contentUrl = $this->baseUrl ."/". $this->view->language ."/view/".$contentId;
				$contentUrl = $this->_urlHelper->url(array('controller' => 'view',
                                                'action' => $contentId, 
                                                'language' => $this->view->language),
                                          'lang_default', true);

				$cntHasUsr = new Default_Model_ContentHasUser();
				$userIsOwner = $cntHasUsr->contentHasOwner($userId, $contentId);

				if($userIsOwner) {
					if($content->publishContent($contentId)) {
						$message = $this->view->translate('content-publish-successful');
						$message .= " ".$this->view->translate('content-publish-click');
						$message .= " <a href=\"".$contentUrl."\">";
						$message .= " ".$this->view->translate('content-publish-here');
						$message .= "</a> ";
						$message .= " ".$this->view->translate('content-publish-view-content');
						//$this->flash($message, $url);
					} else {
						$message = 'content-publish-not-successful';
						//$this->flash($message, $url);
					}
				} else {
					$message = 'content-publish-not-owner';
					//$this->flash($message, $url);
				}
			} else {
				$message = 'content-publish-invalid-content-id';
				//$this->flash($message, $url);
			}
		} else {
			$message = 'content-publish-not-authed';
			//$this->flash($message, $url);
		}

		// Add login to log
		$logger = Zend_Registry::get('logs');
		if(isset($logger['contentpublish'])) {
			$logMessage = sprintf(
                'Publish attempt FROM: %s USER: %s. MESSAGE: %s. CONTENT ID: %s.', 
			$_SERVER['REMOTE_ADDR'],
			$username,
			$this->view->translate($message, 'en'),
			$contentId
			);

			$logger['contentpublish']->notice($logMessage);
		}

		$this->flash($message, $url);
	} // end of publishAction


	/**
	 * TODO: The below 3 functions (divisionAction, groupAction, classAction)
	 * should be made as 1
	 *
	 */

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

		$languages = new Default_Model_Languages();
		$idLngInd = $languages->getLangIdByLangName($this->view->language);

		$modelIndustries = new Default_Model_Industries();
		$divisions = $modelIndustries->getNamesAndIdsById($industryid, $idLngInd);
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

		$languages = New Default_Model_Languages();
		$idLngInd = $languages->getLangIdByLangName($this->view->language);

		$modelIndustries = new Default_Model_Industries();
		$groups = $modelIndustries->getNamesAndIdsById($divisionid, $idLngInd);
		$this->view->groups = $groups;
	}

	/**
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

		$languages = New Default_Model_Languages();
		$idLngInd = $languages->getLangIdByLangName($this->view->language);

		$modelIndustries = new Default_Model_Industries();
		$classes = $modelIndustries->getNamesAndIdsById($groupid, $idLngInd);
		$this->view->classes = $classes;
	}

	public function flagAction()
	{
		// Set an empty layout for view
		$this->_helper->layout()->setLayout('empty');

		// Get requests
		$params = $this->getRequest()->getParams();
		$flaggedId = $params['flaggedid'];

		// Models for the job
		$auth = Zend_Auth::getInstance()->getIdentity();
		$userId = $auth->user_id;
		$flagmodel = new Default_Model_ContentFlags();
		$flagExists = $flagmodel->flagExists($flaggedId, $userId);
		$contentmodel = new Default_Model_Content();
		$contentExists = $contentmodel->checkIfContentExists($flaggedId);
		if($contentExists == true)
		{
			if($flagExists == true)
			{
				$success = 0;
			}
			elseif($flagExists == false)
			{
				$success = 1;
				$flagmodel->addFlag($flaggedId, $userId);
			}
		}
		elseif($contentExists == false)
		{
			$success = 0;
		}

		$this->view->success = $success;
	}

	/**
	 * ajaxIndustryAction
	 *
	 * This function handles the population of industry selection
	 * used in addAction and editAction.
	 *
	 * NOTE: This function is to replace functions:
	 * classAction, divisionAction and groupAction,
	 * since it's bad to have 3 functions for the same thing.
	 */
	public function ajaxindustryAction()
	{
		// Set views layout to empty
		$this->_helper->layout()->setLayout('empty');

		// Get paramesters
		$type = $this->_request->getParam('type');
		$id = $this->_request->getParam('id');

		if(isset($type) && isset($id)) {
			// Get language id
			$languages = New Default_Model_Languages();
			$langId = $languages->getLangIdByLangName($this->view->language);

			$industriesModel = new Default_Model_Industries();
			$industries = $industriesModel->getNamesAndIdsById($id, $langId);

			// Set to view
			$this->view->industries = $industries;
			$this->view->type = $type;
		}
	}
}

