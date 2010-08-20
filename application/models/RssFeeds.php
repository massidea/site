<?php
class Default_Model_RssFeeds extends Zend_Db_Table_Abstract {
    // Table name
    protected $_name = 'rss_feeds_rss';
    
    // Table primary key
    protected $_primary = 'id_rss';

    // Table reference map
    protected $_referenceMap    = array(
        'RssPagetype' => array(
        	'columns'		    => array('type_rss'),
        	'refTableClass'		=> 'Default_Model_PageTypes',
        	'refColumns'		=> array('type_ptp')
        )
        // TEST END
    );	
    
    public function getUrls($id = 0, $type = 0) {
    	if ($type == 0 || $id == 0 ) return false;
    	$select = $this->select()->from($this, 'url_rss')
    							 ->where("type_rss = ?", $type)
    							 ->where("id_target_rss = ?", $id)
    							 ;

    	$result = $this->fetchAll($select)->toArray();
    	return $result;
    }
    
    public function addUrls($urls, $id, $type) {
    	
    	$this->removeFeeds($id, $type);
    	foreach ($urls as $url) {
    		if (!$this->feedExists($url, $id, $type)) {
	    		$rssUrl = $this->createRow();
	    		$rssUrl->url_rss = $url;
	    		$rssUrl->id_target_rss = $id;
	    		$rssUrl->type_rss = $type;
	    		$rssUrl->created_rss = new Zend_Db_Expr('NOW()');
                $rssUrl->modified_rss = new Zend_Db_Expr('NOW()');
	    		$rssUrl->save();
    		}
    	}
    }
    
    public function removeFeeds($id, $type) {
    	$stmt = $this->_db->prepare("DELETE FROM ". $this->_name . " WHERE id_target_rss = ? and type_rss = ?");
		$stmt->execute(array($id, $type));    	
    }
    
    public function feedExists($url, $id, $type) {
    	$select = $this->select()->from($this, 'id_rss')
    							 ->where("type_rss = ?", $type)
    							 ->where("id_target_rss = ?", $id)
    							 ->where("url_rss = ?", $url);
    							 
    	$result = $this->fetchAll($select)->toArray();
    	if (isset($result[0])) return true;
    	return false;
    }
}