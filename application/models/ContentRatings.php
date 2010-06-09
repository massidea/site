<?php
/**
 *  ContentRatings -> ContentRatings database model for content ratings table.
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
 *  License text found in /license/
 */

/**
 *  ContentRatings - class
 *
 *  @package        models
 *  @author         Markus Riihel� & Mikko Sallinen & Joel Peltonen
 *  @copyright      2009 Markus Riihel� & Mikko Sallinen & Joel Peltonen
 *  @license        GPL v2
 *  @version        1.0
 */ 
class Default_Model_ContentRatings extends Zend_Db_Table_Abstract
{
    // Table name
    protected $_name = 'content_ratings_crt';
    
    // Table primary key
    protected $_primary = 'id_crt';

    // Table reference map
    protected $_referenceMap    = array(
        'RatingsContent' => array(
            'columns'           => array('id_cnt_crt'),
            'refTableClass'     => 'Default_Model_Content',
            'refColumns'        => array('id_cnt')
        ),
        'RatingsUser' => array(
            'columns'           => array('id_usr_crt'),
            'refTableClass'     => 'Default_Model_Content',
            'refColumns'        => array('id_usr')
        ),
    );
    
    /**
    * getById
    * 
    * Gets rating by given id value.
    *
    * @author Joel Peltonen
    * @param integer $id Id value of content, which rating is wanted.
    * @return int rating
    */
    public function getById($id = 0)
    {
        $return = 0;
    
        // Find rating
        $select = $this->select()->where('id_cnt_crt = ?', $id);
        $rowset = $this->fetchAll($select);

        if(count($rowset) == 1) {
            $rating_data = $rowset->toArray();
            $return = $rating_data['0']['rating_crt'];
        } else if (count($rowset) >> 1) {
            foreach ($rowset->toArray() as $row) {
                $return += $row['rating_crt'];
            }
        } else {
            $return = 0;
        }

        return $return;
    }
    
    /**
    * getPercentagesById
    *
    * @author Joel Peltonen
    * @param content Id which owns the ratings
    * @return array with results
    */
    public function getPercentagesById($id = 0) 
    {
        $return['positive'] = 0;
        $return['negative'] = 0;
        $return['total'] = 0;
        $return['positive_percentage'] = 0;
        $return['negative_percentage'] = 0;
        
        // get all ratings for content
        $select = $this->select()->where('id_cnt_crt = ?', $id);
        $rowset = $this->fetchAll($select);
        
        if (count($rowset) > 0) {
            // count positive, negative and total ratings
            foreach ($rowset->toArray() as $row) {
                if ($row['rating_crt'] > 0) {
                    $return['positive'] += 1;
                    $return['total'] += 1;
                } else if ($row['rating_crt'] < 0) {
                    $return['negative'] += 1;
                    $return['total'] += 1;
                }
            }
            
            // calc percentages
            if ($return['positive'] >> 0) {
                $return['positive_percentage'] = ceil(($return['positive']/
                                                       $return['total'])*100);
            }

            if ($return['negative'] >> 0) {
                $return['negative_percentage'] = floor(($return['negative']/
                                                        $return['total'])*100);
            }
        }
        return $return;
    }
    
    /**
    *   addRating
    *   
    *   Adds rating to database or modifies existing
    *
    *   @author Joel Peltonen
    *   @param  idCnt   integer     Id of content to rate
    *   @param  idUsr   integer     Id of user who is doing the rating
    *   @param  rating  integer     Rating value (+1 or -1)
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
        
        // Generate new rating row or modify old one
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

    /**
    *   removeContentRatings
    *   Removes specified rating
    *
    *   @param		int		id_cnt_crt	Id of the content
    *   @author		Mikko Korpinen
    */
    public function removeContentRatings($id_cnt_crt)
    {
        $where = $this->getAdapter()->quoteInto('id_cnt_crt = ?', $id_cnt_crt);
        if ($this->delete($where)) {
            return true;
        } else {
            return false;
        }
    }

} // end of class
?>