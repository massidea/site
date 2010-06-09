<?php
/**
 *  ContentHasRelatedCompany -> ContentHasRelatedCompany database model for table cnt_has_rec
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
 *  ContentHasRelatedCompany - class
 *
 *  @package 	models
 *  @author 	Pekka Piispanen
 *  @copyright 	2009 Pekka Piispanen
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class Default_Model_ContentHasRelatedCompany extends Zend_Db_Table_Abstract
{
	// Table name
    protected $_name = 'cnt_has_rec';
	
	// Table reference map
	protected $_referenceMap    = array(
        'RelCompContent' => array(
            'columns'           => array('id_cnt'),
            'refTableClass'     => 'Default_Model_Content',
            'refColumns'        => array('id_cnt')
        ),
		'RelCompRelComp' => array(
            'columns'           => array('id_rec'),
            'refTableClass'     => 'Default_Model_RelatedCompanies',
            'refColumns'        => array('id_rec')
        )
    );
	
	/**
	*	addRelCompToContent
	*
	*	Add specified related company to specified content.
	*
	*	@param integer $id_rec
	*	@param integer $id_cnt
	*/
	public function addRelCompToContent($id_rec = 0, $id_cnt = 0)
	{
		// If related company id and content id is not 0
		if($id_rec != 0 && $id_cnt != 0)
		{
			// Create a new row
			$row = $this->createRow();
			
			// Set id values
			$row->id_cnt = $id_cnt;
			$row->id_rec = $id_rec;
			
			// Add row to database
			$row->save();
		} // end if
	} // end of addRelCompToContent
    
    public function getContentRelComps($id_cnt = 0)
    {
        $select =  $this->_db->select()
                        ->from(array('cnt_has_rec' => 'cnt_has_rec'), array('id_rec'))
                        ->where('id_cnt = ?', $id_cnt)
                        ->join(array('related_companies_rec' => 'related_companies_rec'), 'related_companies_rec.id_rec = cnt_has_rec.id_rec', array('name_rec'))
        ;
        $result = $this->_db->fetchAll($select);

        return $result;
    } // end of getContentRelComps
    
    /**
	*	checkIfOtherContentHasRelComp
	*
	*	This function checks, if other content(s) have specified related company when
    *   deleting content. If no other content uses the specified related company, the 
    *   entire related company can be deleted from the system
	*
    *   @param int $id_rec The related company to check
	*	@param int $id_cnt The content which is going to be deleted
    *   @return bool
    *   @author Pekka Piispanen
	*/
    public function checkIfOtherContentHasRelComp($id_rec = 0, $id_cnt = 0)
    {
        $return = false;
        
        $select = $this->select()
                        ->where('id_rec = ?', $id_rec)
                        ->where('id_cnt != ?', $id_cnt);
                        
        $result = $this->fetchAll($select)->toArray();
        
        if(count($result) != 0)
        {
            $return = true;
        }
        
        return $return;
    }
    
    /**
	*	removeContentRelComps
	*
	*	Remove related companies from specified content
	*
	*	@param integer $id_cnt
    *   @return bool $return
    *   @author Pekka Piispanen
	*/
	public function removeContentRelComps($id_cnt = 0)
    {
        $return = false;
        
        $where = $this->getAdapter()->quoteInto('id_cnt = ?', $id_cnt);
        if($this->delete($where))
        {
            $return = true;
        }
        
        return $return;
    } // end of removeContentRelComps
    
    /**
    *
    *
    */
    public function deleteRelCompFromContent($id_rec = 0, $id_cnt = 0)
    {
        $return = false;
        
        $where = array();
        
        $where[] = $this->getAdapter()->quoteInto('id_rec = ?', $id_rec);
        $where[] = $this->getAdapter()->quoteInto('id_cnt = ?', $id_cnt);

        if($this->delete($where))
        {
            $return = true;
        }
        
        return $return;
    }
    
    /**
    *
    *
    */
    public function checkExistingCompanies($contentId = -1, array $companies = array())
    {
        $result = null;
        
        if($contentId != -1 && !empty($companies)) {
            // Go through all existing related companies
            $existingRecs = $this->getContentRelComps($contentId);
            
            foreach($existingRecs as $id => $existingRec) {
                // If some of the existing related companies aren't found in sent
                // related companies, that related company is deleted from the 
                // content and maybe even from thedatabase
                if(!in_array($existingRec['name_rec'], $companies)) {
                    // Removing rec from content
                    $this->deleteRelCompFromContent($existingRec['id_rec'], $contentId);
                    
                    // If other content(s) doesn't have this related company, the whole
                    // related company is going to be removed from the database
                    if(!$this->checkIfOtherContentHasRelComp($existingRec['id_rec'], $contentId)) {
                        $modelRecs = new Default_Model_RelatedCompanies();
                        $modelRecs->removeRelComp($existingRec['id_rec']);
                    }
                    
                    // Remove related company from existingRecs array
                    unset($existingRecs[$id]);
                }
            }
            
            $result = $existingRecs;
        }
        
        return $result;
    }
} // end of class
