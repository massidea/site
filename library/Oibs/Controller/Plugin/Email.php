<?php
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

	public function setParameter($key, $value) {
		$this->_parameters[$key] = $value; 
		return $this;
	}
	
	public function setSenderId($id) {
		$userModel = new Default_Model_User();
		$this->_sender = $userModel->getUserRow($id);
		if ($this->_sender == null) {
			$this->_errorMessage = "Error on sender id";
		}
		return $this;
	}
	
	public function setReceiverId($id) {
		$userModel = new Default_Model_User();
		$this->_receiver = $userModel->getUserRow($id);
		if ($this->_receiver == null) {
			$this->_errorMessage = "Error on receiver id";
		}
		return $this;
	}
	
	public function setNotificationType($type) {
		$this->_notificationType = $type;
		return $this;
	}
	
	public function isValid() {
		$this->_loadMessage();
		$this->_replaceMessageParameters();

		if ($this->_errorMessage == "") {
			$this->_validated = true;
			return true;
		}
		return false;
	}
	
	public function getErrorMessage() {
		return $this->_errorMessage;
	}
	
	private function _loadMessage() {
		//
		//$this->_subject = "uus kommentti";
		$templateDir = "../library/Oibs/Emails/"; 
		$file = $templateDir."notification_email_".$this->_notificationType.".txt";
		
		$message = split("\n", file_get_contents($file), 2);

		$this->_subject = $message[0];
		
		
		$this->_message = nl2br($message[1]);
		
		return true;		 
	}
	
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