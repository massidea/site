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
}