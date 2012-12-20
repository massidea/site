<?php
/**
 * SearchController -> main pages
 *
 * Copyright (c) <2009>, Joel Peltonen <joel.peltonen@cs.tamk.fi>
 * Copyright (c) <2009>, Pekka Piispanen <pekka.piispanen@cs.tamk.fi>
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
 *  IndexController - class
 *
 *  @package    controllers
 *  @author     Joel Peltonen
 *  @author     Pekka Piispanen
 *  @copyright  2009 Joel Peltonen & Pekka Piispanen
 *  @license    GPL v2
 *  @version    0.1
 */
class SearchController extends Oibs_Controller_CustomController
{
    public function init()
    {
        parent::init();

        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('get-results', 'html')
            ->initContext();
        
        $this->view->title = 'search-title';
    }

    /**
     * Show main page and list newest and most viewed ideas and problems
     */
    function indexAction()
    {
    
        $urlHelper = $this->_helper->getHelper('url');

        $target = $urlHelper->url(array('controller' => 'search', 
                                        'action' => 'result', 
                                        'language' => $this->view->language), 
                                  'lang_default', true);
                              
        $this->_redirect($target);
        
        /*
        $industries = new Default_Model_Industries();
        $this->view->industries = $industries->fetchAll();
        
        $innovation_types = new Default_Model_InnovationTypes();
        $this->view->innovation_types = $innovation_types->fetchAll();
    /*
        // fetch list of different industry categories and load it to view
        $categoryIndustries = new DatabaseObject_Industries($this->db);
        $industryArray = $categoryIndustries->getCategories($this->db);
        $this->view->industryArray = $industryArray;

		// fetch list of different innovation categories and load it to view
		$categoryInnovations = new DatabaseObject_Innovationtypes($this->db);
		$innovationArray = $categoryInnovations->getCategories($this->db);
		$this->view->innovationArray = $innovationArray;
		
		// request search parameters
		$request 	= $this->getRequest();
		$orderBy	= trim($request->getParam('order'));
		$sort		= trim($request->getParam('sort'));
		$show 		= trim($request->getParam('show'));
		$startFrom	= (int)trim($request->getParam('from'));
		$searchword = trim($request->getParam('search'));
		$industry	= trim($request->getParam('industry'));
		$innovation	= trim($request->getParam('innovation'));
		$tab 		= trim($request->getParam('tab'));
		
		if (strlen($tab) == 0)
		{
			$tab = "problems";
		}
		if($tab == "problems")
		{
			$table = "oibs_problems";
		}
		elseif($tab == "ideas")
		{
			$table = "oibs_ideas";
		}
		elseif($tab == "futureinfo")
		{
			$table = "oibs_futureinfo";
		}
		
		$this->view->searchword = $searchword;
		$this->view->industry 	= $industry;
		$this->view->innovation = $innovation;
		$this->view->from 		= $startFrom;
		$this->view->tab		= $tab;
		
		// table must be set by this point!
		if (($industry != '%') AND ($innovation == '%')) 
		{
			$this->view->parentList = $categoryIndustries->getPosts($this->db, $table, $industry, $innovation, $searchword);
			$this->view->childList = $categoryInnovations->getPosts($this->db, $table, $industry, $innovation, $searchword);
			$this->view->listparent = "industry";
		} else if (($innovation != '%') AND ($industry == '%'))
		{
			$this->view->parentList = $categoryInnovations->getPosts($this->db, $table, $industry, $innovation, $searchword);
			$this->view->childList = $categoryIndustries->getPosts($this->db, $table, $industry, $innovation, $searchword);
			$this->view->listparent = "innovation";
		} else if (($innovation == '%') AND ($industry == '%'))
		{
			$this->view->parentList = $categoryIndustries->getPosts($this->db, $table, $industry, $innovation, $searchword);
			$this->view->childList = $categoryInnovations->getPosts($this->db, $table, $industry, $innovation, $searchword);
			$this->view->listparent = "none";
		} else
		{
			$this->view->parentList = $categoryIndustries->getPosts($this->db, $table, $industry, $innovation, $searchword);
			$this->view->childList = $categoryInnovations->getPosts($this->db, $table, $industry, $innovation, $searchword);
			$this->view->listparent = "both";
		}

		// lets check which table we should query, we don't need all the database objects set up.
		switch ($table)
		{
			case 'oibs_problems': 
				$object = new DatabaseObject_Problem($this->db);
				break;
			case 'oibs_ideas': 
				$object = new DatabaseObject_Idea($this->db);
				break;
			case 'oibs_futureinfo': 
				$object = new DatabaseObject_Future($this->db);
				break;
			default: 
				$object = new DatabaseObject_Problem($this->db);
		}
		
		//Zend_Debug::dump($object, $label=null, $echo=true);
		
		// if searhword is set do a search
		if (strlen($searchword) != 0)
		{
			$objectList = $object->search($this->db, $orderBy, $sort, $show, 
				$startFrom, $searchword, $industry, $innovation);
		}
		// if searchword is not set list results according to industry or innovationtype or both
		else
		{
			$objectList = $object->listItems($this->db, $orderBy, $sort, $show, 
				$startFrom, $industry, $innovation);
		}

		$this->view->objects = $objectList;
	//	Zend_Debug::dump($objectList, $label=null, $echo=true); 
	//	Zend_Debug::dump($table, $label=null, $echo=true);
    
		//break;
		*/
	}

