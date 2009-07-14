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
class Models_Comments extends Zend_Db_Table_Abstract
{
    // Table name
    protected $_name = 'comments_cmt';
    
    // Table primary key
    protected $_primary = 'id_cmt';

    // Table reference map
    protected $_referenceMap    = array(
        'CommentContent' => array(
            'columns'           => array('id_cnt_cmt'),
            'refTableClass'     => 'Models_Content',
            'refColumns'        => array('id_cnt')
        ),
        'CommentUser' => array(
            'columns'           => array('id_usr_cmt'),
            'refTableClass'     => 'Models_User',
            'refColumns'        => array('id_usr')
        ),
        // TEST START
        'CommentComment' => array(
            'columns'           => array('id_parent_cmt'),
            'refTableClass'     => 'Models_Comments',
            'refColumns'        => array('id_cmt')
        ),
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
        if(count($rowset) == 1)
        {
            // Comment data to array
            $comment_data = $rowset->toArray();	

            // Find dependet users
            $usr = $rowset->findDependentRowset('Models_User', 'CommentUser')->toArray();
            
            // If user found
            if(!empty($usr))
            {
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
    * @pram $id int id of content which comments are wanted
    * @author Joel Peltonen 
    * @return $data array array of data
    */
    public function getAllByContentId($id = 0)
    {
        $data = array();
        
        if ($id != -1) {
            $select = $this->_db->select()
                            ->from(array('comments_cmt' => 'comments_cmt'), array('*'))
                            ->where('id_cnt_cmt = ?', $id)
                            ->join(array('usr' => 'users_usr'), 'comments_cmt.id_usr_cmt = usr.id_usr', array('id_usr', 'login_name_usr'))
                            ->order('created_cmt DESC')
            ;

            $data = $this->_db->fetchAll($select);
        }
        
        return $data;
    }

    /**
    *   getByContent
    *
    *   Get comments by content.
    *
    *   @param $id Content id value.
    *   @return array
    */
    /*
    public function getByContent($id = 0)
    {
        // Array for comment data
        $data = array();
        
        // Select comments and users
        $select = $this->_db->select()
            ->from('comments_cmt', array('*'))
            ->joinInner('users_usr', 'id_cmt_usr = id_usr', array('*'))
            ->where('id_cnt = ?', $id)
            ->order('created_cmt ASC');
        
        $stmt = $this->_db->query($select);
        
        $result = $stmt->fetchAll();
        
        if(count($result) != 0)
        {
            $data = $result;
        }

        return $data;
    }
    */

    /**
    *   addComment
    *
    *   Add a new comment to content
    *   
    *   @param integer $content_id Content id value.
    *   @param integer $user_id User id value.
    *   @param array $data Comment form data.
    */
    public function addComment($content_id = 0, $user_id = 0, $data = null)
    {
        // Check if content id, user id and form data exists
        if ($content_id != 0 && $user_id != 0 && $data != null)
        {
            // Create a new row
            $comment = $this->createRow();
            
            // Set columns values
            $comment->id_cnt_cmt = $content_id;
            $comment->id_usr_cmt = $user_id;
            $comment->id_parent_cmt = 0;
            $comment->title_cmt = strip_tags($data['comment_subject']);
            $comment->body_cmt = strip_tags($data['comment_message']);
            
            $comment->created_cmt = new Zend_Db_Expr('NOW()');
            $comment->modified_cmt = new Zend_Db_Expr('NOW()');
            
            // Save row
            $comment->save();
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
                        ->where('id_cnt_cmt = ?', $id_cnt);
                        
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
        $where = $this->getAdapter()->quoteInto('id_cmt = ?', $id_cmt);
        $this->delete($where);
    }
} // end of class
?>