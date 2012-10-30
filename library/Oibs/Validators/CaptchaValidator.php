<?php
/**
 *  CaptchaValidator -> Validates captcha 
 *
* 	Copyright (c) <2009>, Markus Riihel� 
* 	Copyright (c) <2009>, Mikko Sallinen
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
 *  CaptchaValidator - class
 *
 *  @package 	Oibs
 *  @subpackage	Validators
 *  @author 		Markus Riihel� & Mikko Sallinen
 *  @copyright 	2009 Markus Riihel� & Mikko Sallinen
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class Oibs_Validators_CaptchaValidator extends Zend_Validate_Abstract
{
	const MSG_URI = 'msgUri';
	
    protected $_messageTemplates = array(
        self::MSG_URI => "error-captcha-no-same",
    );
	
	/**
	*	Checks if captcha is valid
	*	@param string $value captcha value to be validated
	*	@return boolean
	*/
	public function isValid($value)
	{
		// Get session captcha
		$session = new Zend_Session_Namespace('registration');
		$security_code = $session->security_code;
		
		// if md5 hash of user submitted value is not equal to session captcha, set error and return false
		if (md5(strtolower($value)) != $security_code)
		{
            $this->_error(self::MSG_URI);
			return false;
		} // end if
		
		return true;
	} // end isValid()
}
?>