	/**
	*	resultAction
	*
	*	Gets search results.
	*
	*/
	function resultAction()
	{
        // assuming that the CleanQuery plugin has already stripped empty parameters
        // regenerate URI
       if (isset($_GET) && is_array($_GET) && !empty($_GET)) {
            $path = '';
            
            array_walk($_GET, array('SearchController', 'encodeParam'));
            
            foreach ($_GET as $key => $value) {
                if ($key != 'submit' && $key != 'submitsearch')
                    $path .= '/' . $key . '/' . $value;
            }
            
            $uri = $_SERVER['REQUEST_URI'];
            
            $path = substr($uri, 0, strpos($uri, '?')) . $path;
            
            $this->getResponse()->setRedirect($path, $this->_permanent ? 301 : 302);
            $this->getResponse()->sendResponse();
            
            return;
        }
        
		$params = $this->getRequest()->getParams();
		$data = array();

		// Get page number and items per page
		$page = isset($params['page']) ? $params['page'] : 1;
		$count = isset($params['count']) ? $params['count'] : 10;
		
		// Get list order value
		$order = isset($params['order']) ? $params['order'] : 'created';
		
		// disabled for now...
		// Search params
		//$search = isset($params['q']) ? $params['q'] : null;
		
		// quick fix enables the search string to last to the next page as well
		/****************************************************/
		$search_space = new Zend_Session_Namespace('search');
		
		if(isset($params['q'])) {
			$search_space->query = $params['q'];
		}
		
		$search = $search_space->query;	
		/****************************************************/
        
        // Get data and content result count
        $contentModel = new Default_Model_Content();
        
        $data = $contentModel->getSearchResult($search, $page, $count, $order);
        $contentCount = $contentModel->getContentCountBySearch($search);

        $results = array();
        
        // gather other content data and insert to results array
        if(isset($data[0])) {
            $contentHasTagModel = new Default_Model_ContentHasTag();
            $contentRatingsModel = new Default_Model_ContentRatings();
            
            $i = 0;
            foreach ($data as $content) {
                $results[$i] = $content;
                $results[$i]['tags'] = $contentHasTagModel
                                        ->getContentTags($content['id_cnt']);
                                        
                $results[$i]['ratingdata'] = $contentRatingsModel
                                        ->getPercentagesById($content['id_cnt']);
                $i++;
            }
        }
        
        // Calculate total page count
        $pageCount = ceil($contentCount / $count);
        
        // Custom pagination to fix memory error on large amount of data
        $paginator = new Zend_View();
        $paginator->setScriptPath('../application/views/scripts');
        $paginator->pageCount = $pageCount;
        $paginator->currentPage = $page;
        $paginator->pagesInRange = 10;
        
        $this->view->search = $search;
        $this->view->page = $page;
        $this->view->contentPaginator = $paginator;        
        $this->view->contentData = $results;  

	} // end of result
	
	/**
	*    encodeParam
	*
	*    Encodes given value and key to url.
	*/
    public static function encodeParam(&$value, &$key) {
        $value = urlencode($value);
        $key = urlencode($key);
    } // end of encodeParam

    function searchUserByFilter() {

        $params = $this->getRequest()->getParams();
        $pattern = isset($params['pattern']) ? $params['pattern'] : "";

        if($pattern != "") {
            $userModel = new Default_Model_User();
            $searchResults = $userModel->getUserByFilter($pattern);
            $this->getSidebarHelper()->setSearchUserResults($searchResults);
        }

    }

    function searchContentByFilter() {

        $params = $this->getRequest()->getParams();
        $pattern = isset($params['pattern']) ? $params['pattern'] : "";

        if($pattern != "") {
            $contentModel = new Default_Model_Content();
            $searchResults = $contentModel->getContentByFilter($pattern);
            $this->getSidebarHelper()->setSearchContentResults($searchResults);
        }

    }

    function searchGroupByFilter() {

        $params = $this->getRequest()->getParams();
        $pattern = isset($params['pattern']) ? $params['pattern'] : "";

        if($pattern != "") {
            $groupModel = new Default_Model_Groups();
            $searchResults = $groupModel->getGroupByFilter($pattern);
            $this->getSidebarHelper()->setSearchGroupResults($searchResults);
        }

    }

    // delivers 0-5 users which match best with the parameters
    function matchingUsers() {
        $params = $this->getRequest()->getParams();
        $job = isset($params['job']) ? $params['job'] : "";
        $location = isset($params['location']) ? $params['location'] : "";
        $attribute = isset($params['attribute']) ? $params['attribute'] : "";

        $userModel = new Default_Model_User();
        $matchingUsers = $userModel->getMatchingUser($job, $location, $attribute);
        $this->getSidebarHelper()->setMatchingUsers($matchingUsers);

    }

    public function getResults() {
        $params = $this->getRequest()->getParams();

        $pattern = isset($params['pattern']) ? $params['pattern'] : 0;

        $userModel = new Default_Model_User();
        $userResults = $userModel->getUserByFilter($pattern);

        $groupModel = new Default_Model_Groups();
        $groupResults = $groupModel->getGroupByFilter($pattern);

        $contentModel = new Default_Model_Content();
        $contentResults = $contentModel->getContentByFilter($pattern);

        $this->view->userResults = $userResults;
        $this->view->groupResults = $groupResults;
        $this->view->contentResults = $contentResults;
    }

}