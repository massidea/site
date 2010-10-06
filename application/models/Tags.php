<?php
/**
 *  Tags -> Tags database model for tags table.
 *
 *  Copyright (c) <2009>, Markus Riihel�
 *  Copyright (c) <2009>, Mikko Sallinen
 *  Copyright (c) <2009>, Joel Peltonen
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
 * License text found in /license/
 */

/**
 *  Tags - class
 *
 *  @package    models
 *  @author     Markus Riihel�
 *  @author     Mikko Sallinen
 *  @author     Joel Peltonen
 *  @license    GPL v2
 *  @version    1.0
 */ 
class Default_Model_Tags extends Zend_Db_Table_Abstract
{
    // Table name
    protected $_name = 'tags_tag';

    // Table primary key
    protected $_primary = 'id_tag';

    // Table dependet tables
    protected $_dependentTables = array('Default_Model_ContentHasTag');
    
    protected $_referenceMap    = array(
		'TagTag' => array(
            'columns'           => array('id_tag'),
            'refTableClass'     => 'Default_Model_ContentHasTag',
            'refColumns'        => array('id_tag')
        )
    );
	
/*
    protected $_referenceMap    = array(
        'TagContents' => array(
            'columns'           => array('id_cnt'),
            'refTableClass'     => 'Default_Model_Content',
            'refColumns'        => array('id_cnt')
        )
    );
*/

    /**
    *   tagExists
    *
    *   Check if keyword exists in database.
    *
    *   @param string $tag name of tag to be checked
    *   @return boolean
    */
    public function tagExists($tag)
    {
        // Select tags with given name
        $select = $this->select()->where('name_tag = ?', $tag);
        
        // Find all matching tags
        $result = $this->fetchAll($select)->toArray();
        
        return empty($result);
    } // end of tagExists

    /**
    *   getTag
    *
    *   Get tag info and return an array containing tag data
    *
    *   @param string $tag name of tag to be fetched
    *   @return array
    */
    public function getTag($tag)
    {
        // Select tag by name
        $select = $this->select()->where('name_tag = ?', $tag)
                                 ->limit(1);
                                 
        $row = $this->fetchAll($select)->current();
        
        return $row;
    } // end of getTag

    /**
    *   createTag
    *
    *   Adds a given tag to database and returns the created row
    *
    *   @param string $tag name of tag that will be created
    *   @return array
    */
    public function createTag($tag)
    {
        // Create new empty row
        $row = $this->createRow();
        
        // Set tag data
        $row->name_tag = $tag;
        $row->views_tag = 0;
        
        $row->created_tag = new Zend_Db_Expr('NOW()');
        $row->modified_tag = new Zend_Db_Expr('NOW()');
        
        // Save data to database
        $row->save();
        
        return $row;
    } // end of createTag

    /**
    *   getTagContentById
    *
    *   Gets content by tag id value.
    *
    *   @param integer $id
    *   @return array
    */
    public function getTagContentById($id = 0)
    {
        $data = array();
        
        if ($id != 0) {
            $query = $this->_db
                          ->select()
                          ->from(array('tag' => 'tags_tag'),
                                array()
                            )
                          ->joinLeft(array('cht' => 'cnt_has_tag'), 
                                'cht.id_tag = tag.id_tag', 
                                array('*')
                            )
                          ->joinLeft(array('cnt' => 'contents_cnt'), 
                                'cnt.id_cnt = cht.id_cnt', 
                                array('cnt.id_cnt', 
                                    'cnt.title_cnt', 
                                    'cnt.lead_cnt'
                                )
                            )
                          ->joinLeft(array('cty' => 'content_types_cty'), 
                                'cty.id_cty = cnt.id_cty_cnt',
                                array('cty.key_cty')
                            )
                          ->where('tag.id_tag = ?', $id);
                          
            $data = $this->_db->fetchAll($query);
            
            // Find content row by id
            //$rowset = $this->find((int)$id)->current();
        
            //$data = $rowset->findManyToManyRowset('Default_Model_Content', 'Default_Model_ContentHasTag')->toArray();
            /*
            echo '<pre>';
            print_r($data);
            echo '</pre>';
            */
        } // end if
        
        return $data;
    } // end of getTagContentById

    /**
    *   getAll
    *
    *   Gets all tags
    *
    *   @return array
    */
    public function getAll()
    {
        return $this->fetchAll();
    } // end of getAll

