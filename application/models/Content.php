<?php
/**
 *  Content -> Content database model for content table.
 *
 *  Copyright (c) <2009>, Markus Riihel�
 *  Copyright (c) <2009>, Mikko Sallinen
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
 *  @package    models
 *  @author     Markus Riihel� & Mikko Sallinen
 *  @copyright  2009 Markus Riihel� & Mikko Sallinen
 *  @license    GPL v2
 *  @version    1.0
 */
class Default_Model_Content extends Zend_Db_Table_Abstract
{
	// Table name
	protected $_name = 'contents_cnt';

	// Table primary key
	protected $_primary = 'id_cnt';

	// Dependet tables
	protected $_dependentTables = array('Default_Model_ContentRatings', 'Default_Model_Comments',
                                        'Default_Model_Links', // 'Default_Model_ContentTypes', 
                                        'Default_Model_ContentHasTag', 'Default_Model_ContentHasIndustries',
                                        'Default_Model_ContentHasInnovationTypes', 'Default_Model_ContentHasContent', 
                                        'Default_Model_ContentHasUser', 'Default_Model_ContentHasGroup',
                                        'Default_Model_Files', 'Default_Model_ContentPublishTimes',
                                        'Default_Model_UserHasFavourites');

	// Table reference map
	protected $_referenceMap    = array(
        'ContentType' => array(
            'columns'           => array('id_cty_cnt'),
            'refTableClass'     => 'Default_Model_ContentTypes',
            'refColumns'        => array('id_cty')
	),
	/*
	 'ContentPublishTimes' => array(
	 'columns'           => array('id_cnt'),
	 'refTableClass'     => 'Default_Model_PublishTimes',
	 'refColumns'        => array('id_cnt_pbt')
	 ),
	 'ContentRatings' => array(
	 'columns'           => array('id_cnt'),
	 'refTableClass'     => 'Default_Model_PublishTimes',
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
	 *    listRecent
	 *
	 *    List recent content by content type.
	 *
	 *    @param string $cty
	 *    @return array
	 */
	public function listRecent($cty = 'all', $page = 1, $count = -1, $order = 'created', $lang = 'en', $ind = 0)
	{
		switch ($order) {
			case 'author':
				$order = 'login_name_usr';
				break;
			case 'header':
				$order = 'title_cnt';
				break;
		 	case 'views':
				$order = 'viewCount ASC';
		 		break;
			default:
				$order = 'created_cnt DESC';
		}

		$select = $this->select()->from($this, "id_cnt")
								 ->where('published_cnt = 1')
								 ->order($order);
								 
		if ($cty != "all") {
			$select->join('content_types_cty', 'content_types_cty.id_cty = contents_cnt.id_cty_cnt',array())
				   ->where('content_types_cty.key_cty = ?', $cty);
		}
		if ($count > 0){
			$select->limitPage($page, $count);
		} else {
			$select->limit($page);
		}

		// Content data
		//$data = $this->_db->fetchAll($select);
		$ids = $this->fetchAll($select);
		$data = $this->getContentRows($ids->toArray(), 'id_cnt', true);
		return $data;
	}
	
	/* getcontentRows
	 * 
	 * Function to get data for content_row partial from given id parameters
	 * 
	 *  @param ids		array	array of arrays with content_id in it
	 *  @param id_cnt	string	what is the key of id_cnt in the ids array
	 *  @param sort		bool	sorts the data according to given id array or not
	 *  @return 		array	array of all data needed for content_row partial
	 */
	public function getContentRows($ids, $id_cnt = 'id_cnt', $sort = false) {
		if (empty($ids)) {
			return array();
		}
		$select = $this->_db->select()->from("contents_cnt", array(	"id_cnt",
																	"title_cnt",
																	"lead_cnt",
																	"language_cnt",
																	"created_cnt"))
								->joinLeft(	"cnt_has_usr", 
											"contents_cnt.id_cnt = cnt_has_usr.id_cnt",
											array())
								->join(	"users_usr", 
											"users_usr.id_usr = cnt_has_usr.id_usr",
											array("login_name_usr", "id_usr"))
								->joinLeft( "content_types_cty",
											"content_types_cty.id_cty = contents_cnt.id_cty_cnt",
											array("id_cty", "key_cty"))
								->joinLeft(	array("chu" => "cnt_has_usr"),
											"cnt_has_usr.id_usr = chu.id_usr",
											array("count" => "count(*)"))
								->group('contents_cnt.id_cnt')
								->where('contents_cnt.id_cnt IN (?)', $ids)
								;
		$data = $this->_db->fetchAll($select);

		if ($sort) {
			$idList = array();
			foreach ($ids as $id) {
				$idList[] = $id[$id_cnt];
			}
			$idList = array_flip($idList);
			$sortedData = array();
			foreach ($data as $row) {
				if(isset($idList[$row['id_cnt']])) $sortedData[$idList[$row['id_cnt']]] = $row;
			}
			ksort($sortedData);
			return $sortedData;
		}
		return $data;
	}

	/**
	 *   getContentCountByContentType
	 *
	 *   Get total content count by content type.
	 *
	 *   @param string $cty Content type
	 *   @return array
	 */
	public function getContentCountByContentType($cty, $lng)
	{
		$select = $this->_db->select()->from(array('cty' => 'content_types_cty'),
		array())
		->join(array('cnt' => 'contents_cnt'),
                                            'cnt.id_cty_cnt = cty.id_cty',
		array('contentCount' => 'COUNT(cnt.id_cnt)'))
		->where('cty.key_cty = ?', $cty)
		->where('cnt.published_cnt = 1');
		//->where('cnt.language_cnt = ?', $lng);

		$data = $this->_db->fetchAll($select);

		return $data[0]['contentCount'];
	}

	/**
	 *   getById
	 *
	 *   Get content by id.
	 *   Is this function used anywhere?
	 *   If not, this function should probably be removed.
	 *
	 *   @param ineteger $id
	 *   @return array
	 */
	public function getById($id = 0)
	{
		// Array for content data
		$data = array();

		// Find content row by id
		$rowset = $this->find((int)$id)->current();

		// If content was found
		if(count($rowset) == 1) {
			// Content data
			$content_data = $rowset->toArray();

			// Select content ratings
			$select_ratings = $this->select()->from('content_ratings_crt', array('SUM(rating_crt) AS rate_crt'));
			$ratings = $rowset->findDependentRowset('Default_Model_ContentRatings', 'RatingsContent', $select_ratings)->toArray();

			// Find content owner
			$content_owner = $rowset->findManyToManyRowset('Default_Model_User', 'Default_Model_ContentHasUser');

			// Find content comments
			$select_comment = $this->select()->order('created_cmt ASC');
			$cmtModel = new Default_Model_Comments();
			$comments = $cmtModel->getCommentsByContent($id); //$rowset->findDependentRowset('Default_Model_Comments', 'CommentContent', $select_comment);

			// Find content keywords
			$tags = $rowset->findManyToManyRowset('Default_Model_Tags', 'Default_Model_ContentHasTag')->toArray();

			// Find content links
			$links = $rowset->findDependentRowset('Default_Model_Links')->toArray();

			// Find related content
			$related_content = $rowset->findManyToManyRowset('Default_Model_Content', 'Default_Model_ContentHasContent', 'ParentContent', 'ChildContent')->toArray();

			// Array for comment owners
			$comment_owners = array();

			// Go through all comments
			foreach($comments as $cmt) {
				// Find comment owner
				$usr = $cmt->findDependentRowset('Default_Model_User', 'CommentUser')->toArray();

				// If owner found
				if(!empty($usr)) {
					// Specify comment owner
					$comment_owners[$usr[0]['id_usr']] = $usr[0];
				} // end if
			} // end foreach

			// Gather content data
			$data['Content']['Data']        = $content_data;
			$data['Content']['Poster']      = $content_owner;
			$data['Content']['Tags']        = $tags;
			$data['Content']['Links']       = $links;
			$data['Content']['Related']     = $related_content;
			$data['Ratings']                = $ratings;
			$data['Comments']['Data']       = $comments;
			$data['Comments']['Posters']    = $comment_owners;
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

	/**
	 *
	 *
	 */
	/*
	 public function getByAuthor($author_id = 0)
	 {
	 //$contentUser = new Default_Model_ContentHasUser();
	 //$select = $this->select()->where('')
	 //$this->findDependentRowset('Default_Model_ContentHasUser', );

	 //$select = $this->_db->select()
	 //    ->from('contents_cnt', array('*'))
	 //    ->where('id_usr_cnt = ?', $author_id);

	 //$stmt = $this->_db->query($select);

	 //$result = $stmt->fetchAll();

	 //return $result;
	 }
	 */


	/* getRelatedContents
	 * 
	 * gets all contents that share tags with specified content
	 * 
	 * @param 	int		id		content id
	 * @param	int		limit	limit to N contents
	 * @return	array			array of title_cnt, id_cnt,  viewCount, contentType 
	 */
    public function getRelatedContents($id, $limit = -1) {

    	$tags = $this->getTagIdsByContentId($id);

    	$linkedContents = array();
    					
   		$cntHasTagModel = new Default_Model_ContentHasTag();
    	$select = $cntHasTagModel->select()
    							 ->from('cnt_has_tag', array('id_cnt'))
    							 ->where('id_tag IN (?)', $tags)
    							 ->where('id_cnt != ?', $id);
    	if($limit != -1)  $select->limit($limit);
   		
   		$contents = $cntHasTagModel->fetchAll($select)->toArray();
   		
   		$cnthascntModel = new Default_Model_ContentHasContent();
   		$contents = array_merge($contents, $cnthascntModel->getContentLinkIds($id));

   		$linkedContents = $this->find($contents);
 	
    	$viewsModel = new Default_Model_ContentViews();
    	$rows = array();
    	
    	foreach ($linkedContents as $row) {
    		$tempRow = array();
    		$tempRow['title_cnt']   = $row->title_cnt;
    		$tempRow['id_cnt']      = $row->id_cnt;
    		$tempRow['language_cnt']= $row->language_cnt;
    		$tempRow['viewCount']   = $viewsModel->getViewsByContentId($row->id_cnt);
    		$tempRow['contentType'] = $row->findDependentRowset('Default_Model_ContentTypes', 'ContentType')->current()->key_cty;
    		array_push($rows, $tempRow);
    		if ($limit != -1 && count($rows) >= $limit) break;
    	}

    	return $rows;
    }
    
    /* getTagNamesByContentId
     * gets tag names by content id 
     * 
     * @param			id_cnt		content id
     * @return	array	(name_tag, name_tag, ...) 
     */
    public function getTagNamesByContentId($id_cnt) {

    	$content = $this->find($id_cnt)->current();
    	$contentTagIds = $content->findDependentRowset('Default_Model_ContentHasTag', 'TagContent');
    	$tagsArray = array();
    	foreach ($contentTagIds as $tagId) {
    		$tag = $tagId->findDependentRowset('Default_Model_Tags', 'TagTag')->current();
    		array_push($tagsArray, $tag->name_tag);
	    }
	    return $tagsArray;
    }
    
    /*getTagIdsByContentId
     * 
     * Gets all tag ids linked to content
     * 
     * @param			id_cnt		content id
     * @return 	array	(id_tag, id_tag, ...) tag ids
     */
    public function getTagIdsByContentId($id_cnt) {

    	$content = $this->find($id_cnt)->current();
    	$contentTags = $content->findDependentRowset('Default_Model_ContentHasTag', 'TagContent');
    	$ids = array();
    	foreach ($contentTags as $tag) {
    		array_push($ids, $tag->id_tag);
    	}

	    return $ids;
    }

    /**
	 *   getByName
	 *
	 *   Gets content by name from database. This is used in search function.
	 *
	 *   This function rapes your database, when there's lots of content.
	 *   To fix this the <LIKE %searchTerm%> in query
	 *   should be replaced with something more efficient.
	 *   Also should find a better way to get total count of results.
	 *
	 *   @param string $searchWord
	 */
	public function getSearchResult($searchword = null, $page = 1, $count = 10, $order = 'created')
	{
		switch ($order) {
			case 'author':
				$order = 'usr.login_name_usr';
				break;
			case 'header':
				$order = 'cnt.title_cnt';
				break;
			case 'views':
				$order = 'viewCount DESC';
				break;
			default:
				$order = 'cnt.created_cnt DESC';
		}

		$contentEntries = array();

		// enable empty searches as content listing
		if ($searchword == NULL || $searchword == "") {
			$searchword = "%";
		} else {
			$searchword = '%'.$searchword.'%';
		}

		$select = $this->_db->select()->from(array('cty' => 'content_types_cty'),
		array('cty.key_cty'))
		->joinLeft(array('cnt' => 'contents_cnt'),
                                             'cnt.id_cty_cnt = cty.id_cty',
		array('cnt.id_cnt',
                                                   'cnt.title_cnt',
                                                   'cnt.lead_cnt',
                                                   'cnt.created_cnt'))
		->joinLeft(array('cht' => 'cnt_has_tag'),
                                             'cht.id_cnt = cnt.id_cnt',
		array())
		->joinLeft(array('tag' => 'tags_tag'),
                                             'cht.id_tag = tag.id_tag',
		array())
		->joinLeft(array('chu' => 'cnt_has_usr'),
                                             'chu.id_cnt = cnt.id_cnt', 
		array())
		->joinLeft(array('vws' => 'cnt_views_vws'),
                                                 'cnt.id_cnt = vws.id_cnt_vws',
		array('viewCount' => 'SUM(DISTINCT vws.views_vws)'))
		->joinLeft(array('usr' => 'users_usr'),
                                             'chu.id_usr = usr.id_usr',
		array('usr.id_usr', 'usr.login_name_usr'))
		->where('cnt.published_cnt = 1')
		// extra "(" hacks AND statements together
		->where('(cnt.title_cnt LIKE ?', $searchword)
		->orWhere('cnt.lead_cnt LIKE ?', $searchword)
		->orWhere('cnt.body_cnt LIKE ?', $searchword)
		->orWhere('tag.name_tag LIKE ?)',$searchword)
		->group('cnt.id_cnt')
		->order($order)
		->limitPage($page, $count);

		$contentEntries = $this->_db->fetchAll($select);

		return $contentEntries;
	} // end of getByName

	/**
	 *   getContentCountBySearch
	 *
	 *   Get total content count by search.
	 *
	 *   This also helps to rape your database.
	 *   Should find a better way to retrieve the total
	 *   count of content, that search finds.
	 *
	 *   @param string $searchword
	 *   @return int amount
	 */
	public function getContentCountBySearch($searchword = null)
	{
		if ($searchword == null) {
			$searchword = "%";
		} else {
			$searchword = '%'.$searchword.'%';
		}

		$select = $this->_db->select()->from(array('cnt' => 'contents_cnt'),
		array('contentCount' => 'COUNT(DISTINCT cnt.id_cnt)'))
		->joinLeft(array('cht' => 'cnt_has_tag'),
                                             'cht.id_cnt = cnt.id_cnt',
		array())
		->joinLeft(array('tag' => 'tags_tag'),
                                             'cht.id_tag = tag.id_tag',
		array())
		->where('cnt.published_cnt = 1')
		->where('cnt.title_cnt LIKE ?', $searchword)
		->orWhere('cnt.lead_cnt LIKE ?', $searchword)
		->orWhere('cnt.body_cnt LIKE ?', $searchword)
		->orWhere('tag.name_tag LIKE ?',$searchword);

		$data = $this->_db->fetchAll($select);

		if (isset($data[0]['contentCount'])) {
			return $data[0]['contentCount'];
		}

		return 0;
	}

	/**
	 *   addContent
	 *
	 *   Add content.
	 *
	 *    @param array $data
	 */
	public function addContent($data)
	{
		$auth = Zend_Auth::getInstance();

		// Create a new row
		$content = $this->createRow();
		//Zend_Debug::dump($content, $label=null, $echo=true);

		// Set data to row
		$content->id_cty_cnt = $data['content_type'];
		$content->title_cnt = htmlspecialchars($data['content_header']);
		$content->lead_cnt = htmlspecialchars($data['content_textlead']);
		$content->body_cnt = htmlspecialchars($data['content_text']);

		if(isset($data['content_research'])) {
			$content->research_question_cnt = htmlspecialchars($data['content_research']);
		}

		if(isset($data['content_opportunity'])) {
			$content->opportunity_cnt = htmlspecialchars($data['content_opportunity']);
		}

		if(isset($data['content_threat'])) {
			$content->threat_cnt = htmlspecialchars($data['content_threat']);
		}

		if(isset($data['content_solution'])) {
			$content->solution_cnt = htmlspecialchars($data['content_solution']);
		}

		$content->references_cnt = $data['content_references'];
		$content->published_cnt = $data['publish'];

		$content->created_cnt = new Zend_Db_Expr('NOW()');
		$content->modified_cnt = new Zend_Db_Expr('NOW()');
		$content->language_cnt = $data['content_language'];
			
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

		if(!$content->save()) {
			$return = false;
		} else {
			// If save was successful, content id is returned, because it is needed
			// when redirecting
			$return = $content->id_cnt;
			/*if($_FILES['content_file_upload']['size'] != 0) {
			 $files = new Default_Model_Files();
			 $files->newFile($content->id_cnt, $auth->getIdentity()->user_id);
			 }*/
				
			$filesModel = new Default_Model_Files();
			for ($i=1;$i < count($data['files']['name']);$i++)
			{
				$files = $data['files'];
				$file['name'] = $files['name'][$i];
				$file['type'] = $files['type'][$i];
				$file['tmp_name'] = $files['tmp_name'][$i];
				$file['error'] = $files['error'][$i];
				$file['size'] = $files['size'][$i];
				$filesModel->newFile($content->id_cnt, $data['User']['id_usr'], $file);
			}
		}
        
        // What is this used for
        //$contentTypes = new Default_Model_ContentTypes();
        //$content_type = $contentTypes->getTypeById($data['content_type']);
        
        if($data['content_relatesto_id'] != 0) {
            $contentHasContent = new Default_Model_ContentHasContent();
            $contentHasContent->addContentToContent(
                $data['content_relatesto_id'], 
                $content->id_cnt
            );
        }
        
        // Add user to content
        $contentHasUser = new Default_Model_ContentHasUser();
        $contentHasUser->addUserToContent($content->id_cnt, $data['User']['id_usr'], 1);
        
        // Check if user has given keywords
        if(!empty($data['content_keywords'])) {
            $tagModel = new Default_Model_Tags();
            $tagModel->addTagsToContent(
                $content->id_cnt, $data['content_keywords']
            );
        
            // Go through all given keywords
            // This should be in Tags model
            /*
            foreach($data['content_keywords'] as $tag) {
                $tagRow = new Default_Model_Tags();
                $tag = strip_tags($tag);
                
                // Check if given keyword does not exists in database
                if($tagRow->tagExists($tag)) {
                    // Create new keyword
                    $tag = $tagRow->createTag($tag);
                } else {
                    // Get keyword
                    $tag = $tagRow->getTag($tag);
                } // end else
                
                // echo '<pre>';echo $tag->id_tag.'    '.$content->id_cnt;echo '</pre>';
                //die();
                
                // Add keywords to content
                $contentHasTag = new Default_Model_ContentHasTag();
                $contentHasTag->addTagToContent($tag->id_tag, $content->id_cnt);
            } // end foreach 
            */
        } // end if
        
        // Check if user has given related companies
        if(!empty($data['content_related_companies'])) {
            $recModel = new Default_Model_RelatedCompanies();
            $recModel->addRelatedCompaniesToContent(
                $content->id_cnt, $data['content_related_companies']
            );
            // Go through all given related companies
            // FIX: This should be in RelatedCompanies model
            /*foreach($data['content_related_companies'] as $relComp) {
                $relCompRow = new Default_Model_RelatedCompanies();
                $relComp = strip_tags($relComp);
                
                // Check if given related company does not exists in database
                if($relCompRow->relCompExists($relComp)) {
                    // Create new related company
                    $relComp = $relCompRow->createRelComp($relComp);
                } else {
                    // Get related company
                    $relComp = $relCompRow->getRelComp($relComp);
                } // end else
                
                // echo '<pre>';echo $tag->id_tag.'    '.$content->id_cnt;echo '</pre>';
                //die();
                
                // Add related companies to content
                $contentHasRelatedCompany = new Default_Model_ContentHasRelatedCompany();
                $contentHasRelatedCompany->addRelCompToContent($relComp->id_rec, $content->id_cnt);
            } // end foreach */
        } // end if
        
        // Check if user has given campaigns
        if(!empty($data['content_campaigns'])) {
            $cmpModel = new Default_Model_Campaigns();
            $cmpModel->addCampaignsToContent(
                $content->id_cnt, $data['content_campaigns']
            );
            // Go through all given campaigns
            // FIX: This should be in Campaigns model
            /*foreach($data['content_campaigns'] as $campaign) {
                $campaignRow = new Default_Model_Campaigns();
                $campaign = strip_tags($campaign);
                
                // Check if given campaign does not exists in database
                if($campaignRow->campaignExists($campaign)) {
                    // Create new campaign
                    $campaign = $campaignRow->createCampaign($campaign);
                } else {
                    // Get campaign
                    $campaign = $campaignRow->getCampaign($campaign);
                } // end else
                
                // echo '<pre>';echo $tag->id_tag.'    '.$content->id_cnt;echo '</pre>';
                //die();
                
                // Add related companies to content
                $contentHasCampaign = new Default_Model_ContentHasCampaign();
                $contentHasCampaign->addCampaignToContent($campaign->id_cmp, $content->id_cnt);
            } // end foreach */
        } // end if
        
        // Add industry to content
        $contentHasIndustry = new Default_Model_ContentHasIndustries();
        
        if(isset($data['content_industry'])) {
            $id_ind = 0;
            
            if($data['content_class'] != 0) {
                $id_ind = $data['content_class'];
            } elseif($data['content_group'] != 0) {
                $id_ind = $data['content_group'];
            } elseif($data['content_division'] != 0) {
                $id_ind = $data['content_division'];
            } elseif($data['content_industry'] != 0) {
                $id_ind = $data['content_industry'];
            }
        }
        
        if($id_ind != 0) {
            $contentHasIndustry->addIndustryToContent($content->id_cnt, $id_ind);
        }
        
        // Add future info classification to content
        if(isset($data['content_finfo_class'])) {
            if($data['content_finfo_class'] != 0) {
                $contentHasFutureinfoClass = new Default_Model_ContentHasFutureinfoClasses();
                $contentHasFutureinfoClass->addFutureinfoClassToContent($content->id_cnt, $data['content_finfo_class']);
            }
        }
        
        // Add innovation type to content
        if(isset($data['innovation_type'])) {
            if($data['innovation_type'] != 0) {
                $contentHasInnovationType = new Default_Model_ContentHasInnovationTypes();
                $contentHasInnovationType->addInnovationTypeToContent($content->id_cnt, $data['innovation_type']);
            }
        }
        
        return $return;
    } // end of addContent
   


	/**
	 *   editContent
	 *
	 *   Edit content.
	 *
	 *   @param array $data
	 */
	public function editContent($data)
	{
		// Get the original content
		$content = $this->getContentRow($data['content_id']);

		// Get content type
		$contentTypes = new Default_Model_ContentTypes();
		$contentType = $contentTypes->getTypeById($content['id_cty_cnt']);

		// Unset fields that are not going to be updated
		unset($content['id_cnt']);
		unset($content['id_cty_cnt']);
		unset($content['views_cnt']);
		unset($content['created_cnt']);

		$content['title_cnt'] = htmlspecialchars($data['content_header']);
		$content['lead_cnt'] = htmlspecialchars($data['content_textlead']);
		$content['language_cnt'] = $data['content_language'];
		$content['body_cnt'] = htmlspecialchars($data['content_text']);

		if(!isset($data['content_research'])) {
			$data['content_research'] = "";
		}

		if(!isset($data['content_opportunity'])) {
			$data['content_opportunity'] = "";
		}

		if(!isset($data['content_threat'])) {
			$data['content_threat'] = "";
		}

		if(!isset($data['content_solution'])) {
			$data['content_solution'] = "";
		}

		$content['research_question_cnt'] = htmlspecialchars($data['content_research']);
		$content['opportunity_cnt'] = htmlspecialchars($data['content_opportunity']);
		$content['threat_cnt'] = htmlspecialchars($data['content_threat']);
		$content['solution_cnt'] = htmlspecialchars($data['content_solution']);
		$content['references_cnt'] = htmlspecialchars($data['content_references']);
		$content['modified_cnt'] = new Zend_Db_Expr('NOW()');
		
		
		if (isset($data['publish']) && $data['publish'] == 1) 
			$content['published_cnt'] = 1;

		$where = $this->getAdapter()->quoteInto('`id_cnt` = ?', $data['content_id']);

		// MIK� VITTU T�SS� KUSEE?
		if(!$this->update($content, $where)) {
			$return = false;
		} else {
			$return = $data['content_id'];
		}

		// Check if user has given keywords
		if(!empty($data['content_keywords'])) {
			// Get existing keywords of the content
			$cntHasTag = new Default_Model_ContentHasTag();
			$existingTags = $cntHasTag->checkExistingTags(
			$data['content_id'], $data['content_keywords']
			);

			//$existingTags = $cntHasTag->getContentTags($data['content_id']);

			$modelTags = new Default_Model_Tags();
			$modelTags->addTagsToContent(
			$data['content_id'], $data['content_keywords'], $existingTags
			);

			/*
			 $i = 0;
			 // Go through all existing keywords
			 // This belongs to contentHasTags model
			 foreach($existingTags as $existingTag) {
			 // If some of the existing keywords aren't found in sent keywords,
			 // that keyword is deleted the from content and maybe even from the
			 // database
			 if(!in_array($existingTag['name_tag'], $data['content_keywords'])) {
			 // Removing tag from content
			 $cntHasTag->deleteTagFromContent($existingTag['id_tag'], $data['content_id']);

			 // If other content(s) doesn't have this tag, the whole
			 // tag is going to be removed from the database
			 if(!$cntHasTag->checkIfOtherContentHasTag($existingTag['id_tag'], $data['content_id'])) {
			 $modelTags->removeTag($existingTag['id_tag']);
			 }

			 // Remove tag from existingTags array
			 unset($existingTags[$i]);
			 }
			 $i++;
			 }
			 */

			/*
			 // Go through all sent keywords
			 // This belongs to Tags model
			 foreach($data['content_keywords'] as $tag) {
			 $tag = strip_tags($tag);

			 $foundTag = false;
			 foreach($existingTags as $existingTag) {
			 if($tag == $existingTag['name_tag']) {
			 $foundTag = true;
			 }
			 }

			 // If tag is not found in existing tags
			 if(!$foundTag) {
			 // Check if given keyword does not exists in database
			 if($modelTags->tagExists($tag)) {
			 // Create new keyword
			 $tag = $modelTags->createTag($tag);
			 } else {
			 // Get keyword
			 $tag = $modelTags->getTag($tag);
			 } // end else

			 // Add keywords to content
			 $cntHasTag->addTagToContent($tag->id_tag, $data['content_id']);
			 }
			 } // end foreach
			 */
		} // end if

		// Check if user has related companies
		if(!empty($data['content_related_companies'])) {
			// Get existing related companies of the content
			$cntHasRec = new Default_Model_ContentHasRelatedCompany();
			$existingCompanies = $cntHasRec->checkExistingCompanies(
			$data['content_id'], $data['content_related_companies']
			);
			//$existingRecs = $cntHasRec->getContentRelComps($data['content_id']);

			$modelRecs = new Default_Model_RelatedCompanies();
			$modelRecs->addRelatedCompaniesToContent(
			$data['content_id'], $data['content_related_companies'], $existingCompanies
			);
			/*
			 $i = 0;
			 // Go through all existing related companies
			 // FIX: This belongs to ContentHasRelatedCompany model
			 foreach($existingRecs as $existingRec) {
			 // If some of the existing related companies aren't found in sent
			 // related companies, that related company is deleted from the
			 // content and maybe even from thedatabase
			 // FIXED: Could have caused mismatches when adding,
			 // since when related company is added strip_tags is used
			 // but not when doing comparisons here.
			 // This goes for campaigns as well.
			 if(!in_array($existingRec['name_rec'], $data['content_related_companies'])) {
			 // Removing rec from content
			 $cntHasRec->deleteRelCompFromContent($existingRec['id_rec'], $data['content_id']);

			 // If other content(s) doesn't have this related company, the whole
			 // related company is going to be removed from the database
			 if(!$cntHasRec->checkIfOtherContentHasRelComp($existingRec['id_rec'], $data['content_id'])) {
			 $modelRecs->removeRelComp($existingRec['id_rec']);
			 }

			 // Remove related company from existingRecs array
			 unset($existingRecs[$i]);
			 }
			 $i++;
			 }
			 */

			/*
			 // Go through all sent related companies
			 // FIX: This belongs to RelatedCompany model
			 foreach($data['content_related_companies'] as $rec) {
			 //$rec = strip_tags($rec);

			 $foundRec = false;
			 foreach($existingRecs as $existingRec) {
			 if($rec == $existingRec['name_rec']) {
			 $foundRec = true;
			 }
			 }

			 // If related company is not found in existing related companies
			 if(!$foundRec) {
			 // Check if given related company does not exists in database
			 if($modelRecs ->relCompExists($rec)) {
			 // Create new related company
			 $rec = $modelRecs ->createRelComp($rec);
			 } else {
			 // Get related company
			 $rec = $modelRecs ->getRelComp($rec);
			 } // end else

			 // Add related company to content
			 $cntHasRec->addRelCompToContent($rec->id_rec, $data['content_id']);
			 }
			 } // end foreach
			 */
		} // end if

		/*
		 if($_FILES['content_file_upload']['size'] != 0) {
		 $files = new Default_Model_Files();
		 $files->newFile($content->id_cnt, $auth->getIdentity()->user_id);
		 }
		 */
		// Check if user has given campaigns
		if(!empty($data['content_campaigns'])) {
			// Get existing campaigns of the content
			$cntHasCmp = new Default_Model_ContentHasCampaign();
			$existingCampaigns = $cntHasCmp->checkExistingCampaigns(
			$data['content_id'], $data['content_campaigns']
			);
			//$existingCmps = $cntHasCmp->getContentCampaigns($data['content_id']);

			$modelCmps = new Default_Model_Campaigns();
			$modelCmps->addCampaignsToContent(
			$data['content_id'], $data['content_campaigns'], $existingCampaigns
			);

			/*
			 $i = 0;
			 // Go through all existing campaigns
			 // FIX: This belongs to ContentHasCampaign moodel
			 foreach($existingCmps as $existingCmp) {
			 // If some of the existing campaigns aren't found in sent campaigns,
			 // that campaign is deleted the from content and maybe even from the
			 // database
			 if(!in_array($existingCmp['name_cmp'], $data['content_campaigns'])) {
			 // Removing campaign from content
			 $cntHasCmp->deleteCampaignFromContent($existingCmp['id_cmp'], $data['content_id']);

			 // If other content(s) doesn't have this campaign, the whole
			 // campaign is going to be removed from the database
			 if(!$cntHasCmp->checkIfOtherContentHasCampaign($existingCmp['id_cmp'], $data['content_id'])) {
			 $modelCmps->removeCampaign($existingCmp['id_cmp']);
			 }

			 // Remove campaign from existingCmps array
			 unset($existingCmps[$i]);
			 }
			 $i++;
			 }
			 */

			/*
			 // Go through all sent campaigns
			 // FIX: This belongs to Campaigns model
			 foreach($data['content_campaigns'] as $cmp) {
			 //$cmp = strip_tags($cmp);

			 $foundCmp = false;
			 foreach($existingCmps as $existingCmp) {
			 if($cmp == $existingCmp['name_cmp']) {
			 $foundCmp = true;
			 }
			 }

			 // If campaign is not found in existing tags
			 if(!$foundCmp) {
			 // Check if given campaign does not exists in database
			 if($modelCmps->campaignExists($cmp)) {
			 // Create new campaign
			 $cmp = $modelCmps->createCampaign($cmp);
			 } else {
			 // Get campaign
			 $cmp = $modelCmps->getCampaign($cmp);
			 } // end else

			 // Add campaigns to content
			 $cntHasCmp->addCampaignToContent($cmp->id_cmp, $data['content_id']);
			 }
			 } // end foreach
			 */
		} // end if

		/*// Update industry to content
		 $contentHasIndustry = new Default_Model_ContentHasIndustries();
		 $current_industry = $contentHasIndustry->getIndustryIdOfContent($data['content_id']);
		 if($current_industry != $data['content_industry_id']) {
		 if(!$contentHasIndustry->updateIndustryToContent($data['content_industry_id'], $data['content_id'])) {
		 $return = false;
		 }
		 }*/
		if ($return) {
			$filesModel = new Default_Model_Files();

			for ($i=1;$i < count($data['files']['name']);$i++)
			{
				$files = $data['files'];
				$file['name'] = $files['name'][$i];
				$file['type'] = $files['type'][$i];
				$file['tmp_name'] = $files['tmp_name'][$i];
				$file['error'] = $files['error'][$i];
				$file['size'] = $files['size'][$i];
				$filesModel->newFile($data['content_id'], $data['User']['id_usr'], $file);
			}
				
			if (isset($data['uploadedFiles'])) $filesModel->deleteFiles($data['uploadedFiles']);

			//die;
		}
		if($contentType == "idea") {
			// Update innovation type to content
			$contentHasInnovationType = new Default_Model_ContentHasInnovationTypes();
			$current_innovation_type = $contentHasInnovationType->getInnovationTypeIdOfContent($data['content_id']);
			if($current_innovation_type != $data['innovation_type']) {
				if(!$contentHasInnovationType->updateInnovationTypeToContent($data['innovation_type'], $data['content_id'])) {
					$return = false;
				}
			}
		}
		return $return;
	}

	/**
	 *   publishContent
	 *   Publishes specified content
	 *
	 *   @param int id_cnt The id of content to be published
	 * 	@param(optional) int pubFlag Value for "published"-flag, set to true (1) by default.
	 *   @return bool $return
	 *   @author Pekka Piispanen
	 */
	public function publishContent($id_cnt = 0, $pubFlag = 1)
	{
		$return = false;

		$data = array('published_cnt' => $pubFlag);
		$where = $this->getAdapter()->quoteInto('id_cnt = ?', (int)$id_cnt);

		if($this->update($data, $where)) {
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

		$content = new Default_Model_Content();

		if($this->delete("id_cnt = ".$id_cnt)) {
			$return = true;
		}

		return $return;
	} // end of removeContent


        /**
	 *   removeContent
	 *   Removes specified content from the database and all related stuff
	 *
	 *   @param int id_cnt The id of content to be removed
	 *   @return boolean array $contentRemoveChecker
	 *   @author Mikko Korpinen
	 */
        public function removeContentAndDepending($id_cnt = 0)
        {
            /*$contentRemoveChecker = array(
                'removeContentFromCampaign' =>          true,
                'removeContentFromContent' =>           true,
                'removeContentFromFutureinfoClasses' => true,
                'removeContentFromIndustries' =>        true,
                'removeContentFromInnovationTypes' =>   true,
                'removeContentFromRelatedCompanies' =>  true,
                'removeContentRelatedCompanies' =>      true,
                'removeContentFromTags' =>              true,
                'removeContentTags' =>                  true,
                'removeContentFromUser' =>              true,
                'removeContentViews' =>                 true,
                'removeContentFlags' =>                 true,
                'removeContentCommentFlags' =>          true,
                'removeContentRatings' =>               true,
                'removeContentFiles' =>                 true,
                'removeUserFromFavorites' =>            true,
                'removeContent' =>                      true,
                'removeContentComments' =>              true
            );*/

            // cnt_has_cmp
            $cmpHasCnt = new Default_Model_CampaignHasContent();
            if (!$cmpHasCnt->removeContentCampaignLinks($id_cnt))
                $contentRemoveChecker['removeContentFromCampaign'] = false;

            // cnt_has_cnt
            $cntHasCnt = new Default_Model_ContentHasContent();
            if (!$cntHasCnt->removeContentFromContents($id_cnt))
                $contentRemoveChecker['removeContentFromContent'] = false;

            // cnt_has_fic
            $cntHasFic = new Default_Model_ContentHasFutureinfoClasses();
            if (!$cntHasFic->removeFutureinfoClassesFromContent($id_cnt))
                $contentRemoveChecker['removeContentFromFutureinfoClasses'] = false;

            // cnt_has_grp
            // Not used?

            // cnt_has_ind
            $cntHasInd = new Default_Model_ContentHasIndustries();
            if (!$cntHasInd->removeIndustriesFromContent($id_cnt))
                $contentRemoveChecker['removeContentFromIndustries'] = false;

            // cnt_has_ivt
            $cntHasIvt = new Default_Model_ContentHasInnovationTypes();
            if (!$cntHasIvt->removeInnovationTypesFromContent($id_cnt))
                $contentRemoveChecker['removeContentFromInnovationTypes'] = false;

            // related_companies_rec and cnt_has_rec
            $cntHasRec = new Default_Model_ContentHasRelatedCompany();
            $recs = $cntHasRec->getContentRelComps($id_cnt);
            $rec = new Default_Model_RelatedCompanies();
            foreach($recs as $id_rec) {
                if (!$cntHasRec->checkIfOtherContentHasRelComp($id_rec['id_rec'], $id_cnt)) {
                    if (!$rec->removeRelComp($id_rec['id_rec']))
                        $contentRemoveChecker['removeRelatedCompanies'] = false;
                }
            }
            if (!$cntHasRec->removeContentRelComps($id_cnt))
                $contentRemoveChecker['removeContentFromRelatedCompanies'] = false;

            // tags_tag and cnt_has_tag
            $cntHasTag = new Default_Model_ContentHasTag();
            $tags = $cntHasTag->getContentTags($id_cnt);
            $tag = new Default_Model_Tags();
            foreach($tags as $id_tag) {
                if(!$cntHasTag->checkIfOtherContentHasTag($id_tag['id_tag'], $id_cnt)) {
                    if (!$tag->removeTag($id_tag['id_tag']))
                        $contentRemoveChecker['removeTags'] = false;
                }
            }
            if (!$cntHasTag->removeContentTags($id_cnt))
                $contentRemoveChecker['removeContentFromTags'] = false;

            // cnt_has_usr
            $cntHasUsr = new Default_Model_ContentHasUser();
            if (!$cntHasUsr->removeUserFromContent($id_cnt))
                $contentRemoveChecker['removeContentFromUser'] = false;

            // cnt_publish_times_pbt
            // Not used?

            // cnt_views_vws
            $cntWiewsVws = new Default_Model_ContentViews();
            if (!$cntWiewsVws->removeContentViews($id_cnt))
                $contentRemoveChecker['removeContentViews'] = false;

            // Flags from content_flags_cfl
            $contentflagmodel = new Default_Model_ContentFlags();
            $cnfl_ids = $contentflagmodel->getFlagsByContentId($id_cnt);
            if (is_array($cnfl_ids)) {
                foreach($cnfl_ids as $cfl_id) {
                    if (!$contentflagmodel->removeFlag($cfl_id))
                        $contentRemoveChecker['removeContentFlags'] = false;
                }
            }
            // Flags from comment_flags_cfl
            $commentflagmodel = new Default_Model_CommentFlags();
            $cmfl_ids = $commentflagmodel->getFlagsByContentId($id_cnt);
            if (is_array($cmfl_ids)) {
                foreach($cmfl_ids as $cfl_id) {
                    if (!$commentflagmodel->removeFlag($cfl_id))
                        $contentRemoveChecker['removeContentCommentFlags'] = false;
                }
            }

            // content_ratings_crt
            $contentRatingRct = new Default_Model_ContentRatings();
            if (!$contentRatingRct->removeContentRatings($id_cnt))
                $contentRemoveChecker['removeContentRatings'] = false;

            // files_fil
            $files = new Default_Model_Files();
            if (!$files->removeContentFiles($id_cnt))
                $contentRemoveChecker['removeContentFiles'] = false;

            // links_lnk
            // Not used?

            // usr_has_fvr
            $usrHasFvr = new Default_Model_UserHasFavourites();
            if (!$usrHasFvr->removeAllContentFromFavouritesByContentId($id_cnt))
                $contentRemoveChecker['removeUserFromFavorites'] = false;

            // contents_cnt
            $contentmodel = new Default_Model_Content();
            if (!$contentmodel->removeContent($id_cnt))
                $contentRemoveChecker['removeContent'] = false;
            // coments_cmt
            $commentmodel = new Default_Model_Comments();
            if (!$commentmodel->removeAllContentComments($id_cnt))
                $contentRemoveChecker['removeContentComments'] = false;

            
            return $contentRemoveChecker;
        }

	/**
	 * checkIfContentExists
	 *
	 */
	public function checkIfContentExists($id_cnt = 0)
	{
		$return = false;
		if((int)$id_cnt != 0) {
			$select = $this->select()
			->from($this, array('*'))
			->where('id_cnt = ?', (int)$id_cnt);

			$result = $this->fetchAll($select)->toArray();

			if(count($result) != 0) {
				$return = true;
			}
		}

		return $return;
	}

	/**
	 * getContentTypeIdByContentId
	 *
	 *
	 */
	public function getContentTypeIdByContentId($id_cnt = 0) {
		$select = $this->select()
		->from($this, array('id_cty_cnt'))
		->where('id_cnt = ?', (int)$id_cnt);

		$result = $this->fetchAll($select)->toArray();

		return $result[0]['id_cty_cnt'];
	}

	/**
	 * getContentHeaderByContentId
	 *
	 *
	 */
	public function getContentHeaderByContentId($id_cnt = 0) {
		if((int)$id_cnt != 0) {
			$select = $this->select()
			->from('contents_cnt', array('title_cnt'))
			->where('id_cnt = ?', (int)$id_cnt);

			$result = $this->fetchAll($select)->toArray();

			return $result[0]['title_cnt'];
		} else {
			return NULL;
		}
	}

	/**
	 *   getDataForView
	 *
	 *   Get content by id.
	 *   Is this function used anywhere?
	 *   If not, this function should probably be removed.
	 *
	 *   @param ineteger $id
	 *   @return array
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
		if(count($result) == 1) {
			$data['Content']['Data'] = $result[0];

			// Find Ratings
			//$select_ratings = $this->select()->from('content_ratings_crt', array('SUM(rating_crt) AS rate_crt'));
			//$ratings = $rowset->findDependentRowset('Default_Model_ContentRatings', 'RatingsContent', $select_ratings)->toArray();
			$ratings = new Default_Model_ContentRatings();
			$rating = $ratings->getById($id);

			// Find content owners
			//$content_owner = $rowset->findManyToManyRowset('Default_Model_User', 'Default_Model_ContentHasUser');
			$cntHasUser = new Default_Model_ContentHasUser();
			$owners = $cntHasUser->getContentOwners($id);

			// Find owners
			$userModel = new Default_Model_User();
			$i = 0;
			foreach ($owners as $owner) {
				$data['Content']['Data']['Owners'][$i] = $userModel->getSimpleUserDataById($owner);
				$i++;
			}

			// Find content comments
			//$select_comment = $this->select()->order('created_cmt ASC');
			//$comments = $rowset->findDependentRowset('Default_Model_Comments', 'CommentContent', $select_comment);
			$commentModel = new Default_Model_Comments();
			$comments = $commentModel->getAllByContentId($id);

			/*  comment owner username is fetched in the previous query, no need for this anymore
			 // Array for comment owners
			 $comment_owners = array();

			 // Go through all comments
			 foreach($comments as $cmt)
			 {
			 // Find comment owner
			 $usr = $cmt->findDependentRowset('Default_Model_User', 'CommentUser')->toArray();

			 // If owner found
			 if(!empty($usr))
			 {
			 // Specify comment owner
			 $comment_owners[$usr[0]['id_usr']] = $usr[0];
			 } // end if
			 } // end foreach
			 */

			// Find content keywords
			//$tags = $rowset->findManyToManyRowset('Default_Model_Tags', 'Default_Model_ContentHasTag')->toArray();
			$cntHasTag = new Default_Model_ContentHasTag();
			$tags = $cntHasTag->getContentTags($id);

			// Find content links - needs updating to this version
			$links = array(); //$rowset->findDependentRowset('Default_Model_Links')->toArray();

			// Find related content
			//$$related_content = $rowset->findManyToManyRowset('Default_Model_Content', 'Default_Model_ContentHasContent', 'ParentContent', 'ChildContent')->toArray();
			$contentHasContent = new Default_Model_ContentHasContent();
			$familyTree = $contentHasContent->getContentFamilyTree($id);

			// echo"<pre>"; print_r($tagArray); echo"</pre>"; die;

			// Gather and format content data a bit
			$data['Content']['Data']['rating']  = $rating;
			//$data['Content']['Data']['owner'] = $owner;
			$data['Content']['Tags']            = $tags;
			$data['Content']['Links']           = $links;
			$data['Content']['FamilyTree']      = $familyTree;
			$data['Comments']['Data']           = $comments;
			//echo"<pre>"; print_r($comments); echo"</pre>"; die;
			//$data['Comments']['Posters']        = $comment_owners;
		}
		return $data;
	} // end of getById

	/**
	 *   getDataAsSimpleArray
	 *
	 *
	 */
	public function getDataAsSimpleArray($id = -1)
	{
		if((int)$id != -1) {
			$select = $this->_db->select()
			->from(array('cnt' => 'contents_cnt'),
			array('*'))
			->joinLeft(array('chu' => 'cnt_has_usr'),
                                           'chu.id_cnt = cnt.id_cnt',
			array())
			->joinLeft(array('usr' => 'users_usr'),
                                           'usr.id_usr = chu.id_usr',
			array('usr.id_usr', 'usr.login_name_usr'))
			->joinLeft(array('cty' => 'content_types_cty'),
                                           'cnt.id_cty_cnt = cty.id_cty',
			array('cty.key_cty', 'cty.name_cty'))
			->where('cnt.id_cnt = ?', (int)$id)
			// ->where('published_cnt = 1')
			;

			$result = $this->_db->fetchAll($select);

			if (isset($result[0]) && !empty($result[0])) {
				return $result[0];
			}
		}

		return false;
	}

	/*
	 public function getContentByIndustry($ind_id)
	 {
	 $select = $this->_db->select()->from(array('cnt' => $this), array('*'))
	 ->join(array('chi' => 'cnt_has_ind'),
	 'cnt.id_cnt = chi.id_cnt',
	 array('*'))
	 ->join(array('ind' => 'industries_ind'),
	 'chi.id_ind = ind.id_ind',
	 array('*'));

	 $result = $this->_db->fetchAll($select);

	 return $result;
	 }
	 */

	/**
	 * getMostViewed
	 *
	 *
	 */
	public function getMostViewed ($limit = 20)
	{
		$select = $this->_db->select()->from(array('cnt' => 'contents_cnt'),
		array('cnt.id_cnt', 'cnt.title_cnt'))
		->join(array('vws' => 'cnt_views_vws'),
		'cnt.id_cnt = vws.id_cnt_vws',
		array('totalViews' => 'COUNT(vws.id_usr_vws)'))
		->group('cnt.id_cnt')
		->limit($limit)
		->order('totalViews');
		
		$result = $this->_db->fetchAll($select);

		// Zend_Debug::dump($result);

		return $result;
	}
	

	/**
	 * getMostViewedType
	 *
	 *
	 */
	public function getMostViewedType ($cty = 'all', $page = 1, $count = -1, $order = 'views', $lang = 'en', $ind = 0)
	{
		switch ($order) {
			case 'author':
				$order = 'usr.login_name_usr';
				break;
			case 'header':
				$order = 'cnt.title_cnt';
				break;
		 	case 'views':
				$order = 'viewCount DESC';
		 		break;
			default:
				$order = 'cnt.created_cnt DESC';
		}

		/*
		 $industry = 1;
		 if ($ind > 0) {
		 $industry = $this->_db->quoteInto('chi.id_ind = ?', $ind);
		 }
		 */

		// Needs more optimization
		$select = $this->_db->select()->from(array('cty' => 'content_types_cty'),
		array('cty.id_cty', 'cty.key_cty'))
		->join(array('cnt' => 'contents_cnt'),
                                            'cnt.id_cty_cnt = cty.id_cty',
		array('cnt.id_cnt',
                                                  'cnt.title_cnt',
                                                  'cnt.lead_cnt',
                                                  'cnt.created_cnt',
                                                  'cnt.language_cnt'))
		->joinLeft(array('chu' => 'cnt_has_usr'),
                                            'chu.id_cnt = cnt.id_cnt',
		array())
		->joinLeft(array('usr' => 'users_usr'),
                                            'usr.id_usr = chu.id_usr',
		array('usr.id_usr',
                                                  'usr.login_name_usr'))
		
		 ->joinLeft(array('chi' => 'cnt_has_ind'),
		 'chi.id_cnt = cnt.id_cnt',
		 array())

		 ->joinLeft(array('vws' => 'cnt_views_vws'),
		 'vws.id_cnt_vws = cnt.id_cnt',
		 array('viewCount' => 'COUNT(vws.id_usr_vws)'))
		/*
		 ->joinLeft(array('ind' => 'industries_ind'),
		 'ind.id_ind = chi.id_ind',
		 array())*/
		->group('cnt.id_cnt')
		->where('cnt.published_cnt = 1')
		//->where('cnt.language_cnt = ?', $lang)
		->order($order);

		if ($cty != 'all' && $cty != 'All') {
			$select->where('cty.key_cty = ?', $cty);
		}

		if ($count > 0){
			$select->limitPage($page, $count);
		} else {
			$select->limit($page);
		}

		// Content data
		$data = $this->_db->fetchAll($select);	

		return $data;
	}
	
	
	public function getRecentByLangAndType($lang, $type, $limit=10) {
		$order = 'contents_cnt.created_cnt DESC';

		$select = $this->select()
					->from($this, array("*"))
					->join('content_types_cty', 'contents_cnt.id_cty_cnt = content_types_cty.id_cty', array())
					->where('published_cnt = 1')
					//->where('language_cnt = ?', $lang)
					->order($order)
					->limit($limit);
					
		if($type != "all") {
			$select->where('key_cty = ?', $type);
		}
		
		// Content data
		$data = $this->_db->fetchAll($select);
		
		return $data;
	}
	/**
	 * 
	 * @param int $content_id, id $user
	 * @return boolean
	 */
	public function checkIfUserIsOwner($id = -1,$user = 0)
	{
		if((int)$id != -1 && $user != 0) {
			$select = $this->_db->select()
							->from(array('cnt' => 'contents_cnt'),
									array())
							->joinLeft(array('chu' => 'cnt_has_usr'),
                                    'chu.id_cnt = cnt.id_cnt',
									array())
							->joinLeft(array('usr' => 'users_usr'),
                                    'usr.id_usr = chu.id_usr',
									array('usr.id_usr'))
							->where('cnt.id_cnt = ?', (int)$id)
							->where('usr.id_usr = ?', $user)
			;

			$result = $this->_db->fetchAll($select);
			if($result) return true;
		}

		return false;
	}

	public function hasCntLinks($id_cnt) {
		$select = $this->_db->select()->from('cnt_has_cnt', 'created_cnt')
									  ->where('id_parent_cnt = ?', $id_cnt)
									  ->orWhere('id_child_cnt = ?', $id_cnt)
									  ;
									  
		//echo $select->__toString(); die;
		$result = $this->_db->fetchAll($select);
		return !empty($result);
	}
	
	public function hasCmpLinks($id_cnt) {
		$select = $this->_db->select()->from('cmp_has_cnt', 'id_cmp')
									  ->where('id_cnt = ?', $id_cnt)
									  ;
									  
		//echo $select->__toString(); die;
		$result = $this->_db->fetchAll($select);
		return !empty($result);
	}
} // end of class

