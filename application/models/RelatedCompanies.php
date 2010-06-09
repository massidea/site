<?php
/**
 *  Related companies -> Related companies database model for Related companies table.
 *
 *  Copyright (c) <2009>, Pekka Piispanen
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
 *  Related companies - class
 *
 *  @package    models
 *  @author     Pekka Piispanen
 *  @license    GPL v2
 *  @version    1.0
 */ 
class Default_Model_RelatedCompanies extends Zend_Db_Table_Abstract
{
    // Table name
    protected $_name = 'related_companies_rec';

    // Table primary key
    protected $_primary = 'id_rec';

    // Table dependet tables
    protected $_dependentTables = array('Default_Model_ContentHasRelatedCompany');

    /**
    *   relCompExists
    *
    *   Check if related company exists in database.
    *
    *   @param string $relComp name of related company to be checked
    *   @return boolean
    */
    public function relCompExists($relComp)
    {
        // Select related company with given name
        $select = $this->select()->where('name_rec = ?', $relComp);
        
        // Find all matching related companies
        $result = $this->fetchAll($select)->toArray();
        
        return empty($result);
    } // end of relCompExists

    /**
    *   getRelComp
    *
    *   Get related company info and return an array containing related company data
    *
    *   @param string $relComp name of related company to be fetched
    *   @return array
    */
    public function getRelComp($relComp)
    {
        // Select related company by name
        $select = $this->select()->where('name_rec = ?', $relComp)
                                 ->limit(1);
                                 
        $row = $this->fetchAll($select)->current();
        
        return $row;
    } // end of getRelComp

    /**
    *   createRelComp
    *
    *   Adds a given related company to database and returns the created row
    *
    *   @param string $relComp name of related company that will be created
    *   @return array
    */
    public function createRelComp($relComp)
    {
        // Create new empty row
        $row = $this->createRow();
        
        // Set related company data
        $row->name_rec = $relComp;
        
        $row->created_rec = new Zend_Db_Expr('NOW()');
        $row->modified_rec = new Zend_Db_Expr('NOW()');
        
        // Save data to database
        $row->save();
        
        return $row;
    } // end of createRelComp

    /**
    *   getContentByRelCompId
    *
    *   Gets content by related company id value.
    *
    *   @param integer $id
    *   @return array
    */
    public function getContentByRelCompId($id = 0)
    {
        $data = array();
        
        if ($id != 0)
        {
            // Find content row by id
            $rowset = $this->find((int)$id)->current();
        
            $data = $rowset->findManyToManyRowset('Default_Model_Content', 'Default_Model_ContentHasRelatedCompany')->toArray();
            /*
            echo '<pre>';
            print_r($data);
            echo '</pre>';
            */
        } // end if
        
        return $data;
    } // end of getContentByRelCompId

    /**
    *   getAll
    *
    *   Gets all related companies
    *
    *   @return array
    */
    public function getAll()
    {
        return $this->fetchAll();
    } // end of getAll
    
    /** 
    *   removeRelComp
    *   Removes the related company from the database
    *   The related company is removed when it is not used with any content
    *   
    *   @param int id_rec
    *   @author Pekka Piispanen
    */
    public function removeRelComp($id_rec = 0)
    {
        $where = $this->getAdapter()->quoteInto('id_rec = ?', $id_rec);
        if ($this->delete($where)) {
            return true;
        } else {
            return false;
        }
    } // end of removeRelComp
    
    /**
    * addRelatedCompaniesToContent
    *
    */
    public function addRelatedCompaniesToContent($contentId = -1, array $companyArray = array(), $companyExisting = null)
    {
        $result = false;
    
        if(!empty($companyArray) && $contentId != -1) {
            $cntHasRec = new Default_Model_ContentHasRelatedCompany();
                    
            foreach($companyArray as $id => $company) {
                $recFound = false;
                
                // Check if company is already added for this content
                if($companyExisting != null) {
                    foreach($companyExisting as $existingCompany) {
                        if($company == $existingCompany['name_rec']) {
                            $recFound = true;
                        }
                    }
                }
                
                // If company is not already associated with current content
                if(!$recFound) {
                    // Check if given keyword does not exists in database
                    if($this->relCompExists($company)) {
                        // Create new keyword
                        $company = $this->createRelComp($company);
                    } else {
                        // Get company
                        $company = $this->getRelComp($company);
                    } // end else
                    
                    // Add companies to content
                    $cntHasRec->addRelCompToContent($company->id_rec, $contentId);
                }
            } // end foreach 
        
            $result = true;
        }
        
        return $result;
    }
    
} // end of class