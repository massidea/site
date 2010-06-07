<?php
/**
 *  GTranslate - Get translations from Google Translate service (Google AJAX API)
 *
 *   Copyright (c) <2010>, Jaakko Paukamainen <jaakko.paukamainen@student.samk.fi>
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
 *  GTranslate - class
 *
 *  @package    plugins
 *  @author     Jaakko Paukamainen
 *  @copyright  2010 Jaakko Paukamainen
 *  @license    GPL v2
 *  @version    1.0
 */ 
class Oibs_Controller_Plugin_GTranslate {
	
	private $_langFrom;
	private $_langTo;
	private $_queryString;
	private $_error;
	
	public function __construct()
	{
		$this->_error = false;
	}
	
	/*
	 * public function setLangPair()
	 * 
	 * Sets language pair
	 * 
	 * @param	string	from	Translate language from
	 * @param	string	to		Translate language to
	 */
	public function setLangPair($from, $to)
	{
		$this->_langFrom = $from;
		$this->_langTo = $to;
		return $this;
	}
	
	/*
	 * public function switchLang()
	 * 
	 * Switches language pair places (from -> to, to -> from)
	 */
	public function switchLang()
	{
		$langHelpVar = $this->_langTo;
		$this->_langTo = $this->_langFrom;
		$this->_langFrom = $langHelpVar;
	}
	
	/*
	 * public function translate()
	 * 
	 * Executes the translation and returns the result
	 * 
	 * @param	queryString	string	String to translate
	 * @return				string	Translated string
	 */
	public function translate($string) {
		$this->_queryString = $string;
		if ($this->_isValid()) {
			$cache = Zend_Registry::get('cache');
			$hash = $this->_generateHash();
			$cacheName = 'GTranslate_'.$hash;
			if(!($result = $cache->load($cacheName)))
			{
				$client = new Zend_Http_Client('http://ajax.googleapis.com/ajax/services/language/translate', array(
	                        'maxredirects' => 0,
	                        'timeout'      => 30));
	
				$client->setParameterGet(array(
	                        'v' => '1.0',
	                        'q' => $this->_queryString,
	                        'langpair' => $this->_langFrom."|".$this->_langTo
				));
	
				$response = $client->request();
				$data = $response->getBody();
				$server_result = json_decode($data);
	
				//$status = $server_result->responseStatus; // should be 200
				
				//$details = $server_result->responseDetails;
				
				$result = $server_result->responseData->translatedText;
				// If translation fails (incompatible language pair), return original string
				if(!isset($result)) $result = $this->_queryString;
				$cache->save($result, $cacheName);
			}
			return $result;

		} else {
			return "GTranslation error";
		}
	}
	
	/*
	 * private function _generateHash()
	 * 
	 * Generates a md5-hash for identifying a cached translation
	 */
	private function _generateHash()
	{
		$rawHash = $this->_langFrom . $this->_langTo . $this->_queryString;
		return md5($rawHash);
	}
	
	/* private function _isValid()
	 * 
	 * Checks if all data is valid and no errors have been put up. 
	 */
	private function _isValid() {
		if($this->_langFrom == null) $this->_error = true;
		if($this->_langTo == null) $this->_error = true;
		
		return !$this->_error;
	}

}
?>