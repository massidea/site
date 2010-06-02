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
	private $_string;
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
	 * @param	from	Translate language from
	 * @param	to		Translate language to
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
	 * 
	 * @param	none	none
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
	 * @param	query	String to translate
	 * @return	string	Translated string
	 */
	public function translate($query) {
		if ($this->_isValid()) {
			$client = new Zend_Http_Client('http://ajax.googleapis.com/ajax/services/language/translate', array(
                        'maxredirects' => 0,
                        'timeout'      => 30));

			$client->setParameterGet(array(
                        'v' => '1.0',
                        'q' => $query,
                        'langpair' => $this->_langFrom."|".$this->_langTo
			));

			$response = $client->request();

			$data = $response->getBody();

			$server_result = json_decode($data);

			$status = $server_result->responseStatus; // should be 200
			$details = $server_result->responseDetails;

			$result = $server_result->responseData->translatedText;
			return $result;

		} else {
			return "Translation error";
		}
	}
	
	/* isValid
	 * 
	 * checks if all data is valid and no errors have been put up. 
	 * REQUIRED to be ran before send()
	 */
	private function _isValid() {
		if($this->_langFrom == null) $this->_error = true;
		if($this->_langTo == null) $this->_error = true;
		
		return !$this->_error;
	}
	
//	/* getErrorMessage
//	 * 
//	 * returns possible errormessage
//	 * 
//	 * @return string	errormessage
//	 */
//	public function getErrorMessage() {
//		return $this->_errorMessage;
//	}
//	
//	/* _loadMessage
//	 * 
//	 * private method to handle loading of message from template
//	 */
//	private function _loadMessage() {
//		//
//		//$this->_subject = "uus kommentti";
//		$templateDir = "../library/Oibs/Emails/"; 
//		$file = $templateDir."notification_email_".$this->_notificationType.".txt";
//		
//		$message = split("\n", @file_get_contents($file), 2);
//
//		$this->_subject = $message[0];
//		
//		$this->_message = nl2br($message[1]);
//		 
//		if ($this->_message == "") {
//			$this->_errorMessage = "Error when opening file";
//		}
//	}

}
?>