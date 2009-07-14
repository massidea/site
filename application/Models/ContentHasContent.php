<?php
/**
 *  ContentHasContent -> ContentHasContent database model for content has content link table.
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
 *  ContentHasContent - class
 *
 *  @package 	models
 *  @author 		Markus Riihelä & Mikko Sallinen
 *  @copyright 	2009 Markus Riihelä & Mikko Sallinen
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class Models_ContentHasContent extends Zend_Db_Table_Abstract
{
	// Table name
    protected $_name = 'cnt_has_cnt';
	
	// Table reference map
	protected $_referenceMap    = array(
        'ParentContent' => array(
            'columns'           => array('id_parent_cnt'),
            'refTableClass'     => 'Models_Content',
            'refColumns'        => array('id_cnt')
        ),  
		 'ChildContent' => array(
            'columns'           => array('id_child_cnt'),
            'refTableClass'     => 'Models_Content',
            'refColumns'        => array('id_cnt')
        ),
    );
	
	/**
	*	addContentToContent
	*
	*	Add content to be a child of another content
	*
	*	@param integer $id_parent_cnt
	*	@param integer $id_child_cnt
	*/
	public function addContentToContent($id_parent_cnt = 0, $id_child_cnt = 0)
	{
		// If id values not 0
		if($id_parent_cnt != 0 && $id_child_cnt != 0)
		{
			// Create a new row
			$row = $this->createRow();
			
			// Set id values
			$row->id_parent_cnt = $id_parent_cnt;
			$row->id_child_cnt = $id_child_cnt;
			
			// Add row to database
			$row->save();
		} // end if
	} // end of addContentToContent
    
    /*
    *   Get the parents and children  of content
    *   TODO: siblings
    *   
    *   @param  id      integer id of the content for which we want the tree
    *   @return return  array   array of the family tree
    */
    public function getContentFamilyTree($id = -1) 
    {
        $return = array();
    
        $selectParents = $this->_db->select()
                            ->from(array('cnt_has_cnt' => 'cnt_has_cnt'), array('id_parent_cnt'))
                            ->where('id_child_cnt = ?', $id)
        ;
        $parents = $this->_db->fetchAll($selectParents);
        
        $i = 0;
        foreach ($parents as $parent) {
            $return['parents'][$i] = $parent['id_parent_cnt'];
            
            /* Sibling finder ... not implemented yet, is this an insane way to do anything anyway?
              $selectSiblings = $this->_db->select()
                            ->from(array('cnt_has_cnt' => 'cnt_has_cnt'), array('*'))
                            ->where('id_parent_cnt = ?', $id)
              ;
              siblings = $this->_db->fetchAll($select);
              */
            
            $i++;
        }

        $selectChildren = $this->_db->select()
                            ->from(array('cnt_has_cnt' => 'cnt_has_cnt'), array('id_child_cnt'))
                            ->where('id_parent_cnt = ?', $id)
        ;
        $children = $this->_db->fetchAll($selectChildren);
        
        $i = 0;
        foreach ($children as $child) {
            $return['children'][$i] = $child['id_child_cnt'];
            $i++;
        }

        return $return;
    }
    
} // end of class
?>