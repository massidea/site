<?php
/**
 *  Content -> Content database model for content table.
 *
* 	Copyright (c) <2009>, Markus Riihel�
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
 *  Content - class
 *
 *  @package 	models
 *  @author 		Markus Riihel� & Mikko Sallinen
 *  @copyright 	2009 Markus Riihel� & Mikko Sallinen
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class Models_Content extends Zend_Db_Table_Abstract
{
	// Table name
    protected $_name = 'contents_cnt';
    
	// Table primary key
	protected $_primary = 'id_cnt';
	
	// Dependet tables
	protected $_dependentTables = array('Models_ContentRatings', 'Models_Comments', 
										'Models_Links', // 'Models_ContentTypes', 
										'Models_ContentHasTag', 'Models_ContentHasIndustries',
										'Models_ContentHasInnovationTypes', 'Models_ContentHasContent', 
										'Models_ContentHasUser', 'Models_ContentHasGroup',
										'Models_Files', 'Models_ContentPublishTimes');
	
	// Table reference map
	protected $_referenceMap    = array(
        'ContentType' => array(
            'columns'           => array('id_cty_cnt'),
            'refTableClass'     => 'Models_ContentTypes',
            'refColumns'        => array('id_cty')
        ),
		/*
		'ContentPublishTimes' => array(
            'columns'           => array('id_cnt'),
            'refTableClass'     => 'Models_PublishTimes',
            'refColumns'        => array('id_cnt_pbt')
        ),
		'ContentRatings' => array(
            'columns'           => array('id_cnt'),
            'refTableClass'     => 'Models_PublishTimes',
            'refColumns'        => array('id_cnt_crt')
        ),
		*/
	);
	
    protected $_id = 0;
    protected $_data = null;
    
    /**
    *   __construct
    *
    *   Content constructor.
    *
    *   @param integer $id Content id value.
    */
    public function __construct($id = -1)
    {
        parent::__construct();
        
        $this->_id = $id;
        
        if ($id != -1){
            $this->_data = $this->find((int)$id)->current();
        } // end if
    }
    
	/**
	*	listRecent
	*
	*	List recent content by content type.
	*
	*	@param string $cty
	*	@return array
	*/
	public function listRecent($cty = 'all', $page = 1, $count = 10, $order = 'created')
	{
		//
		//	THIS FUNCTION NEEDS FIXING ND CLEANING
		//
	
		//$where = 1;
		//$select = $this->select()->order('created_cnt DESC')
		
								 /*->limitPage($page, $count)*/;
		
		switch ($order) {
			case 'author': 
				$order = 'usr.login_name_usr';
				break;
			case 'header':
				$order = 'cnt.title_cnt';
				break;
			case 'views':
				$order = 'cnt.views_cnt';
				break;
			default: 
				$order = 'cnt.created_cnt DESC';
		}
		
		// FIX THIS TO ITS OWN FUNCTION!!!!
		$select = $this->_db->select()->from(array('cnt' => 'contents_cnt'),
											array('*'))
									 ->join(array('cty' => 'content_types_cty'),
						                    'cnt.id_cty_cnt = cty.id_cty',
						                    array('*'))
									->join(array('chu' => 'cnt_has_usr'), 'chu.id_cnt = cnt.id_cnt', array('*'))
									->join(array('usr' => 'users_usr'),
						                    'chu.id_usr = usr.id_usr',
						                    array('*'))
									->where('cty.key_cty = ?', $cty)
									->where('published_cnt = 1')
									 ->order($order);
		
		// Content data
		//$content_entries = array();
		$data = $this->_db->fetchAll($select);//array();
		/*
		// If content type not all
		if($cty != 'all')
		{
			$ct = new Models_ContentTypes();
			
			// Select content type by specified
			$slct = $ct->select()->where('key_cty = ?', $cty);
			
			// Fetch content types
			$content_types = $ct->fetchAll($slct);
			$temp = $content_types->toArray();
			
			// Check if content type is not empty
			if(!empty($temp))
			{
				// Find content by content type
				$content_entries = $content_types->current()->findDependentRowset($this, 'ContentType', $select);
			} // end if
		} // end if
		else
		{
			// Find all content
			$content_entries = $this->fetchAll($select);
		} // end if
		*/
		/*
		// Go through all content entries to get owners 
		foreach($content_entries as $entry)
		{
			$data['Content']['Data'][$entry['id_cnt']] = $entry->toArray();
			// Find content users
			$data['Content']['Data'][$entry['id_cnt']]['Owner'] = $entry->findManyToManyRowset('Models_User', 'Models_ContentHasUser', 'UserContent', 'UserUser')->toArray();
		} // end foreach
		*/
		
		return $data;
	}
	
	/**
	*	getById
	*
	*	Get content by id.
	*
	*	@param ineteger $id
	*	@return array
	*/
	public function getById($id = 0)
	{
		// Array for content data
		$data = array();
		
		// Find content row by id
		$rowset = $this->find((int)$id)->current();
		
		// If content was found
		if(count($rowset) == 1)
		{
			// Content data
			$content_data = $rowset->toArray();
			
			// Select content ratings
			$select_ratings = $this->select()->from('content_ratings_crt', array('SUM(rating_crt) AS rate_crt'));
			$ratings = $rowset->findDependentRowset('Models_ContentRatings', 'RatingsContent', $select_ratings)->toArray();
			
			// Find content owner
			$content_owner = $rowset->findManyToManyRowset('Models_User', 'Models_ContentHasUser');
			
			// Find content comments
			$select_comment = $this->select()->order('created_cmt ASC');
			$comments = $rowset->findDependentRowset('Models_Comments', 'CommentContent', $select_comment);
			
			// Find content keywords
			$tags = $rowset->findManyToManyRowset('Models_Tags', 'Models_ContentHasTag')->toArray();
			
			// Find content links
			$links = $rowset->findDependentRowset('Models_Links')->toArray();
			
			// Find related content
			$related_content = $rowset->findManyToManyRowset('Models_Content', 'Models_ContentHasContent', 'ParentContent', 'ChildContent')->toArray();
			
			// Array for comment owners
			$comment_owners = array();
			
			// Go through all comments 
			foreach($comments as $cmt)
			{
				// Find comment owner
				$usr = $cmt->findDependentRowset('Models_User', 'CommentUser')->toArray();
				
				// If owner found
				if(!empty($usr))
				{
					// Specify comment owner
					$comment_owners[$usr[0]['id_usr']] = $usr[0];
				} // end if
			} // end foreach

			// Gather content data
			$data['Content']['Data'] 	 = $content_data;
			$data['Content']['Poster'] 	 = $content_owner;
			$data['Content']['Tags']	 = $tags;
			$data['Content']['Links']	 = $links;
			$data['Content']['Related']	 = $related_content;
			$data['Ratings'] 			 = $ratings;
			$data['Comments']['Data'] 	 = $comments;
			$data['Comments']['Posters'] = $comment_owners;
		}

		return $data;
	} // end of getById
    
    /**
    *    getContentRow
    *
    *    Get content data by id
    *
    *    @param integer $id content id
    *    @return array
    */
    public function getContentRow($id = -1)
    {
        if($id == -1) {
            $id = $this->_id;
        } // end if
        
        return $this->find((int)$id)->current()->toArray();
    } // end of getContentRow
    
	public function getByAuthor($author_id = 0)
	{
		//$contentUser = new Models_ContentHasUser();
		//$select = $this->select()->where('')
		//$this->findDependentRowset('Models_ContentHasUser', );
	/*
		$select = $this->_db->select()
			->from('contents_cnt', array('*'))
			->where('id_usr_cnt = ?', $author_id);
		
		$stmt = $this->_db->query($select);
		
		$result = $stmt->fetchAll();

		*/
		return $result;	
	}
	
	/**
	*	getByName
	*
	*	Gets content by name from database. This is used in search function.
	*
	*	@param string $searchWord
	*/
	public function getByName($searchword = null, $page = 1, $count = 10, $order = 'created')
	{
		//
		//	THIS FUNCTION NEEDS FIXING ND CLEANING
		//
		
		$data = array();
		$content_entries = array();
		
		switch ($order) {
			case 'author': 
				$order = 'usr.login_name_usr DESC';
				break;
			case 'header':
				$order = 'cnt.title_cnt DESC';
				break;
			case 'views':
				$order = 'cnt.views_cnt DESC';
				break;
			default: 
				$order = 'cnt.created_cnt DESC';
		}

		if ($searchword != null)
		{
			try{
			// Select content where title contains search word
			// FIX THIS TO ITS OWN FUNCTION!!!!
			$select = $this->_db->select()->from(array('cnt' => 'contents_cnt'),
											array('*'))
									 ->join(array('cty' => 'content_types_cty'),
						                    'cnt.id_cty_cnt = cty.id_cty',
						                    array('*'))
									->join(array('chu' => 'cnt_has_usr'), 'chu.id_cnt = cnt.id_cnt', array('*'))
									->join(array('usr' => 'users_usr'),
						                    'chu.id_usr = usr.id_usr',
						                    array('*'))
									->join(array('cht' => 'cnt_has_tag'),
						                    'cht.id_cnt = cnt.id_cnt',
						                    array('*'))		
									->join(array('tag' => 'tags_tag'),
						                    'cht.id_tag = tag.id_tag',
						                    array('*'))
									 ->where('title_cnt LIKE ?', '%'.$searchword.'%')
									 ->orWhere('lead_cnt LIKE ?', '%'.$searchword.'%')
									 ->orWhere('body_cnt LIKE ?', '%'.$searchword.'%')
									 ->orWhere('name_tag LIKE ?', '%'.$searchword.'%')
									 ->order($order)
									//->where('chu.id_cnt = cnt.id_cnt')
									 /*->limitPage($page, $count)*/;
			//$content_entries = $this->fetchAll($select);
			//$stmt = $this->_db->query($select);
			$content_entries = $this->_db->fetchAll($select);
			
			/*
			foreach ($content_entries as $entry)
			{
				$data['Content']['Data'][$entry['id_cnt']] = $entry->toArray();
				// Find content users
				$data['Content']['Data'][$entry['id_cnt']]['Owner'] = $entry->findManyToManyRowset('Models_User', 'Models_ContentHasUser', 'UserContent', 'UserUser')->toArray();
				$data['Content']['Data'][$entry['id_cnt']]['Type'] = $entry->findDependentRowset('Models_ContentTypes', 'ContentType')->toArray();
			} // end foreach
			*/
			
			/*	
			echo '<pre>';
			print_r($content_entries);
			echo '</pre>';
		
			*/
			
			}
			catch(Zend_Exception $e)
			{
				echo '<pre>';
				print_r($e);
				echo '</pre>';
			}
		} // end if
		
		return $content_entries;
	} // end of getByName
	
	/**
	*	addContent
	*
	*	Add content.
	*
	*	@param array $data
	*/
	public function addContent($data)
	{
        $return = true;
        
		// Create a new row
		$content = $this->createRow();
		//Zend_Debug::dump($content, $label=null, $echo=true);
		// Set data to row
		$content->id_cty_cnt = $data['content_type'];
		$content->title_cnt = strip_tags($data['content_header']);
		$content->lead_cnt = strip_tags($data['content_textlead']);
		$content->body_cnt = strip_tags($data['content_text']);
		$content->published_cnt = 0;
		
		$content->created_cnt = new Zend_Db_Expr('NOW()');
		$content->modified_cnt = new Zend_Db_Expr('NOW()');
     
        //Zend_Debug::dump($content, $label=null, $echo=true); die();
     
        /*$query = "INSERT INTO contents_cnt (id_cty_cnt, id_ind_cnt, title_cnt, lead_cnt, body_cnt, views_cnt, published_cnt, created_cnt, modified_cnt) ";
        $query .= "VALUES (".$data['content_type'].", ".$data['content_industry_id'].", '".strip_tags($data['content_header'])."', '".strip_tags($data['content_textlead'])."', '";
        $query .= strip_tags($data['content_text'])."', 0, 0, ".new Zend_Db_Expr('NOW()').", ".new Zend_Db_Expr('NOW()').")";*/
        //Zend_Debug::dump($query, $label=null, $echo=true); die();
        /*mysql_connect("localhost", "root", "lollero");
        mysql_query($query); 
        echo mysql_error();
        mysql_close();
        die();*/

		if(!$content->save())
        {
            $return = false;
        }
        
		$contentTypes = new Models_ContentTypes();
		$content_type = $contentTypes->getTypeById($data['content_type']);
		
		if($content_type == "idea")
		{
			$contentHasContent = new Models_ContentHasContent();
			$contentHasContent->addContentToContent(
                                            $data['content_relatesto_id'], 
                                            $content->id_cnt
                                            );
		}
        
        
		// Add user to content
		$contentHasUser = new Models_ContentHasUser();
		$contentHasUser->addUserToContent($content->id_cnt, $data['User']['id_usr'], 1);
		
		// Check if user has given keywords
		if(!empty($data['content_keywords']))
		{
			// Go through all given keywords
            
			foreach($data['content_keywords'] as $tag)
			{
				$tagRow = new Models_Tags();
				$tag = strip_tags($tag);
				
				// Check if given keyword does not exists in database
				if($tagRow->tagExists($tag))
				{
					// Create new keyword
					$tag = $tagRow->createTag($tag);
				} // end if
				else
				{
					// Get keyword
					$tag = $tagRow->getTag($tag);
				} // end else
				
				// echo '<pre>';echo $tag->id_tag.'    '.$content->id_cnt;echo '</pre>';
				//die();
				
				// Add keywords to content
				$contentHasTag = new Models_ContentHasTag();
				$contentHasTag->addTagToContent($tag->id_tag, $content->id_cnt);
			} // end foreach 
		} // end if
		
		// Add industry to content
		$contentHasIndustry = new Models_ContentHasIndustries();
		
		if($data['content_class'] != 0)
		{
			$id_ind = $data['content_class'];
		}
		elseif($data['content_group'] != 0)
		{
			$id_ind = $data['content_group'];
		}
		else
		{
			$id_ind = $data['content_division'];
		}
		
		$contentHasIndustry->addIndustryToContent($content->id_cnt, $id_ind);
		
		// Add innovation type to content
		$contentHasInnovationType = new Models_ContentHasInnovationTypes();
		$contentHasInnovationType->addInnovationTypeToContent($content->id_cnt, $data['innovation_type']);
        
        return $return;
	} // end of addContent
   
    /**
	*	editContent
	*
	*	Edit content.
	*
	*	@param array $data
	*/
	public function editContent($data)
	{
        $return = true;
     
        // Create array for content
		$content = array();
         
		$content['title_cnt'] = strip_tags($data['content_header']);
		$content['lead_cnt'] = strip_tags($data['content_textlead']);
		$content['body_cnt'] = strip_tags($data['content_text']);
		
		$content['modified_cnt'] = date("Y-m-d H:i:s", time());
     
        $where = $this->getAdapter()->quoteInto('id_cnt = ?', $data['content_id']);
     
        if(!$this->update($content, $where))
        {
            $return = false;
        }
        
        if(!empty($data['content_keywords']))
		{
            $tagRow = New Models_Tags();
            $cntHasTag = New Models_ContentHasTag();
            $tags = $cntHasTag->getContentTags($data['content_id']);
            
            $tags_to_add = array();
			$tags_to_delete = array();
            
			foreach($data['content_keywords'] as $keyword)
			{
                $found = false;
                foreach($tags as $tag)
                {
                    if($keyword == $tag['name_tag'])
                    {
                        $found = true;
                    }
                }
                if(!$found)
                {
                    $tags_to_add[] = $keyword;
                }
            }
            
            foreach($tags as $tag)
			{
                $found = false;
                foreach($data['content_keywords'] as $keyword)
                {
                    if($tag['name_tag'] == $keyword)
                    {
                        $found = true;
                    }
                }
                if(!$found)
                {
                    $tags_to_delete[] = $tag['id_tag'];
                }
            }
            
            if(!empty($tags_to_add))
            {
                foreach($tags_to_add as $tag_to_add)
                {
                    $tag_to_add = $tagRow->createTag($tag_to_add);
                    $cntHasTag->addTagToContent($tag_to_add->id_tag, $data['content_id']);
                }
            }
            
            if(!empty($tags_to_delete))
            {
                foreach($tags_to_delete as $tag_to_delete)
                {
                    $cntHasTag->deleteTagFromContent($tag_to_delete, $data['content_id']);
                    if(!$cntHasTag->checkIfOtherContentHasTag($tag_to_delete, $data['content_id']))
                    {
                        $tagRow->removeTag($tag_to_delete);
                    }
                }
            }
        }
        
        // Update industry to content
        $contentHasIndustry = new Models_ContentHasIndustries();
        $current_industry = $contentHasIndustry->getIndustryIdOfContent($data['content_id']);
        if($current_industry != $data['content_industry_id'])
        {
            if(!$contentHasIndustry->updateIndustryToContent($data['content_industry_id'], $data['content_id']))
            {
                $return = false;
            }
        }
        
        // Update innovation type to content
		$contentHasInnovationType = new Models_ContentHasInnovationTypes();
        $current_innovation_type = $contentHasInnovationType->getInnovationTypeIdOfContent($data['content_id']);
        if($current_innovation_type != $data['innovation_type'])
        {
            if(!$contentHasInnovationType->updateInnovationTypeToContent($data['innovation_type'], $data['content_id']))
            {
                $return = false;
            }
        }
        
        return $return;
    }
   
   /** 
    *   publishContent
    *   Publishes specified content
    *   
    *   @param int id_cnt The id of content to be published
    *   @return bool $return
    *   @author Pekka Piispanen
    */
    public function publishContent($id_cnt = 0)
    {
        $return = false;
    
        $data = array('published_cnt' => 1);			
		$where = $this->getAdapter()->quoteInto('id_cnt = ?', $id_cnt);
		if($this->update($data, $where))
        {
            $return = true;
        }
        
        return $return;
    }
   
    /** 
    *   removeContent
    *   Removes specified content from the database
    *   
    *   @param int id_cnt The id of content to be removed
    *   @return bool $return
    *   @author Pekka Piispanen
    */
    public function removeContent($id_cnt = 0)
    {
        $return = false;
        
        $content = new Models_Content();
        
        $where = $this->getAdapter()->quoteInto('id_cnt = ?', $id_cnt);
        if($this->delete($where))
        {
            $return = true;
        }
        
        return $return;
    } // end of removeContent
	
	public function checkIfContentExists($id_cnt = 0)
	{
		$return = false;
		
		if($id_cnt != 0)
		{
			$select = $this->select()
					->from($this, array('*'))
					->where("`id_cnt` = $id_cnt");

			$result = $this->fetchAll($select)->toArray();
			
			if(count($result) != 0)
			{
				$return = true;
			}
		}
		
		return $return;
	}
    
    /**
	*	getDataForView
	*
	*	Get content by id.
	*
	*	@param ineteger $id
	*	@return array
	*/
	public function getDataForView($id = 0)
	{
		// Array for content data
		$data = array();
		
		// Find content row by id
		//$rowset = $this->find((int)$id)->current();
        $select = $this->_db->select()
                            ->from(array('contents_cnt' => 'contents_cnt'), array('*'))
                            ->where('id_cnt = ?', $id)
        ;
        $result = $this->_db->fetchAll($select);
        

		// If content was found
		if(count($result) == 1)
		{
            $data['Content']['Data'] = $result[0];
			
			// Find Ratings
			//$select_ratings = $this->select()->from('content_ratings_crt', array('SUM(rating_crt) AS rate_crt'));
			//$ratings = $rowset->findDependentRowset('Models_ContentRatings', 'RatingsContent', $select_ratings)->toArray();
			$ratings = new Models_ContentRatings();
            $rating = $ratings->getById($id);
            //echo"<pre>"; print_r($rating); echo"</pre>"; die;
            
			// Find content owners
			//$content_owner = $rowset->findManyToManyRowset('Models_User', 'Models_ContentHasUser');
			$cntHasUser = new Models_ContentHasUser();
            $owners = $cntHasUser->getContentOwners($id);
            //echo "<pre>"; print_r($owners); die;
            
            // Find owners
            $userModel = new Models_User();
            $i = 0;
            foreach ($owners as $owner) {
                $data['Content']['Data']['Owners'][$i] = $userModel->getSimpleUserDataById($owner);
                $i++;
            }

			// Find content comments
			//$select_comment = $this->select()->order('created_cmt ASC');
			//$comments = $rowset->findDependentRowset('Models_Comments', 'CommentContent', $select_comment);
            $commentModel = new Models_Comments();
            $comments = $commentModel->getAllByContentId($id);  
            
            /*  comment owner username is fetched in the previous query, no need for this anymore
                // Array for comment owners
			$comment_owners = array();
			
			// Go through all comments 
			foreach($comments as $cmt)
			{
				// Find comment owner
				$usr = $cmt->findDependentRowset('Models_User', 'CommentUser')->toArray();
				
				// If owner found
				if(!empty($usr))
				{
					// Specify comment owner
					$comment_owners[$usr[0]['id_usr']] = $usr[0];
				} // end if
			} // end foreach
            */
            
			// Find content keywords
			//$tags = $rowset->findManyToManyRowset('Models_Tags', 'Models_ContentHasTag')->toArray();
            $cntHasTag = new Models_ContentHasTag();
            $tags = $cntHasTag->getContentTags($id);

			// Find content links - needs updating to this version
			$links = array(); //$rowset->findDependentRowset('Models_Links')->toArray();
			
			// Find related content
			//$$related_content = $rowset->findManyToManyRowset('Models_Content', 'Models_ContentHasContent', 'ParentContent', 'ChildContent')->toArray();
            $contentHasContent = new Models_ContentHasContent();
            $familyTree = $contentHasContent->getContentFamilyTree($id);
            
           // echo"<pre>"; print_r($tagArray); echo"</pre>"; die;

			// Gather and format content data a bit
            $data['Content']['Data']['rating'] 	= $rating;
			//$data['Content']['Data']['owner']	= $owner;
			$data['Content']['Tags']	        = $tags;
			$data['Content']['Links']	        = $links;
			$data['Content']['FamilyTree']      = $familyTree;
			$data['Comments']['Data'] 	        = $comments;
            //echo"<pre>"; print_r($comments); echo"</pre>"; die;
			//$data['Comments']['Posters']        = $comment_owners;
		}
		//echo"<pre>"; print_r($data); echo"</pre>"; die;
		return $data;
	} // end of getById 
    
    
    public function getDataAsSimpleArray($id = -1)
    {
        if($id == -1) {
            return false;
        }
        $select = $this->_db->select()
                            ->from(array('contents_cnt' => 'contents_cnt'), array('*'))
                            ->where('id_cnt = ?', $id)
                            //->where('published_cnt = ?', 1)   Publishing not implemented yet
        ;

        $result = $this->_db->fetchAll($select);
        return $result[0];  // this is a workaround to get the array to appear nicer to modify by hand
    }
    
} // end of class
?>