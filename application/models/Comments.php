<?php
/**
 *  Comments -> Comments database model for comments table.
 *
 *  Copyright (c) <2009>, Markus Riihelä
 *  Copyright (c) <2009>, Mikko Sallinen
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
 *  Comments - class
 *
 *  @package    models
 *  @author     Markus Riihelä & Mikko Sallinen
 *  @copyright  2009 Markus Riihelä & Mikko Sallinen
 *  @license    GPL v2
 *  @version    1.0
 */ 
class Default_Model_Comments extends Zend_Db_Table_Abstract
{
    // Table name
    protected $_name = 'comments_cmt';
    
    // Table primary key
    protected $_primary = 'id_cmt';

    // Table reference map
    protected $_referenceMap    = array(
        'CommentUser' => array(
            'columns'           => array('id_usr_cmt'),
            'refTableClass'     => 'Default_Model_User',
            'refColumns'        => array('id_usr')
        ),
        // TEST START
        'CommentComment' => array(
            'columns'           => array('id_parent_cmt'),
            'refTableClass'     => 'Default_Model_Comments',
            'refColumns'        => array('id_cmt')
        ),
        'CommentCType' => array(
        	'columns'		    => array('type_cmt'),
        	'refTableClass'		=> 'Default_Model_PageTypes',
        	'refColumns'		=> array('type_ptp')
        )
        // TEST END
    );

    /**
    *   getById
    *   
    *   Gets comment by given id value.
    *
    *   @param integer $id Id value of comment.
    *   @return array
    */
    public function getById($id = 0)
    {
        // Array for comment data
        $data = array();
        
        // Find comment
        $rowset = $this->find((int)$id)->current();
        
        // If comment found
        if(count($rowset) == 1) {
            // Comment data to array
            $comment_data = $rowset->toArray();	

            // Find dependet users
            $usr = $rowset->findDependentRowset('Default_Model_User', 'CommentUser')->toArray();
            
            // If user found
            if(!empty($usr)) {
                $comment_owner = $usr[0];
            } // end if	
            
            // Comment data
            $data['Data'] = $comment_data;	
            $data['Poster'] = $comment_owner;		
        } // end if

        return $data;
    } // end of getById()
    
    /** 
    * gets all comments to a certain content by it's ID
    *
    * @param $id int id of content which comments are wanted
    * @author Joel Peltonen 
    * @return $data array array of data
    */
    public function getAllByContentId($id = 0, $page = 0, $count = 0)
    {
        $data = array();
        
        if ($id != -1) {
            $select = $this->_db->select()
                           ->from(array('comments_cmt' => 'comments_cmt'), 
                                  array('*'))
                           ->join(array('usr' => 'users_usr'), 
                                  'comments_cmt.id_usr_cmt = usr.id_usr', 
                                  array('id_usr', 'login_name_usr'))
                           ->where('id_target_cmt = ?', (int)$id)
                           ->where('type_cmt = 1')
                           ->order('created_cmt DESC')
                           ->limitPage((int)$page, (int)$count)
            ;

            $data = $this->_db->fetchAll($select);
        }
        
        return $data;
    }

    /**
    *   getCommentCountByContentId
    *
    *   Gets comment count by content id.
    *
    *   @param $id int Id of content
    *   @return int
    */
    public function getCommentCountByContentId($id = 0)
    {
        if ($id != 0) {
            $select = $this->_db->select()
                            ->from(array('comments_cmt'),
                                   array('commentCount' => 'COUNT(id_cmt)'))
                            ->where('id_target_cmt = ?', (int)$id)
                            ->where('type_cmt = 1');
                            
            $data = $this->_db->fetchAll($select);
        }
        
        return $data[0]['commentCount'];
    }
    
    /**
    *   getByContent
    *
    *   Get comments by content.
    *
    *   @param $id Content id value.
    *   @return array
    */
    public function getCommentsByContent($id = 0)
    {
        // Array for comment data
        $data = array();
        
        // Select comments and users
        $select = $this->_db->select()
            ->from('comments_cmt', array('*'))
            ->joinInner('users_usr',
                'id_usr_cmt = id_usr', 
                array('*')
            )
            ->where('id_target_cmt = ?', (int)$id)
            ->where('type_cmt = 1')
            //->where('id_parent_cmt = 0')
            ->order('created_cmt DESC');
        
        $stmt = $this->_db->query($select);
        
        $result = $stmt->fetchAll();
        
        if(count($result) != 0) {
            $data = $result;
        }

        return $data;
    }
    
    public function getComments($type, $id, $id_usr, $time) {
    	$select = $this->select()
    					   ->setIntegrityCheck(false)	
    					   ->from($this, array('*'))
    					   ->joinInner(	'users_usr', 'id_usr_cmt = id_usr',
    					   			array('id_usr', 'login_name_usr'))
    					   ->where('id_target_cmt = ?', $id)
    					   ->where('type_cmt = ?' , $type)
    					   ->order('created_cmt DESC')
    					   ;
    	//Zend_Debug::dump($select->__toString());
   		if ($time != 0) {
   			$select->where('created_cmt >= from_unixtime('.$time.') and id_usr != '.$id_usr);
   			$select->orWhere('created_cmt > from_unixtime('.$time.') and id_usr = '.$id_usr);
   		}
		$result = $this->fetchAll($select);
		//Zend_Debug::dump($result->toArray()); die;
		return $result->toArray();
    }
    
