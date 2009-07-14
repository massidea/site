<?php
/**
 *  Tags -> Tags database model for tags table.
 *
 *  Copyright (c) <2009>, Markus Riihelä
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
 *  @author     Markus Riihelä & Mikko Sallinen
 *  @copyright  2009 Markus Riihelä & Mikko Sallinen
 *  @license    GPL v2
 *  @version    1.0
 */ 
class Models_Tags extends Zend_Db_Table_Abstract
{
    // Table name
    protected $_name = 'tags_tag';

    // Table primary key
    protected $_primary = 'id_tag';

    // Table dependet tables
    protected $_dependentTables = array('Models_ContentHasTag');
/*
    protected $_referenceMap    = array(
        'TagContents' => array(
            'columns'           => array('id_cnt'),
            'refTableClass'     => 'Models_Content',
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
        $row->name_tag = htmlentities($tag);
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
        
        if ($id != 0)
        {
            // Find content row by id
            $rowset = $this->find((int)$id)->current();
        
            $data = $rowset->findManyToManyRowset('Models_Content', 'Models_ContentHasTag')->toArray();
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
    *   getTagCloudData
    *
    *   Gets data for tag cloud
    *
    *   @return array
    */
    public function getTagCloudData()
    {
        $tag_list = $this->getAll()->toArray();
        $i = 0;
        
        $cht = new Models_ContentHasTag();
        
        foreach($tag_list as $tag)
        {
            $select = $cht->select()->from($cht, 'COUNT(id_tag) AS tag_count')
                                     ->where('id_tag = ?', $tag['id_tag']);
                                     
            $tag_list[$i]['count'] = $this->fetchAll($select)->current()->toArray();
            $i++;
        } // end foreach
        /*
        echo '<pre>';
        print_r($tag_list);
        echo '</pre>';
        */
        return $tag_list;
    } // end getTagCloudData
    
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
        $this->delete($where);
    } // end of removeTag
} // end of class
?>