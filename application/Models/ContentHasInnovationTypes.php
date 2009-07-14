<?php
/**
 *  ContentHasInnovationTypes -> ContentHasInnovationTypes database model for content has innovation types link table.
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
 *  ContentHasInnovationTypes - class
 *
 *  @package 	models
 *  @author 		Markus Riihelä & Mikko Sallinen
 *  @copyright 	2009 Markus Riihelä & Mikko Sallinen
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class Models_ContentHasInnovationTypes extends Zend_Db_Table_Abstract
{
	// Table name
    protected $_name = 'cnt_has_ivt';
	
	// Table refence map
	protected $_referenceMap    = array(
        'InnovationTypeContent' => array(
            'columns'           => array('id_cnt'),
            'refTableClass'     => 'Models_Content',
            'refColumns'        => array('id_cnt')
        ),
		 'InnovationTypeInnovationType' => array(
            'columns'           => array('id_ivt'),
            'refTableClass'     => 'Models_InnovationTypes',
            'refColumns'        => array('id_ivt')
        )
    );
	
	/**
	*	addInnovationTypeToContent
	*
	*	Add innovation type to content
	*
	*	@param integer $id_cnt
	*	@param integer $id_ivt
	*/
	public function addInnovationTypeToContent($id_cnt = 0, $id_ivt = 0)
	{
		// If id values not 0
		if($id_cnt != 0 && $id_ivt != 0)
		{
			// Create a new row
			$row = $this->createRow();
			
			// Set id values
			$row->id_cnt = $id_cnt;
			$row->id_ivt = $id_ivt;
			
			// Add row to database
			$row->save();
		} // end if
	} // end of addInnovationTypeToContent
    
    /** 
    *   removeInnovationTypesFromContent
    *   Removes innovation types from content
    *   
    *   @param int id_cnt Id of the content
    *   @return bool $return
    *   @author Pekka Piispanen
    */
    public function removeInnovationTypesFromContent($id_cnt)
    {
        $return = false;
    
        $where = $this->getAdapter()->quoteInto('id_cnt = ?', $id_cnt);
        if($this->delete($where))
        {
            $return = true;
        }
        
        return $return;
    } // end of removeIndustriesFromContent
    
    public function getInnovationTypeIdOfContent($id_cnt)
    {
        $select = $this->select()
				->from($this, array('id_ivt'))
				->where("`id_cnt` = $id_cnt");

		$result = $this->fetchAll($select)->toArray();
        
        return $result[0]['id_ivt'];
    }
    
    public function updateInnovationTypeToContent($id_ivt = 0, $id_cnt = 0)
    {
        $return = false;
        
        $id_ivt = (int)$id_ivt;
        $id_cnt = (int)$id_cnt;
        
        $data = array('id_ivt' => $id_ivt);			
		$where = $this->getAdapter()->quoteInto('id_cnt = ?', $id_cnt);
        
        if($this->update($data, $where))
        {
            $return = true;
        }
        return $return;
    }
} // end of class
?>