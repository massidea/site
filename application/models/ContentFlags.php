<?php
/**
 *  ContentFlags -> ContentFlags database model for content flagging table.
 *
* 	Copyright (c) <2010>, Jaakko Paukamainen
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
 *  ContentFlags - class
 *
 *  @package 	models
 *  @author 	Jaakko Paukamainen
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class Default_Model_ContentFlags extends Zend_Db_Table_Abstract
{
	// Table name
    protected $_name = 'content_flags_cfl';
    
	// Table primary key
	protected $_primary = 'id_cfl';
	
	// Table reference map
	protected $_referenceMap    = array(
        'ContentFlagging' => array(
            'columns'           => array('id_content_cfl'),
            'refTableClass'     => 'Default_Model_Content',
            'refColumns'        => array('id_cfl')
        ),
		'ContentFlaggingUser' => array(
            'columns'           => array('id_user_cmr'),
            'refTableClass'     => 'Default_Model_User',
            'refColumns'        => array('id_usr')
        ),
    );
    
    /**
    *   addFlag
    *   
    *   Adds content flag to database
    *
    *   @param  idCnt   integer     Id of content to flag
    *   @param  idUsr   integer     Id of user who is doing the flagging
    */
    public function addFlag($idCnt = 0, $idUsr = 0)
    {
        if ((int)$idCnt == 0 || (int)$idUsr == 0) {
            return -1;
        }
        
		$newRow = $this->createRow();
        
		$newRow->id_content_cfl = $idCnt;
		$newRow->id_user_cfl = $idUsr;
		$newRow->flag_cfl = 1;
		$newRow->modified_cfl = new Zend_Db_Expr('NOW()');
		$newRow->created_cfl = new Zend_Db_Expr('NOW()');

		return $newRow->save();
    }
    
   /*
    *   flagExists
    *   
    *   Checks if flag exists
    *
    *   @param  idCnt   integer     Id of content that we check
    *   @param  idUsr   integer     Id of flag owner
    *   @return         boolean     success
    */
    public function flagExists($idCnt = 0, $idUsr = 0) 
    {
        if ((int)$idCnt == 0 || (int)$idUsr == 0) {
            return false;
        }
        
        $select = $this->select()
            ->where('id_content_cfl = ?', (int)$idCnt)
            ->where('id_user_cfl = ?', (int)$idUsr)
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
    	$select = $this->select('id_content_cfl')
                       ->where('flag_cfl','1');
        
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
    public function getFlagsByContentId($id_cnt)
    {
    	$select = $this->select()
                       ->from('content_flags_cfl', array('id_cfl'))
                       ->where('id_content_cfl = ?', (int)$id_cnt);

		$results = $this->getAdapter()->fetchAll($select);
                $finalresult = '';
		foreach ($results as $result)
		{
			$finalresult[] = $result['id_cfl'];
		}
		//$this->getAdapter()->
        
    	return $finalresult;//['id_cmf'];
    }
    
    /** 
    *   removeFlag
    *   Removes specified flag
    *   
    *   @param		int		id_cfl	Id of the flag
    *   @author		Jaakko Paukamainen
    */
    public function removeFlag($id_cfl)
    {
        $where = $this->getAdapter()->quoteInto('id_cfl = ?', $id_cfl);
        if ($this->delete($where)) {
            return true;
        } else {
            return false;
        }
    }
} // end of class