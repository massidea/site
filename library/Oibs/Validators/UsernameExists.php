<?php
/**
 *  UsernameExists -> Validator to check if username is already registered
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
 *  RepeatValidator - class
 *
 *  @package 	  Oibs
 *  @subpackage Validators
 *  @author     Joel Peltonen
 *  @license    GPL v2
 *  @version 	  1.0
 */ 
class Oibs_Validators_UsernameExists extends Zend_Validate_Abstract
{
	const NOT_MATCH = 'notMatch';

	protected $_messageTemplates = array(
		self::NOT_MATCH => 'username-exists'
	);
	
    /**
    *   isValid Checks if username exists in db using User Model's function
    *
    *   @param  $value string username
    *   @return boolean
    */
	public function isValid($value)
	{
	    $value = (string) $value;
	    $this->_setValue($value);

        $userModel = new Models_User();
        
        if ($userModel->usernameExists($value)) {
            $this->_error(self::NOT_MATCH);
            return false;   // if username is registered, form is not valid
        } else {
            return true;    // if username is registered, form is not valid
        }
	}
}