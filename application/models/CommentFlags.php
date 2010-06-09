<?php
/**
 *  CommentFlags -> CommentFlags database model for comment flagging table.
 *
* 	Copyright (c) <2009>, Jaakko Paukamainen
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
 *  CommentRatings - class
 *
 *  @package 	models
 *  @author 	Jaakko Paukamainen
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class Default_Model_CommentFlags extends Zend_Db_Table_Abstract
{
	// Table name
    protected $_name = 'comment_flags_cmf';
    
	// Table primary key
	protected $_primary = 'id_cmf';
	
	// Table reference map
	protected $_referenceMap    = array(
        'CommentFlagging' => array(
            'columns'           => array('id_comment_cmf'),
            'refTableClass'     => 'Default_Model_Comment',
            'refColumns'        => array('id_cmf')
        ),
		'CommentFlaggingUser' => array(
            'columns'           => array('id_usr_cmr'),
            'refTableClass'     => 'Default_Model_User',
            'refColumns'        => array('id_usr')
        ),
    );
    
    /**
    *   addFlag
    *   
    *   Adds comment flag to database
    *
    *   @param  idCmt   integer     Id of comment to flag
    *   @param  idUsr   integer     Id of user who is doing the flagging
    */
    public function addFlag($idCmt = 0, $idUsr = 0)
    {
        if ((int)$idCmt == 0 || (int)$idUsr == 0) {
            return -1;
        }
        
		$newRow = $this->createRow();
        
		$newRow->id_comment_cmf = $idCmt;
		$newRow->id_user_cmf = $idUsr;
		$newRow->flag_cmf = 1;
		$newRow->modified_cmf = new Zend_Db_Expr('NOW()');
		$newRow->created_cmf = new Zend_Db_Expr('NOW()');

		return $newRow->save();
    }
    
   /*
    *   flagExists
    *   
    *   Checks if flag exists
    *
    *   @param  idCmt   integer     Id of comment that is checked
    *   @param  idUsr   integer     Id of flag owner
    *   @return         boolean     success
    */
    public function flagExists($idCmt = 0, $idUsr = 0) 
    {
        if ((int)$idCmt == 0 || (int)$idUsr == 0) {
            return false;
        }
        
        $select = $this->select()
            ->where('id_comment_cmf = ?', (int)$idCmt)
            ->where('id_user_cmf = ?', (int)$idUsr)
        ;
        
        $result = $this->fetchAll($select)->toArray();
        
        if (count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
    *
    *
    */
    public function getAllFlags()
    {
    	$select = $this->select('id_comment_cmf')
                       ->where('flag_cmf','1');
        
    	$result = $this->fetchAll($select)->toArray();
        
    	return $result;
    }
    
    /**
    *
    *
    */
    public function getListOfFlags()
    {
    	$select = $this->select()
    					->where('');
    }
    
    /**
    *
    *
    */
    public function getFlagsByCommentId($id_cmt)
    {
    	$select = $this->select()
                       ->from('comment_flags_cmf', array('id_cmf'))
                       ->where('id_comment_cmf = ?', (int)$id_cmt);

		$results = $this->getAdapter()->fetchAll($select);
		foreach ($results as $result)
		{
			$finalresult[] = $result['id_cmf'];
		}
		//$this->getAdapter()->
        
    	return $finalresult;//['id_cmf'];
    }

    /**
    *   getFlagsByContentId
    *   Get all comment flags by content id
    *
    *   @param		int		id_cnt	Id of the content
    *   @author		Mikko Korpinen
    */
    public function getFlagsByContentId($id_cnt)
    {
    	$select = $this->_db->select()
                       ->from(array('cmf' => 'comment_flags_cmf'), array('id_cmf'))
                       ->joinLeft(array('cmt' => 'comments_cmt'),
                               'cmf.id_comment_cmf = cmt.id_cmt')
                       ->where('cmt.id_cnt_cmt = ?', (int)$id_cnt);

		$results = $this->getAdapter()->fetchAll($select);
        $finalresult = '';
        foreach ($results as $result) {
            $finalresult[] = $result['id_cmf'];
        }
        return $finalresult;
    }
    
    /** 
    *   removeFlag
    *   Removes specified flag
    *   
    *   @param		int		id_cmf	Id of the flag
    *   @author		Jaakko Paukamainen
    */
    public function removeFlag($id_cmf)
    {
        $where = $this->getAdapter()->quoteInto('id_cmf = ?', $id_cmf);
        if ($this->delete($where)) {
            return true;
        } else {
            return false;
        }
    }
} // end of class