    /**
    *   @param a string to include into where statement
    *   @return String of Where statement
    *   @see getTagCloudData()
    *   @author joel peltonen
    */
    private function makeWhereForFilter($a){
        // just in case this was passed the all parameter in an unfamiliar way
        // avoid idiocy
        if ($a != 'all' && $a != 'All' && $a != 'ALL' && $a != 'other') {
            // like is case insensitive...
            return 'tag.name_tag LIKE("'.$a.'%")';
        } else if ($a == 'other') {
            return 'tag.name_tag LIKE("1%") 
                    OR tag.name_tag LIKE("2%")
                    OR tag.name_tag LIKE("3%")
                    OR tag.name_tag LIKE("4%")
                    OR tag.name_tag LIKE("5%")
                    OR tag.name_tag LIKE("6%")
                    OR tag.name_tag LIKE("7%")
                    OR tag.name_tag LIKE("8%")
                    OR tag.name_tag LIKE("9%")
                    OR tag.name_tag LIKE("0%")
                    OR tag.name_tag LIKE("+%")
                    OR tag.name_tag LIKE("-%")
                    OR tag.name_tag LIKE(".%")
                    OR tag.name_tag LIKE("\\%")
                    OR tag.name_tag LIKE("\_%")
                    OR tag.name_tag LIKE("\t%")
                    OR tag.name_tag LIKE("\0%")
                    ';
                    
        } else {
            return 1;
        }
    }
    
    /**
    *   getTagCloudData
    *
    *   Gets data for tag cloud
    *   
    *   @author joel peltonen
    *   @author ??
    *   @param limit how many results returned max - default 0
    *   @param order of results - default name
    *   @param direction ascending or descending - default DESC
    *   @param where limiter for beginning-of-word searches. use as single string statement made automatically. - default 1
    *   @param ctype content type as a string: all/problem/futureinfo/idea
    *   @return array of results
    *   @see makeWhereForFilter()
    */
    public function getTagCloudData($limit = 0, $order = null, $direction = "DESC", $where = 1, $ctype = "all")
    {
        // alphabetical where statement
        switch ($where) {
            case 1:
                break;  
            case "all":
                $where = 1;
                break;
            default: 
                // if any else, generate a where
                $where = $this->makeWhereForFilter($where);
        }
    
        // direction parameter -- sort ascending, descending
        switch ($direction) {
            case 'desc':
                $direction = "DESC";
                break;
            case 'descending':
                $direction = "DESC";
                break;
            case 'acsending': 
                $direction = "ASC";
                break;
            case 'asc':
                $direction = "ASC";
                break;
            default:
                $direction = "DESC";
        }

        // Tag cloud filter selection, moved from controller
        switch ($order) {
            case 'name':
                $order = 'tag.name_tag ' . $direction;
                break;
            case 'creation': 
                $order = 'tag.created_tag ' . $direction;
                break;
			case 'modified':
                $order = 'tag.modified_tag ' . $direction;
                break;
            case 'popularity':
                $order = 'count ' . $direction;
                break;
            case 'length':
                $order = 'LENGTH(tag.name_tag) ' . $direction;
                break;
			case 'ID':
                $order = 'tag.id_tag ' . $direction;
                break;
            default:
                $order = 'tag.name_tag ' . $direction;
                break;
        }
        
        // format content type filtering
         switch ($ctype) {
            case 'all': break;
            case 'problem':
            case 'problems':
                $ctype = "3";
                break;
            case 'idea':
            case 'ideas':
                $ctype = "2";
                break;
            case 'finfo':
            case 'futureinfo':
                $ctype = "1";
                break;
            default: 
                $ctype = "all";
                break;
         }
        
        // generate select
        if ($ctype == "all") {
            $select = $this->_db->select()->from(array('cht' => 'cnt_has_tag'),
                                                 array('cht.id_tag', 'count' => 
                                                       'COUNT(cht.id_tag)'))
                                          ->join(array('tag' => 'tags_tag'),
                                                 'tag.id_tag = cht.id_tag',
                                                 array('tag.id_tag', 'tag.name_tag'))
                                          ->where($where)
                                          ->limit($limit)
                                          ->group('tag.id_tag')
                                          ->order($order);
        } else {
            $select = $this->_db->select()->from(array('cht' => 'cnt_has_tag'),
                                                 array('cht.id_tag', 'count' => 
                                                       'COUNT(cht.id_tag)'))
                                          ->join(array('tag' => 'tags_tag'),
                                                 'tag.id_tag = cht.id_tag',
                                                 array('tag.id_tag', 'tag.name_tag'))
                                          ->join(array('cnt' => 'contents_cnt'),
                                                 'cnt.id_cnt = cht.id_cnt',
                                                 array('cnt.id_cnt', 'cnt.id_cty_cnt'))
                                          ->where('cnt.id_cty_cnt = ?', $ctype)
                                          ->where($where)
                                          ->limit($limit)
                                          ->group('tag.id_tag')
                                          ->order($order);
        }
        
        // get results
        $tagList = $this->_db->fetchAll($select);

        // return results
        return $tagList;
    } // end getTagCloudData
    
