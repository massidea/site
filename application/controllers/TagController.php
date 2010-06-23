<?php
/**
 *  TagController -> Viewing tags
 *
* 	Copyright (c) <2009>, Markus Riihelä
* 	Copyright (c) <2009>, Mikko Sallinen
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
 *  TagController - class
 *
 *  @package 	controllers
 *  @author 		Markus Riihelä & Mikko Sallinen
 *  @copyright 	2009 Markus Riihelä & Mikko Sallinen
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class TagController extends Oibs_Controller_CustomController
{
	/**
	*	init
	*	
	*	Initialization of tag controller
	*
	*/
	public function init()
	{
		parent::init();
		
		$this->view->title = 'tag-view-title';
		
		//$this->breadcrumbs->addStep('Tag', $this->getUrl(null, 'tag'));
	} // end of init()
	
	/**
	*	indexAction
	*
	*	Tag index
	*
	*/
	public function indexAction()
	{
        // Get cache from registry
        $cache = Zend_Registry::get('cache');
        $cacheFile = '';
        
		$this->view->title = 'tag-view-title';
        
        // default values and GET parameters
        $limit = '0';
        $order = 'name';
        $direction = 'desc';
        $where = 'All';
        $ctype = 'all';
        $list_type = 'cloud';
        
        $params = $this->getRequest()->getParams();
        
        if(isset($params['limit'])) {
            $limit = $params['limit'];
            $cacheFile .= '?limit=' . $limit;
        }
        
        if(isset($params['order'])) {
            $order = $params['order'];
            $cacheFile .= '?order=' . $order;
        }
        
        if(isset($params['direction'])) {
            $direction = $params['direction'];
            $cacheFile .= '?direction=' . $direction;
        }
        
        if(isset($params['where'])) {
            $where = $params['where'];
            $cacheFile .= '?where=' . $where;
        }
        
        if(isset($params['ctype'])) {
            $ctype = $params['ctype'];
            $cacheFile .= '?ctype=' . $ctype;
        }
        
        if(isset($params['list_type'])) {
            $list_type = $params['list_type'];
            $cacheFile .= '?list_type=' . $list_type;
        }
        
        /*
        foreach ($params as $key => $val){
            switch ($key) {
                case 'limit':
                    $limit = $val;
                    break;
                case 'order':
                    $order = $val;
                    break;
                case 'direction':
                    $direction = $val;
                    break;
                case 'where':
                    $where = $val;
                    break;
                case 'ctype':
                    $ctype = $val;
                    break;
                case 'list_type':
                    $list_type = $val;
                    break;
                default:
                    break;
            }
        }
        */
        
        $cacheFile = 'Tags_' . md5($cacheFile);
        
        // Load tags from cache
        if(!$result = $cache->load($cacheFile)) {
            //Retrieving tags
            $tags = new Default_Model_Tags();
            $tagList = $tags->getTagCloudData($limit, $order, $direction, $where, $ctype);
        
            //Changing tag size
            //$this->changeTagSize($tagList);
            $tagList = $this->_helper->tagsizes->tagCalc($tagList);
            
            // Action helper for define is tag running number divisible by two
            $tagList = $this->_helper->tagsizes->isTagDivisibleByTwo($tagList);

            $cache->save($tagList, $cacheFile);
        } else {
            $tagList = $result;
        }
            
        // inject things to view
		$this->view->tag_list = $tagList;
		$this->view->order = $order;
        $this->view->direction = $direction;
        $this->view->ctype = $ctype;
        $this->view->where = $where;
        $this->view->list_type = $list_type;

	} // end of indexAction()

	
	/**
	*	viewAction
	*
	*	Tag index
	*
	*/	
	public function viewAction()
	{
        $this->view->title = 'tag-view-title';
        
		//$params = $this->getRequest()->getParams();
        $tagId = $this->getRequest()->getParam('id');
				
		//$data = array();
		$result = array();
        
		$tag = new Default_Model_Tags();
        
		$contentList = $tag->getTagContentById($tagId);
		$tagName = $tag->getTagNameById($tagId);
        
        /* What is this
        $contentTypesModel = new Default_Model_ContentTypes();
        $contentTypes = $contentTypesModel->getAllNamesAndIds();
        
        // match content type with data
        $i = 0;
        foreach ($data as $dat) {   // for each content that has tag
            if ($dat['published_cnt'] == 1) {
                $result[$i] = $dat;
                
                $break = false;
                
                $j = 0;                 // go through content types until hit
                while (!$break) {
                    if (isset($result[$i]) && isset($contentTypes[$j])){
                        if ($dat['id_cty_cnt'] == $contentTypes[$j]['id_cty']) {
                            $result[$i]['type'] = $contentTypes[$j]['key_cty'];
                            $break = true;  // break if hit
                        }
                    }
                    
                    $j++;
                    
                    if ($j > 100) {     // break if appears to be infinite...
                        $break = true;
                    }
                }
                
                $i++;
            }
        }
        */
        
		$this->view->content = $contentList;
        $this->view->tagName = $tagName;
	} // end of viewAction
	
	/**
	*	calculateTagSize
	*
	*	Tag index
	*
	*/
    /*	
	public function changeTagSize(&$tag_list = null)
	{
		$minFontSize = 80;
		$maxFontSize = 400;		
		
		$maxTagCount = null;
		$minTagCount = null;
        
		foreach ($tag_list as $tag) {
			$cnt = $tag['count'];
		
			if($maxTagCount == null || $cnt > $maxTagCount) {
				$maxTagCount = $cnt;
            }
				
			if($minTagCount == null || $cnt < $minTagCount) {
				$minTagCount = $cnt;
            }
		}
		
		$spread = $maxTagCount - $minTagCount;
		if($spread == 0) {
			$spread = 1;
        }
		
		$step = ($maxFontSize - $minFontSize) / $spread;
        
		foreach ($tag_list as $k => $tag) {			
			$tag_list[$k]['tag_size'] = round($minFontSize + (($tag['count'] - $minTagCount) * $step));		
		}
	} // end of calculateTagSize
    */
}