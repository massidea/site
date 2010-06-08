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
	
	private $_googleApiTranslateUrl = 'http://ajax.googleapis.com/ajax/services/language/translate';
	private $_googleApiDetectUrl = 'http://ajax.googleapis.com/ajax/services/language/detect';
	private $_googleApiVersion = '1.0';
	private $_langFrom;
	private $_langTo;
	private $_translateString;
	private $_detectString;
	private $_response;
	private $_errorLog;
	
	/*
	 * public function setLangFrom()
	 * 
	 * Sets language to translate from
	 * 
	 * @param	string	from	Translate language from
	 */
	public function setLangFrom($from = null)
	{
		if($from != null) $this->_langFrom = $from;
		return $this;
	}
	
	/*
	 * public function setLangTo()
	 * 
	 * Sets language to translate into
	 * 
	 * @param	string	from	Translate language to
	 */
	public function setLangTo($to = null)
	{
		if($to != null) $this->_langTo = $to;
		return $this;
	}
	
	/*
	 * public function setLangPair()
	 * 
	 * Sets language pair
	 * 
	 * @param	string	from	Translate language from
	 * @param	string	to		Translate language to
	 */
	public function setLangPair($from = null, $to = null)
	{
		if($from != null)	$this->_langFrom = $from;
		if($to != null) 	$this->_langTo = $to;
		return $this;
	}
	
	/*
	 * public function switchLangs()
	 * 
	 * Switches language pair places (from -> to, to -> from)
	 */
	public function switchLangs()
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
		// Reformat string for maintaining all newlines (\n -> <newrow />)
		$this->_translateString = str_replace("\n", "<newrow />", $string);
		if ($this->_isValid()) {
			// Use cache for translated strings
			$cache = Zend_Registry::get('cache');
			$hash = $this->_generateHash();
			$cacheName = 'GTranslate_'.$hash;
			if(!($result = $cache->load($cacheName)))
			{
				$langpair = $this->_langFrom."|".$this->_langTo;
				$client = new Zend_Http_Client($this->_googleApiTranslateUrl, array(
	                        'maxredirects' => 0,
	                        'timeout'      => 30));
	
				$client->setParameterGet(array(
	                        'v' => $this->_googleApiVersion,
	                        'q' => $this->_translateString,
	                        'langpair' => $langpair
				));
				
				$response = $client->request();
				$data = $response->getBody();
				$server_result = json_decode($data);
				
				$responseCode = $server_result->responseStatus;
				$responseDetails = $server_result->responseDetails;

				if($responseCode == 200)
				{
					$this->_debugLog[] = array( 'sentString'	=> $this->_translateString,
												'receiveString'	=> $server_result->responseData->translatedText);
					
					$result = $server_result->responseData->translatedText;
				
					// Reformat string back to original
					$result = str_replace("<newrow />","\n", $result);
				}
				else
				{
					$this->_errorLog[] = array( 'string' 			=> $string,
												'responseStatus'	=> $responseCode,
												'responseDetails'	=> $responseDetails);
				}

				// If translation fails (incompatible language pair), return original string
				if(!isset($result)) $result = $this->_translateString;
				$cache->save($result, $cacheName);
			}
			return $result;

		} else {
			// If translation query is invalid
			return $string;
		}
	}
	
	/*
	 * public function detectLanguage()
	 * 
	 * Detect language from given string
	 * 
	 * @param	detectString	string	String to detect
	 * @return					string	Detected language
	 */
	public function detectLanguage($string)
	{
			// Reformat string for maintaining all newlines (\n -> <newrow />)
			$this->_detectString = str_replace("\n", "<newrow />", $string);
			
			$client = new Zend_Http_Client($this->_googleApiDetectUrl, array(
                        'maxredirects' => 0,
                        'timeout'      => 30));

			$client->setParameterGet(array(
                        'v' => $this->_googleApiVersion,
                        'q' => $this->_detectString
			));
			
			$response = $client->request();
			$data = $response->getBody();
			$server_result = json_decode($data);
			$responseCode = $server_result->responseStatus;
			$responseDetails = $server_result->responseDetails;
			
			if($responseCode == 200)
			{
				$this->_response[] = array( 'string'		=> $this->_detectString,
											'language'		=> $server_result->responseData->language,
											'isReliable'	=> $server_result->responseData->isReliable,
											'confidence'	=> $server_result->responseData->confidence);
			}
			else
			{
				$this->_errorLog[] = array( 'responseStatus'	=> $responseCode,
											'responseDetails'	=> $responseDetails);
			}
			
			$detected = $server_result->responseData->language;
			return $detected;
	}
	
	/*
	 * public function translateArray()
	 * 
	 * Translates arrays (values only)
	 * 
	 * @param	array	array	Array to translate
	 * @return			array	Translated array
	 */
	public function translateArray($array)
	{
		$returnArray = array();
		foreach($array as $key => $value)
			$returnArray[$key] = $this->translate($value);
		return $returnArray;
	}
	
	/*
	 * public function translateContent()
	 * 
	 * Translates content data arrays (values only).
	 * Excludes keys which are not defined in $includeList.
	 * 
	 * @param	array	array	Array to translate
	 * @return			array	Translated array
	 */
	public function translateContent($array)
	{
		$includeList = array('title_cnt', 
							 'lead_cnt', 
							 'body_cnt', 
							 'research_question_cnt',
							 'opportunity_cnt', 
							 'threat_cnt', 
							 'solution_cnt', 
							 'references_cnt');
		
		foreach($array as $key => $value)
		{
			if(in_array($key, $includeList)) $array[$key] = $this->translate($value);
		}
		
		return $array;
	}
	
	/*
	 * private function _generateHash()
	 * 
	 * Generates a MD5-hash for identifying a cached translation
	 * 
	 * @return		string	MD5-hash
	 */
	private function _generateHash()
	{
		$rawHash = $this->_langFrom . $this->_langTo . $this->_translateString;
		return md5($rawHash);
	}
	
	/* 
	 * private function _isValid()
	 * 
	 * Checks if all data is valid.
	 * 
	 * @return		boolean	
	 */
	private function _isValid()
	{
		return ($this->_areNotNullOrEmpty($this->_translateString,
										  $this->_langFrom, 
										  $this->_langTo) &&
				($this->_langFrom != $this->_langTo));
	}
	
	/*
	 * private function _areNotNullOrEmpty
	 * 
	 * Pretty self explainatory... Accepts multiple arguments.
	 * 
	 * @params	strings		Strings to validate
	 * @return	boolean		Valid/Invalid
	 */
	private function _areNotNullOrEmpty()
	{
		$args = func_get_args();
		$numOfArgs = func_num_args();
		$success = 0;
		foreach($args as $arg)
			if($arg != '' && $arg != null) $success++;
		if($numOfArgs == $success) return 1;
		else return 0;
	}
	
	/*
	 * public function getResponseData()
	 * 
	 * Return response data.
	 * 
	 * @return		array	Response data
	 */
	public function getResponseData()
	{
		return $this->_response;
	}

	/*
	 * public function getErrorData()
	 * 
	 * Return error data.
	 * 
	 * @return		array	error data
	 */
	public function getErrorData()
	{
		return $this->_errorLog;
	}

}
?>