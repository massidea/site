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
	
    public function read($url = "") {
    	try {
	    	$feed = Zend_Feed_Reader::import($url);
	    	$data = $this->sortFeed($feed);
	    	return $data;
    	} catch (Exception $e) {
    		echo "Error with feed";
    		return false;
    	} 
    	
    }
    
    private function sortFeed($channel) {
    	$feedData = array();
    	$feedData['title'] = $channel->getTitle();
    	$i = 0;
    	foreach ($channel as $item) {
    		$tempItem = array();
    		$tempItem['title'] = $item->getTitle();
    		$tempItem['link'] =  $item->getLink();
			$tempItem['desc'] = $item->getContent();
    		$feedData['items'][] = $tempItem;
    		if ($i >= $this->_limit) break;
    		$i++;
    		
    	}

    	return $feedData;
    }
}