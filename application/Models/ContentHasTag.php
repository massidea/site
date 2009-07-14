<?php
/**
 *  ContentHasTag -> ContentHasInnovationTypes database model for content has tag link table.
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
 *  ContentHasTag - class
 *
 *  @package 	models
 *  @author 		Markus Riihelä & Mikko Sallinen
 *  @copyright 	2009 Markus Riihelä & Mikko Sallinen
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class Models_ContentHasTag extends Zend_Db_Table_Abstract
{
	// Table name
    protected $_name = 'cnt_has_tag';
	
	// Table reference map
	protected $_referenceMap    = array(
        'TagContent' => array(
            'columns'           => array('id_cnt'),
            'refTableClass'     => 'Models_Content',
            'refColumns'        => array('id_cnt')
        ),
		'TagTag' => array(
            'columns'           => array('id_tag'),
            'refTableClass'     => 'Models_Tags',
            'refColumns'        => array('id_tag')
        )
    );
	
	/**
	*	addTagToContent
	*
	*	Add specified tag/keyword to specified content.
	*
	*	@param integer $id_tag
	*	@param integer $id_cnt
	*/
	public function addTagToContent($id_tag = 0, $id_cnt = 0)
	{
		// If tag id and content id is not 0
		if($id_tag != 0 && $id_cnt != 0)
		{
			// Create a new row
			$row = $this->createRow();
			
			// Set id values
			$row->id_cnt = $id_cnt;
			$row->id_tag = $id_tag;
			
			// Add row to database
			$row->save();
		} // end if
	} // end of addTagToContent
    
    /** Joel Peltonen */
    public function getContentTags($id = 0)
    {
        $select =  $this->_db->select()
                        ->from(array('cnt_has_tag' => 'cnt_has_tag'), array('id_tag'))
                        ->where('id_cnt = ?', $id)
                        ->join(array('tags_tag' => 'tags_tag'), 'tags_tag.id_tag = cnt_has_tag.id_tag', array('name_tag'))
        ;
        $result = $this->_db->fetchAll($select);

        return $result;
    } // end of getContentTags
    
    /**
	*	checkIfOtherContentHasTag
	*
	*	This function checks, if other content(s) have specified tag when
    *   deleting content. If no other content uses the specified tag, the 
    *   entire tag can be deleted from the system
	*
    *   @param int $id_tag The tag to check
	*	@param int $id_cnt The content which is going to be deleted
    *   @return bool
    *   @author Pekka Piispanen
	*/
    public function checkIfOtherContentHasTag($id_tag = 0, $id_cnt = 0)
    {
        $return = false;
        
        $select = $this->select()
                        ->where('id_tag = ?', $id_tag)
                        ->where('id_cnt != ?', $id_cnt);
                        
        $result = $this->fetchAll($select)->toArray();
        
        if(count($result) != 0)
        {
            $return = true;
        }
        
        return $return;
    }
    
    /**
	*	removeContentTags
	*
	*	Remove tags from specified content
	*
	*	@param integer $id_cnt
    *   @return bool $return
    *   @author Pekka Piispanen
	*/
	public function removeContentTags($id_cnt = 0)
    {
        $return = false;
        
        $where = $this->getAdapter()->quoteInto('id_cnt = ?', $id_cnt);
        if($this->delete($where))
        {
            $return = true;
        }
        
        return $return;
    } // end of removeContentTags
    
    public function deleteTagFromContent($id_tag = 0, $id_cnt = 0)
    {
        $return = false;
        
        $where = array();
        
        $where[] = $this->getAdapter()->quoteInto('id_tag = ?', $id_tag);
        $where[] = $this->getAdapter()->quoteInto('id_cnt = ?', $id_cnt);

        if($this->delete($where))
        {
            $return = true;
        }
        
        return $return;
    }
} // end of class
?>