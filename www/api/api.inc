<?php
class OibsAPI
{
	public function __construct()
	{
		$this->_authenticate($_GET['key']);
	}
	
	private function _authenticate($pubKey)
	{
		$hash = sha1($pubKey . $_SERVER['REMOTE_ADDR']);
		//print_r($hash); die;
		if($pubKey != 'jepa')
		{
			$this->_generateError('Invalid API key', true);
		}
	}
	
	private function _generateError($string, $halt = false)
	{
		echo Zend_Json::encode(array('error' => $string));
		if($halt) die;
	}
	
	public function getServerTime()
	{
		return getdate();
	}
	
	public function getGet()
	{
		return $_GET;
	}
	
	public function debug()
	{
		return $_SERVER;
	}
	/**
	 * 
	 * @param string $id
	 * @return array shiz
	 */
	public function getUserData($id)
	{
		$model = new Default_Model_User();
		return $model->getSimpleUserDataById($id);
	}
	
	/**
	 * addContent()
	 * @param string $data
	 * @return string 
	 */
	public function addContent($data)
	{
		$model = new Default_Model_Content();
		return null; //$model->addContent($data);
	}
}

function launchService() {

	$server = new Zend_Json_Server();
	$server->setClass('OibsAPI');
	
	// If no POST parameters, show service map
	if ('GET' == $_SERVER['REQUEST_METHOD'])
	{
	    // Indicate the URL endpoint, and the JSON-RPC version used:
	    $server->setTarget('http://dev.massidea.org/~jaakkop/api')
	           ->setEnvelope(Zend_Json_Server_Smd::ENV_JSONRPC_2);
	 
	    // Grab the SMD
	    $smd = $server->getServiceMap();
	 
	    // Return the SMD to the client
	    header('Content-Type: application/json');
	    echo $smd;
	    return;
	}
	
	// Handle requests
	$server->handle();
}
