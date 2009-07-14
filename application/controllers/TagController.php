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
 *  TagtController - class
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
		
		$this->view->title = 'tag-title';
		
		$this->breadcrumbs->addStep('Tag', $this->getUrl(null, 'tag'));
	} // end of init()
	
	/**
	*	indexAction
	*
	*	Tag index
	*
	*/
	public function indexAction()
	{
		$this->view->title = "index-tag";
		
		$tags = new Models_Tags();
		$tag_list = $tags->getTagCloudData();
		
		$tag_list = $this->changeTagSize($tag_list);
		$this->view->tag_list = $tag_list;	
	} // end of indexAction()

	/**
	*	viewAction
	*
	*	Tag index
	*
	*/	
	public function viewAction()
	{
		$params = $this->getRequest()->getParams();
		
		$tag = new Models_Tags();
		
		$data = array();
		$result = array();
        
		$data = $tag->getTagContentById($params['id']);
        
        $contentTypesModel = new Models_ContentTypes();
        $contentTypes = $contentTypesModel->getAllNamesAndIds();
        
        // match content type with data
        $i = 0;
        foreach ($data as $dat) {   // for each content that has tag
            if ($dat['published_cnt'] == 1) {
                $result[$i] = $dat;
                
                $break = false;
                
                $j = 0;                 // go through content types until hit
                while (!$break) {
                    if ($dat['id_cty_cnt'] == $contentTypes[$j]['id_cty']) {
                        $result[$i]['type'] = $contentTypes[$j]['key_cty'];
                        $break = true;  // break if hit
                    }
                    $j++;
                    if ($j > 100) {     // break if appears to be infinite
                        $break = true;
                    }
                }
                
                $i++;
            }
        }
        
		$tagName = $tag->getTagNameById($params['id']);
        
		$this->view->tags = $result;
        $this->view->tagName = $tagName;
	} // end of viewAction
	
	/**
	*	calculateTagSize
	*
	*	Tag index
	*
	*/	
	public function changeTagSize($tag_list = null)
	{
		$maxTagCount = null;
		$minTagCount = null;
		
		$minFontSize = 100;
		$maxFontSize = 250;
		
		$result = array();
		
		foreach ($tag_list as $tag)
		{
			$cnt = $tag['count']['tag_count'];
		
			if($maxTagCount == null || $cnt > $maxTagCount)
				$maxTagCount = $cnt;
				
			if($minTagCount == null || $cnt < $minTagCount)
				$minTagCount = $cnt;
		}
		
		$spread = $maxTagCount - $minTagCount;
		if($spread == 0)
			$spread = 1;
		
		$step = ($maxFontSize - $minFontSize) / $spread;
		
		foreach ($tag_list as $tag)
		{			
			$tag['count']['tag_size'] = $minFontSize + (($tag['count']['tag_count'] - $minTagCount) * $step);

			$result[] = $tag;			
		}
		
		return $result;
	} // end of calculateTagSize
}
?>