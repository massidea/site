<?php
/**
 *  ContentRatings -> ContentRatings database model for content ratings table.
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
 *  License text found in /license/
 */

/**
 *  ContentRatings - class
 *
 *  @package        models
 *  @author         Markus Riihelä & Mikko Sallinen & Joel Peltonen
 *  @copyright      2009 Markus Riihelä & Mikko Sallinen & Joel Peltonen
 *  @license        GPL v2
 *  @version        1.0
 */ 
class Models_ContentRatings extends Zend_Db_Table_Abstract
{
    // Table name
    protected $_name = 'content_ratings_crt';
    
    // Table primary key
    protected $_primary = 'id_crt';

    // Table reference map
    protected $_referenceMap    = array(
        'RatingsContent' => array(
            'columns'           => array('id_cnt_crt'),
            'refTableClass'     => 'Models_Content',
            'refColumns'        => array('id_cnt')
        ),
        'RatingsUser' => array(
            'columns'           => array('id_usr_crt'),
            'refTableClass'     => 'Models_Content',
            'refColumns'        => array('id_usr')
        ),
    );
    
    /**
    *   getById
    *   
    *   Gets rating by given id value.
    *
    *   @param integer $id Id value of content, which rating is wanted.
    *   @return int rating
    */
    public function getById($id = 0)
    {
        $return = 0;
    
        // Find rating
        $select = $this->select()->where('id_cnt_crt = ?', $id);
        $rowset = $this->fetchAll($select);

        // If rating found, return simple value, not a gigantic rowset
        if(count($rowset) == 1) {
            $rating_data = $rowset->toArray();
            $return = $rating_data['0']['rating_crt'];
        } else {
            $return = 0;
        }

        return $return;
    }
    
    /**
    *   addRating
    *   
    *   Adds rating to database
    *
    *   @author Joel Peltonen
    *   @param  idCnt   integer     Id of content to rate
    *   @param  idUsr   integer     Id of user who is doing the rating
    *   @param rating   integer     $value rating value.. most likely +1 or -1
    *   @return         boolean     success
    */
    public function addRating($idCnt = 0, $idUsr = 0, $rating = 0)
    {
        if ((int)$idCnt == 0 || (int)$idUsr == 0) {
            return -1;
        }
        
        $select = $this->select()
            ->where('id_cnt_crt = ?', $idCnt)
            ->where('id_usr_crt = ?', $idUsr)
        ;
        $row = $this->fetchRow($select);
        
        // Modify old rating or generate new
        if (count($row) >> 0) {
            $row->modified_crt = new Zend_Db_Expr('NOW()');
            $row->rating_crt = (int)$rating;
            
            return $row->save();
        } else {
            $newRow = $this->createRow();
        
            $newRow->id_cnt_crt = (int)$idCnt;
            $newRow->id_usr_crt = (int)$idUsr;
            $newRow->rating_crt  = (int)$rating;
            $newRow->modified_crt = new Zend_Db_Expr('NOW()');
            $newRow->created_crt = new Zend_Db_Expr('NOW()');

            return $newRow->save();
        } 
    }
    
   /*
    *   ratingExists
    *   
    *   Checks if rating exists.. was used in addRating() at some point
    *
    *   TODO:   Modify rating
    *
    *   @author Joel Peltonen
    *   @param  idCnt   integer     Id of content that is checked
    *   @param  idUsr   integer     Id of rating owner
    *   @return         boolean     success
    */
    public function ratingExists($idCnt = 0, $idUsr = 0) 
    {
        if ((int)$idCnt == 0 || (int)$idUsr ==0) {
            return -1;
        }
        
        $select = $this->select()
            ->where('id_cnt_crt = ?', $idCnt)
            ->where('id_usr_crt = ?', $idUsr)
        ;
        $result = $this->fetchAll($select)->toArray();
        
        if (count($result) >> 0) {
            return true;
        } else {
            return false;
        }
    }
} // end of class
?>