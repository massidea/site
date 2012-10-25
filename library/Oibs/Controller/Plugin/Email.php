<?php
/**
 *  Email
 *
 *   Copyright (c) <2010>, Sami Suuriniemi <sami.suuriniemi@student.samk.fi>
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
 *  Email - class
 *
 *  @package    plugins
 *  @author     Sami Suuriniemi
 *  @copyright  2010 Sami Suuriniemi
 *  @license    GPL v2
 *  @version    1.0
 */ 
class Oibs_Controller_Plugin_Email {
	
	private $_errorMessage;
	private $_message;
	private $_sender;
	private $_receiver;
	private $_subject;

	private $_parameters = array();
	private $_notificationType;
	private $_validated;
		
	public function __construct() {
		$this->_errorMessage = "";
		$this->_validated = false;
	}

	/* setParameter
	 * 
	 * sets parameter on template to be replaced with value
	 * 
	 * @param 	key		TAG in template, without %%
	 * @param   value	value to replace TAG with
	 * @return 	this	returns this so you can chain '->' when using the method
	 */
	public function setParameter($key, $value) {
		$this->_parameters[$key] = $value; 
		return $this;
	}
	
	/* setSenderId
	 * 
	 * Sets sender id, required for email
	 * if fails to get something, puts error flag on 
	 * 
	 * @param id	senders id
	 */
	public function setSenderId($id) {
		$userModel = new Default_Model_User();
		$this->_sender = $userModel->getUserRow($id);
		if ($this->_sender == null) {
			$this->_errorMessage = "Error on sender id";
		}
		return $this;
	}

	/* setReceiverId
	 * 
	 * sets receiver id, required for email
	 * if fails to get something, puts error flag on
	 * 
	 * @param id	receivers id
	 */
	public function setReceiverId($id) {
		$userModel = new Default_Model_User();
		$this->_receiver = $userModel->getUserRow($id);
		if ($this->_receiver == null) {
			$this->_errorMessage = "Error on receiver id";
		}
		return $this;
	}
	
	/* setNotificationType
	 * 
	 * sets notifications type, used to get correct email template
	 * 
	 * @param type	notification type
	 */
	public function setNotificationType($type) {
		$this->_notificationType = $type;
		return $this;
	}
	
	/* isValid
	 * 
	 * checks if all data is valid and no errors have been put up. 
	 * REQUIRED to be ran before send()
	 */
	public function isValid() {
		$this->_loadMessage();
		$this->_replaceMessageParameters();

		if ($this->_errorMessage == "") {
			$this->_validated = true;
			return true;
		}
		return false;
	}
	
	/* getErrorMessage
	 * 
	 * returns possible errormessage
	 * 
	 * @return string	errormessage
	 */
	public function getErrorMessage() {
		return $this->_errorMessage;
	}
	
	/* _loadMessage
	 * 
	 * private method to handle loading of message from template
	 */
	private function _loadMessage() {
		//
		//$this->_subject = "uus kommentti";
		$templateDir = "../library/Oibs/Emails/"; 
		$file = $templateDir."notification_email_".$this->_notificationType.".txt";
		
		$message = explode("\n", @file_get_contents($file), 2);

        if ($this->_message == "") {
            $this->_errorMessage = "Error when opening file";
        } else {
            $this->_subject = $message[0];

            $this->_message = $message[1];
        }
	}
	
	/* _replaceMessageParameters
	 * 
	 * private method to replace tags from template with correct valeus
	 * fails if there is un-replaced %TAG% when parameters have been ran
	 * correct tags:
	 * %TAG%, %tag%, %tag-tag%, %tag-3%, %tag_5%
	 */
	private function _replaceMessageParameters() {
		$pattern = "/%[A-Za-z0-9]+((\-|\_)[A-Za-z0-9]+)*%/";
		foreach ($this->_parameters as $key => $value) {
			if (preg_match($pattern, "%".$key."%")) {
				$this->_message = preg_replace("/%".$key."%/", $value, $this->_message);
			} else {
				$this->_errorMessage = "Parameter with wrong syntax";
			}
		}
		if ( preg_match($pattern, $this->_message) ) {
			$this->_errorMessage = "Tag from template not filled";
			return false;
		}
		return true; 
	}
	
	/* send
	 * 
	 * method for sending the message, only ran of message is validated
	 */
	public function send() {
		if ($this->_validated) {
			$mail = new Zend_Mail();
	    	$mail->setBodyHtml($this->_message);
	    	$mail->setFrom('no-reply@massidea.org', 'Massidea.org');
	    	$mail->addTo($this->_receiver->email_usr, $this->_receiver->login_name_usr);
	    	$mail->setSubject($this->_subject);
	    	$mail->send();
		}
	}
}
?>