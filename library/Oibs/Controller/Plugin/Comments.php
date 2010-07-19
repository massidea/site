<?php
class Oibs_Controller_Plugin_Comments {
	private $_type;
	private $_id;
	private $_userCanComment = false;
	private $_count = 10;
	private $_comments = array();
	private $_partial;
	
	/**
	 * __construct
	 * 
	 * @param $type		type of commentpage
	 * @param $id		id_target_cmt
	 */
	public function __construct($type=-1, $id=0) {
		$this->_type = $this->getTypeFromDatabase($type);
		$this->_id = $id;
		$this->_partial = "partials/comments.phtml";
		$this->_helper = new Zend_View_Helper_Url();
		$this->setUrls();
	}
	
	/**
	 * setType
	 * 
	 * sets the type
	 * 
	 * @param $type
	 */
	public function setType($type) {
		$this->_type = $this->getTypeFromDatabase($type);
		$this->setUrls();
		return $this;
	}
	
	/**
	 * setId
	 * 
	 * sets the id
	 * 
	 * @param unknown_type $id
	 */
	public function setId($id) {
		$this->_id = $id;
		$this->setUrls();
		return $this;
	}
	
	/**
	 * setUrls
	 * 
	 * sets _urls 
	 */
	private function setUrls() {
		if (!isset($this->_urls) && $this->isValid()) {
			$this->_urls['flagAsSpamUrl'] = $this->_helper->url(array('controller' => 'comment',
										  					 'action' => 'flag'),
										 					 'lang_default', true);
			$this->_urls['postCommentUrl'] = $this->_helper->url(array('controller' => 'ajax',
											   					'action' => 'postcomment',
											   					'id' => $this->_id,
											   					'type' => $this->_type),
											   					'lang_default', true);
			$this->_urls['getCommentsUrl'] = $this->_helper->url(array('controller' => 'ajax',
											   					'action' => 'getcomments',
											   					'id' => $this->_id,
											   					'type' => $this->_type),
											   					'lang_default', true);
		}
	}

	/**
	 * getUrls
	 * 
	 * gets the urls needed for javascripts
	 * 
	 * @return array Urls
	 */
	public function getUrls() {
		return $this->_urls;
	}
	
	/**
	 * getPartial
	 * 
	 * @return string partial to be used
	 */
	public function getPartial() {
		return $this->_partial;
	}
	
	/**
	 * getCommentCount
	 * 
	 * gets the amount of comments
	 * @return int
	 */
	public function getCommentCount() {
		return count($this->_comments);
	}
	
	/**
	 * getType
	 * 
	 * gets the type
	 * 
	 * @return int/string
	 */
	public function getType() {
		return $this->_type;
	}
	
	/**
	 * getId
	 * 
	 * returns id of the page
	 * @return int/string
	 */
	public function getId() {
		return $this->_id;
	}
	
	/**
	 * setCommentsPerPage
	 * 
	 * sets amount of comments per page, no functionality
	 * @param int $count
	 * @return this
	 */
	public function setCommentsPerPage($count) {
		$this->_count = $count;
		return $this;
	}
	
	/**
	 * loadComments
	 * 
	 * loads comments from database
	 */
	public function loadComments() {
		if ($this->isValid()) {
			$this->setLastUpdateTime();
			$this->_comments = $this->getCommentsFromDatabase();
			return true;
		} else {
			return false;
		}
	}

	/**
	 * addComment
	 * 
	 * adds a new comment to database
	 * 
	 * @param int $userId
	 * @param int $parent
	 * @param string $msg
	 */
	public function addComment($userId, $parent, $msg) {
		if ($this->isValid()) {
			$commentModel = new Default_Model_Comments();
			$commentModel->addComment($this->_type,  $this->_id, $userId, $parent, $msg);
		} else {
			echo "Invalid";
		}
	}

	/**
	 * allowComments
	 * 
	 * if user is allowed to comment
	 * 
	 * @param bool $value
	 */
	public function allowComments($value) {
		$this->_userCanComment = $value;
		return $this;
	}

	/**
	 * userCanComment
	 * 
	 * check if user can comment
	 * 
	 * @return bool
	 */
	public function userCanComment() {
		return $this->_userCanComment;
	}
	
	/**
	 * getNewComments
	 * 
	 * gets new comments frmo database
	 * 
	 * @param id $id_usr readers id
	 * @return unknown_type
	 */
	public function getNewComments($id_usr) {
		if ($this->isValid()) {
			$time = $this->getLastUpdateTime();
			return $this->getCommentsFromDatabase($id_usr, $time);
		} else {
			return false;
		}
	}
	
	/**
	 * getComments
	 * 
	 * gets all comments
	 * 
	 * @return unknown_type
	 */
	public function getComments() {
		return $this->_comments;
	}
	
	/**
	 * isValid
	 * 
	 * checks if the necessary data has been set
	 * @return bool
	 */
	private function isValid() {
		if ($this->_id == 0 || $this->_type == -1) {
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * getLastUpdateTime
	 * 
	 * gets the time user has last received comments 
	 * @return unixtime
	 */
	public function getLastUpdateTime() {
	    $session = new Zend_Session_Namespace();
    	if (!isset($session->comments) ) {
    		$session->comments = time();    	
    	}
    	return $session->comments;
	}

	/**
	 * setLastUpdateTime
	 * 
	 * sets the updatetime to current time
	 */
	private function setLastUpdateTime() {
		$session = new Zend_Session_Namespace();
		$session->comments = time();
	}
	
	/**
	 * getTypeFromDatabase
	 * 
	 * @param string $id, type of the comments
	 * @return int
	 */
	private function getTypeFromDatabase($id) {
		$commentTypeModel = new Default_Model_CommentTypes();
		return $commentTypeModel->getId($id);
	}
	
	/**
	 * getCommentsFromDatabase
	 * 
	 * gets the comments from database
	 * 
	 * @param int $id_usr, current user
	 * @param unixtime $time, time if checking for new comments
	 * @return array
	 */
	private function getCommentsFromDatabase($id_usr = 0, $time = 0) {
		$commentModel = new Default_Model_Comments();
		$comments = $commentModel->getComments($this->_type, $this->_id, $id_usr, $time);
		$comments = ($time == 0) ? $this->getCommentChilds($comments) : $comments;
		if(count($comments) != 0) $this->setLastUpdateTime();
		return $comments;
	}
    
	/**
	 * getCommentChilds
	 * 
	 * sorts the comments recursively to the tree format and sets the level
	 * 
	 * @param array $comments
	 * @param int $parent
	 * @param int $level
	 * @param int $maxLevel
	 * @return array
	 */
    private function getCommentChilds($comments, $parent=0, $level=0, $maxLevel=3) {
      	$result = array();
    	foreach ($comments as $comment) 
    	{
    		if($comment['id_parent_cmt'] == $parent) {
    			$comment['level'] =  $level > $maxLevel ? $maxLevel : $level;
    			$result[] = $comment;
    			$result = array_merge($result, $this->getCommentChilds($comments, $comment['id_cmt'], $level+1));
    		}
    	}   
    	return $result;	
    }
}