<?php
/**
 *  OpenidExists -> Validator to check if OpenID is already attached
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
 *  Oibs_Validators_OpenidExists - class
 *
 *  @package 	  Oibs
 *  @subpackage Validators
 *  @author     Jaakko Paukamainen
 *  @license    GPL v2
 *  @version 	  1.0
 */ 
class Oibs_Validators_OpenidExists extends Zend_Validate_Abstract
{
	const NOT_MATCH = 'notMatch';

	protected $_messageTemplates = array(
		self::NOT_MATCH => 'account-profile-openid-exists'
	);
	
    /**
    *   isValid Checks if openid exists in db using User Model's function
    *
    *   @param  $value string openid
    *   @return boolean
    */
	public function isValid($value)
	{
	    $value = (string) $value;
	    $this->_setValue($value);

	    $goodvalue = openid_makegoodurl($value);
	    //echo $goodvalue;
        $userModel = new Default_Model_UserProfiles();
        
        if ($userModel->openidDupes($goodvalue)) {
            $this->_error(self::NOT_MATCH);
            return false;   // if openid is registered, form is not valid
        } else {
            return true;    // if openid is available, form is valid
        }
	}
}

    /**
    *   openid_makegoodurl - Reformats given url to right format for openid
    *
    *   @param  $url string url
    *   @return string
    */
function openid_makegoodurl($url) {
	$exploded = parse_url($url);
	
	$scheme = "";
	if(isset($exploded['scheme'])) {
		$scheme = $exploded['scheme'];
	}
	if($scheme == null) {
		$scheme = "http";
	}
	
	$host = "";
	if(isset($exploded['host'])) {
		$host = $exploded['host'];
	}
	
	$path = "";
	if(isset($exploded['path'])){
		$path = $exploded['path'];
	}
	if($path != "/" && substr($path, -1) != "/") {
		$path = $path . "/";
	}
	$goodurl = $scheme . "://" . $host . $path;
	return $goodurl;
}