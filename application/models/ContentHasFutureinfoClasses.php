<?php
/**
 *  ContentHasFutureinfoClasses -> ContentHasFutureinfoClasses database model for content has future info classes link table.
 *
 * 	Copyright (c) <2009>, Pekka Piispanen
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
 *  ContentHasFutureinfoClasses - class
 *
 *  @package 	models
 *  @author 	Pekka Piispanen
 *  @copyright 	2009 Pekka Piispanen
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class Default_Model_ContentHasFutureinfoClasses extends Zend_Db_Table_Abstract
{
	// Table name
    protected $_name = 'cnt_has_fic';
	
	// Table refence map
	protected $_referenceMap    = array(
        'FutureinfoClassContent' => array(
            'columns'           => array('id_cnt'),
            'refTableClass'     => 'Default_Model_Content',
            'refColumns'        => array('id_cnt')
        ),
		 'FutureinfoClassFutureinfoClass' => array(
            'columns'           => array('id_fic'),
            'refTableClass'     => 'Default_Model_InnovationTypes',
            'refColumns'        => array('id_fic')
        )
    );
	
	/**
	*	addFutureinfoClassToContent
	*
	*	Add innovation type to content
	*
	*	@param integer $id_cnt
	*	@param integer $id_fic
	*/
	public function addFutureinfoClassToContent($id_cnt = 0, $id_fic = 0)
	{
		// If id values not 0
		if($id_cnt != 0 && $id_fic != 0)
		{
			// Create a new row
			$row = $this->createRow();
			
			// Set id values
			$row->id_cnt = $id_cnt;
			$row->id_fic = $id_fic;
			
			// Add row to database
			$row->save();
		} // end if
	} // end of addFutureinfoClassToContent
    
    /** 
    *   removeFutureinfoClassesFromContent
    *   Removes future info classes from content
    *   
    *   @param int id_cnt Id of the content
    *   @return bool $return
    *   @author Pekka Piispanen
    */
    public function removeFutureinfoClassesFromContent($id_cnt)
    {
        $return = false;
    
        $where = $this->getAdapter()->quoteInto('id_cnt = ?', $id_cnt);
        if($this->delete($where))
        {
            $return = true;
        }
        
        return $return;
    } // end of removeFutureinfoClassesFromContent
    
    public function getFutureinfoClassIdOfContent($id_cnt)
    {
        $select = $this->select()
				->from($this, array('id_fic'))
				->where('`id_cnt` = ?', $id_cnt);

		$result = $this->fetchAll($select)->toArray();
        
        if (!empty($result)) {
            return $result[0]['id_fic'];
        }
        
        return false;
    }
    
    public function updateFutureinfoClassToContent($id_fic = 0, $id_cnt = 0)
    {
        $return = false;
        
        $id_fic = (int)$id_fic;
        $id_cnt = (int)$id_cnt;
        
        $data = array('id_fic' => $id_fic);			
		$where = $this->getAdapter()->quoteInto('id_cnt = ?', $id_cnt);
        
        if($this->update($data, $where))
        {
            $return = true;
        }
        return $return;
    }
} // end of class
?>