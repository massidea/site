<?php
/**
 *  RssReader
 *
 *   Copyright (c) <2010>, Sami Suuriniemi <sami.suuriniemi@samk.fi>
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
 *  RssReader - class
 *
 *  @package    plugins
 *  @author     Sami Suuriniemi
 *  @copyright  2010 Sami Suuriniemi
 *  @license    GPL v2
 *  @version    1.0
 */ 
 
class Oibs_Controller_Plugin_RssReader {
	private $_limit = 10;
	
    public function read($id, $type) {
    	$rssModel = new Default_Model_RssFeeds();
    	$pageTypeModel = new Default_Model_PageTypes();
    	$type = $pageTypeModel->getId($type);
    	$urls = $rssModel->getUrls($id, $type);

    	$feeds = array();
    	foreach($urls as $url) {
	    	try {
		    	$feed = Zend_Feed_Reader::import($url['url_rss']);
		    	//echo $feed->getEncoding();
		    	$feeds[] = $feed;
	    	} catch (Exception $e) {
	    		echo "Error with feed";
	    	}
    	}
    	if (count($feeds) != 0) $data = $this->sortFeed($feeds);
    	else return false;
    	
    	return $data;
    }
    
    private function sortFeed($channels) {
    	$feedData = array();
    	foreach ($channels as $channel) {
	
	    	$feedData['titles'][] = $channel->getTitle();
	    	$i = 0;
	    	foreach ($channel as $item) {
	    		$tempItem = array();
	    		$tempItem['title'] = $item->getTitle();
	    		$tempItem['link'] =  $item->getLink();
				$tempItem['desc'] = $item->getContent();
				$tempItem['date'] = $item->getDateCreated()->get();
	    		$feedData['items'][] = $tempItem;
	    		$i++;	
        		if ($i >= $this->_limit/count($channels)) break;

	    	}
    	}
		usort($feedData['items'], array('Oibs_Controller_Plugin_RssReader', 'cmp'));
	    return $feedData;
    }
	private function cmp($a, $b) {
    	if ($a['date'] == $b['date']) return 0;
    	return $a['date'] > $b['date'] ? -1 : 1;
    }
    
}
    
