<?php
class OibsAPI
{
	private $_pubkey = null;
	private $_authed = false;
	private $_userdata = null;
	
	public function __construct()
	{
		$this->_pubkey = (isset($_GET['key'])) ? $_GET['key'] : false;
		if($this->_pubkey)
		{
			$this->_authenticate($this->_pubkey);
		}
		else
		{
			$this->_throwError('API key not provided');
		}
	}
	
	private function _authenticate($pubKey)
	{
		$keymodel = new Default_Model_UserApiKey();
		$apiUser = $keymodel->getUserIdByApiKey($pubKey);

		if(!$apiUser)
		{
			$this->_throwError('Invalid API key', array('provided' => $pubKey));
		}
		else
		{
			$usermodel = new Default_Model_User();
			$userdata = $usermodel->getSimpleUserDataById($apiUser);
			$this->_authed = true;
			$this->_userdata = $userdata;
		}
	}
	
	private function _throwError($string, $details = 'none')
	{
		throw new Exception($string);
	}
	
	/**
	 * getServerTime()
	 * 
	 * Simple method for testing API functionality.
	 *  
	 * @return	array	Time and date
	 */
	public function getServerTime()
	{
		return getdate();
	}
	
	private function _validateContent($data, $min, $max, $req)
	{
		$return = true;
		// Recursive check
		if(is_array($data))
		{
			foreach($data as $blip)
			{
				$return = $return && $this->_validateContent($blip, $min, $max, $req);	
			}
		}
		else
		{
			$length = strlen($data);
			if(($req && $length == 0) ||
				$length < $min ||
				$length > $max)
			{
				$return = false;
			}
		}
		return $return;
	}
	
	/**
	 * getTableDimensions()
	 * 
	 * Get list of parameters with min/max/required -values for given content type.
	 * 
	 * @param	string		$cty	content type (problem/idea/finfo)
	 * @return	array				relevant data
	 */
	public function getTableDimensions($cty = 'none')
	{
		$config = new Zend_Config_Ini('../../application/config/tabledimensions.ini');
		$dimensions = $config->contents_cnt->toArray();
		
		switch($cty)
		{
			case 'problem':
				$dimensions['content_solution']['req'] = '0';
				$dimensions['content_opportunity']['req'] = '0';
				$dimensions['content_threat']['req'] = '0';
				break;
				
			case 'idea':
				$dimensions['content_research']['req'] = '0';
				$dimensions['content_opportunity']['req'] = '0';
				$dimensions['content_threat']['req'] = '0';
				break;
				
			case 'finfo': 
				$dimensions['content_research']['req'] = '0';
				$dimensions['content_solution']['req'] = '0';
				break;
				
			default:
				$this->_throwError('Missing content type');
				break;
		}
		
		return $dimensions;
	}
	
	/**
	 * addContent()
	 * 
	 * Add new content. See method 'getTableDimensions' for required parameters.
	 * 
	 * @param	array	$data
	 * @return	int		content id
	 */
	public function addContent($data)
	{
		$cty = (isset($data['content_type'])) ? $data['content_type'] : 'none';
		$dimensions = $this->getTableDimensions($cty);
		
		// Get content type id
		$contentTypeModel = new Default_Model_ContentTypes();
		$data['content_type'] = $contentTypeModel->getIdByType($cty);
		
		// Get user data
		$data['User'] = $this->_userdata;
		
		// Check invalid / missing keys
		foreach($dimensions as $key => $value)
		{
			if(array_key_exists($key, $data))
			{
				if(!$this->_validateContent($data[$key], 
											$value['min'], 
											$value['max'], 
											$value['req']))
				{
					$error['invalid'][] = $key;
				}
			}
			else
			{
				$error['missing'][] = $key;
			}
		}
		
		// If everything went better than expected, save/publish content
		if(!isset($error))
		{
			$contentModel = new Default_Model_Content();
			$return = $contentModel->addContent($data);
		}
		else
		{
			$this->_throwError('Invalid parameters', $error);
		}
		
		return $return;
	}
}


/*
 * launchService()
 * 
 * Launch API service (called from bootstrap)
 */
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
