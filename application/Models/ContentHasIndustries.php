<?php
/**
 *  ContentHasIndustries -> ContentHasIndustries database model for content has industries link table.
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
 *  ContentHasIndustries - class
 *
 *  @package 	models
 *  @author 		Markus Riihelä & Mikko Sallinen
 *  @copyright 	2009 Markus Riihelä & Mikko Sallinen
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class Models_ContentHasIndustries extends Zend_Db_Table_Abstract
{
	// Table name
    protected $_name = 'cnt_has_ind';
	
	// Table reference map
	protected $_referenceMap    = array(
        'IndustryContent' => array(
            'columns'           => array('id_cnt'),
            'refTableClass'     => 'Models_Content',
            'refColumns'        => array('id_cnt')
        ),
		 'IndustryIndustry' => array(
            'columns'           => array('id_ind'),
            'refTableClass'     => 'Models_Industries',
            'refColumns'        => array('id_ind')
        )
    );
	
	/**
	*	addIndustryToContent
	*
	*	Add industry to content
	*
	*	@param integer $id_cnt
	*	@param integer $id_ind
	*/
	public function addIndustryToContent($id_cnt = 0, $id_ind = 0)
	{
		// If id values not 0
		if($id_cnt != 0 && $id_ind != 0)
		{
			// Create a new row
			$row = $this->createRow();
			
			// Set id values
			$row->id_cnt = $id_cnt;
			$row->id_ind = $id_ind;
			
			// Add row to database
			$row->save();
		} // end if
	} // end of addIndustryToContent
    
    /** 
    *   removeIndustriesFromContent
    *   Removes industries from content
    *   
    *   @param int id_cnt Id of the content
    *   $return bool $return
    *   @author Pekka Piispanen
    */
    public function removeIndustriesFromContent($id_cnt)
    {
        $return = false;
    
        $where = $this->getAdapter()->quoteInto('id_cnt = ?', $id_cnt);
        if($this->delete($where))
        {
            $return = true;
        }
        
        return $return;
    } // end of removeIndustriesFromContent
    
    public function getIndustryIdOfContent($id_cnt)
    {
        $select = $this->select()
				->from($this, array('id_ind'))
				->where("`id_cnt` = $id_cnt");
        
		$result = $this->fetchAll($select)->toArray();
        
        return $result[0]['id_ind'];
    }
    
    public function updateIndustryToContent($id_ind, $id_cnt)
    {
        $return = false;
    
        $id_ind = (int)$id_ind;
        $id_cnt = (int)$id_cnt;
    
        $data = array('id_ind' => $id_ind);
		$where = $this->getAdapter()->quoteInto('id_cnt = ?', $id_cnt);
        
        if($this->update($data, $where))
        {
            $return = true;
        }
        return $return;
    }
} // end of class
?>