    /**
    *   addComment
    *
    *   Add a new comment to content
    *   
    *   @param integer $content_id Content id value.
    *   @param integer $user_id User id value.
    *   @param array $data Comment form data.
    */
    public function addComment($type = 0, $content_id = 0, $user_id = 0, $parent = 0, $data = null)
    {

    	
    	echo $type.$content_id.$user_id.$parent.$data;
        // Check if content id, user id and form data exists
        if ($type != 0 && $content_id != 0 && $user_id != 0 && $data != null) {
            // Create a new row
            $comment = $this->createRow();
            
            // Remove line breaks from the beginning of the message
            //while (substr($data['comment_message'], 0, strlen(PHP_EOL)) == PHP_EOL) {
            //    $data['comment_message'] = substr($data['comment_message'], 2);
            //}
            
            // Set columns values
            $comment->id_target_cmt = $content_id;
            $comment->type_cmt = $type;
            $comment->id_usr_cmt = $user_id;
            $comment->id_parent_cmt = $parent;
            $comment->title_cmt = '';//strip_tags($data['comment_subject']);
            $comment->body_cmt = strip_tags($data);
            
            // Check if there's still characters left in the message
            if (strlen($comment->body_cmt) > 0) {
                $comment->created_cmt = new Zend_Db_Expr('NOW()');
                $comment->modified_cmt = new Zend_Db_Expr('NOW()');
                
                // Save row
                $comment->save();
            }
        } // end if
    } // end of addComment

    /**
    *   getCommentIdsByContentId
    *   Fetchs all comment ids related to content
    *   
    *   @param int id_cnt The id of the content
    *   @return array $result Query results
    *   @author Pekka Piispanen
    */
    public function getCommentIdsByContentId($id_cnt = 0)
    {
        $select = $this->_db->select()
                        ->from('comments_cmt', array('id_cmt'))
                        ->where('id_target_cmt = ?', (int)$id_cnt)
                        ->where('type_cmt = ?', $this->getCommentType("content"));
                        
        $result = $this->_db->fetchAll($select);
        
        return $result;
    }
    
    public function getContentIdsByCommentId($id_cmt = 0)
    {
    	$type = $this->getCommentType("content");
        $select = $this->_db->select('id_target_cmt')
                        ->from('comments_cmt', array('id_target_cmt'))
                        ->where('id_cmt = ?', (int)$id_cmt)
                        ->where('type_cmt = ?', $type);
                        
        $result = $this->_db->fetchAll($select);
        
        return $result;
    }
    
    /** 
    *   removeComment
    *   Removes specified comment
    *   
    *   @param int id_cmt Id of the comment
    *   @author Pekka Piispanen
    */
    public function removeComment($id_cmt)
    {
        $where = $this->getAdapter()->quoteInto('id_cmt = ?', (int)$id_cmt);
        if ($this->delete($where)) {
            return true;
        } else {
            return false;
        }
    }

    /**
    *   removeCommentText
    *   Writes Comment removed into specified comment
    *
    *   @param int id_cmt Id of the comment
    *   @author Mikko Korpinen
    */
    public function removeCommentText($id_cmt)
    {
        $data = array(
            "body_cmt" => "Comment removed",
            "modified_cmt" => new Zend_Db_Expr('NOW()')
        );
        $where = $this->getAdapter()->quoteInto('id_cmt = ?', (int)$id_cmt);
        $this->update($data, $where);
    }

    /**
    *   removeAllContentComments
    *   Removes all content comments
    *
    *   @param int id_cnt Id of the content
    *   @author Mikko Korpinen
    */
    public function removeAllContentComments($id_cnt)
    {
    	$ptModel = new Default_Model_PageTypes();
        $where[] = $this->getAdapter()->quoteInto('id_target_cmt = ?', (int)$id_cnt);
        $where[] = $this->getAdapter()->quoteInto('type_cmt = ?', $ptModel->getId('content'));
        return $this->delete($where);
    }
    
    /**
    *   commentExists
    *
    *   Check if a comment exists
    *   
    *   @param	integer	$comment_id	Comment id value.
    *   @return	boolean	True if exists, false if not.
    */
    public function commentExists($comment_id = 0)
    {
        // Check if $comment_id exists
        if ($comment_id != 0) {
	        $select = $this->select()
	            ->where('id_cmt = ?', (int)$comment_id)
	        ;
            
	        $row = $this->fetchRow($select);
            
            if($row == NULL)
            	return false;
            elseif($row != NULL)
            	return true;
            	
        } // end if
    } // end of flagComment

    /**
     * userIsOwner - Return true if user is comment owner
     *
     * @author Mikko Korpinen
     * @param int $id_usr_cmt
     * @param int $id_cmt
     * @return boolean
     */
    public function userIsOwner($id_usr_cmt, $id_cmt)
    {
        $select = $this->select()
                       ->from($this, array('id_cmt'))
                       ->where('id_usr_cmt = ?', $id_usr_cmt)
                       ->where('id_cmt = ?', $id_cmt)
                       ->limit(1);

        $result = $this->_db->fetchAll($select);

        if(isset($result[0]) && !empty($result[0])) {
            return true;
        }

        return false;
    }

	private function getCommentType($type) {
		$ptpModel = new Default_Model_PageTypes();
		return $ptpModel->getId($type);
	}
} // end of class