    /**
    * gets the most used tags
    * @param limit how many - default 10
    * @author Joel Peltonen, Jari Korpela
    */
    public function getPopular($limit = 10){
        $select = $this->_db->select()
                            ->from(array('cht' => 'cnt_has_tag'),
                                   array('cht.id_tag', 
                                         'count' => 'COUNT(cht.id_tag)'
                                )
                            )
                            ->join(array('tag' => 'tags_tag'),
                                   'tag.id_tag = cht.id_tag',
                                   array('tag.id_tag', 'tag.name_tag')
                            )
                            ->limit($limit)
                            ->order('count desc')
                            ->group('tag.id_tag');

        // get results
        $tagList = $this->_db->fetchAll($select);
        
        $name = array();
        $count = array();
	    foreach ($tagList as $key => $row) {
		    $name[$key]  = mb_strtolower($row['name_tag']);
		    $count[$key] = $row['count'];
		    $tagList[$key]['rank'] = $key;
		}
		array_multisort($name, SORT_ASC, $count, SORT_DESC, $tagList);
        // return results
        return $tagList;
    }
    
   /**
    * gets the most used tags with certain content type
    * @param limit how many - default 10
    * @author Joel Peltonen
    */
    public function getPopularByType($cty = 'idea', $limit = 20)
    {
        $select = $this->_db->select()->from(array('cht' => 'cnt_has_tag'),
                                             array('cht.id_tag', 
                                                   'cht.id_cnt', 
                                                   'count' => 'COUNT(cht.id_tag)'))
                                    ->joinLeft(array('tag' => 'tags_tag'),
                                               'tag.id_tag = cht.id_tag',
                                               array('tag.id_tag', 'tag.name_tag'))
                                    ->joinLeft(array('cnt' => 'contents_cnt'),
                                               'cnt.id_cnt = cht.id_cnt',  
                                               array('cnt.id_cty_cnt'))
                                    ->joinLeft(array('cty' => 'content_types_cty'),
                                               'cty.id_cty = cnt.id_cty_cnt',
                                               array('cty.key_cty'))
                                    ->where('cty.key_cty = ?', $cty)
                                    ->limit($limit)
                                    ->group('tag.id_tag')
                                    ;
                                    
        // get results
        $tagList = $this->_db->fetchAll($select);

        // return results
        return $tagList;
    }
    
    /** 
    *   getTagNameById
    *   Retrieves tag name from database based on id, 
    *   This is not used anywhere, for a good reason, but I'll leave it in anyway
    *   
    *   @param    id  integer id of tag
    *   @return strig result
    */
    public function getTagNameById($id = -1)
    {
        $select = $this->select()
                        ->from(array('tags_tag' => 'tags_tag'), array('name_tag'))
                        ->where('id_tag = ?', $id)
                        ->limit(1);
								 
		$result = $this->_db->fetchAll($select);
        
        return $result[0]['name_tag'];
    } // end of getTagNameById
    
    /** 
    *   getIdByTagName
    *   Retrieves tag id from database based on tag, 
    *   
    *   @param name_tag string name of tag
    *   @return integer result
    */
    public function getIdByTagName($name_tag = "")
    {
        $select = $this->select()
                        ->from(array('tags_tag' => 'tags_tag'), array('id_tag'))
                        ->where('name_tag = ?', $name_tag)
                        ->limit(1);
								 
		$result = $this->_db->fetchAll($select);
        return $result[0]['id_tag'];
    } // end of getTagNameById
    
    /** 
    *   removeTag
    *   Removes the tag from the database
    *   The tag is removed when it is not used with any content
    *   
    *   @param int id_tag
    *   @author Pekka Piispanen
    */
    public function removeTag($id_tag = 0)
    {
        $where = $this->getAdapter()->quoteInto('id_tag = ?', $id_tag);
        if ($this->delete($where)) {
            return true;
        } else {
            return false;
        }
    } // end of removeTag
    
    /**
    * addTagsToContent
    *
    * @param array tagArray
    * @param int contentId
    */
    public function addTagsToContent($contentId = -1, array $tagArray = array(), $tagExisting = null)
    {
        $result = false;
    
        if(!empty($tagArray) && $contentId != -1) {
            $chtModel = new Default_Model_ContentHasTag();
                    
            foreach($tagArray as $id => $tag) {
                $tagFound = false;
                
                // Check if tag is already added for this content
                if($tagExisting != null) {
                    foreach($tagExisting as $existingTag) {
                        if($tag == $existingTag['name_tag']) {
                            $tagFound = true;
                        }
                    }
                }
                
                // If tag is not already associated with current content
                if(!$tagFound) {
                    // Check if given keyword does not exists in database
                    if($this->tagExists($tag)) {
                        // Create new keyword
                        $tag = $this->createTag($tag);
                    } else {
                        // Get keyword
                        $tag = $this->getTag($tag);
                    } // end else
                    
                    // Add keywords to content
                    $chtModel->addTagToContent($tag->id_tag, $contentId);
                }
            } // end foreach 
        
            $result = true;
        }
        
        return $result;
    }
} // end of class
