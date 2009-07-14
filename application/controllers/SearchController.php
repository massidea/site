<?php
/**
 *  SearchController -> main pages
 *
* 	Copyright (c) <2009>, Joel Peltonen <joel.peltonen@cs.tamk.fi>
* 	Copyright (c) <2009>, Pekka Piispanen <pekka.piispanen@cs.tamk.fi>
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
 *  @package 	controllers
 *  @author 		Joel Peltonen & Pekka Piispanen
 *  @copyright 	2009 Joel Peltonen & Pekka Piispanen
 *  @license 	GPL v2
 *  @version 	0.1
 */
class SearchController extends Oibs_Controller_CustomController
{
	public function init()
	{
		parent::init();
		
		$this->view->title = 'search-title';
	}

	/**
	 *	Show mainpage and list newest and most viewed ideas and problems
	 */
    function indexAction()
    {
		$industries = new Models_Industries();
		$this->view->industries = $industries->fetchAll();
		
		$innovation_types = new Models_InnovationTypes();
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

	/* What is this, why is this here?? */
	function testiAction()
	{
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
		$table 		= trim($request->getParam('table'));
		
		if (strlen($table) == 0) $table = 'oibs_problems';
		//$table = 'oibs_problems';
		
		$this->view->searchword = $searchword;
		$this->view->industry 	= $industry;
		$this->view->innovation = $innovation;
		$this->view->from 		= $startFrom;
		
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

		// lets check which table we should query, we don't need all the database ubjects set up.
		switch ($table)
		{
			case 'oibs_problems': 
				$object = new DatabaseObject_Problem($this->db);
				break;
			case 'oibs_ideas': 
				$object = new DatabaseObject_Idea($this->db);
				break;
			case 'oibs_future': 
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
		
		// New content model
		$content = new Models_Content();

		// Get page nummber and items per page
		$page = isset($params['page']) ? $params['page'] : 1;
		$count = isset($params['count']) ? $params['count'] : 10;
		
		// Get list oreder value
		$order = isset($params['order']) ? $params['order'] : 'created';
		
		// Search params
		$search = $params['q'];
		
		// Get data by search
		$data = $content->getByName($search, $page, $count, $order);

		$this->view->search = $search;
		$this->view->page = $page;
		
		if (!empty($data))
		{
		
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
		
			// $this->view->contentPaginator = $data['Content']['Data'];
		} // end if
		/*
		}
		catch(Zend_Exception $e)
		{
			echo '<pre>';
			print_r($e);
			echo '</pre>';
		}
		*/
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
 
}
