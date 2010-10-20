<?php
/**
 *  city validator, validates citys syntax
 *
 * 	Copyright (c) <2010>, Sami Suuriniemi
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
 *  city - class
 *
 *  @package 	Oibs
 *  @subpackage	Validators
 *  @author		Sami Suuriniemi
 *  @copyright 	2010 Sami Suuriniemi
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class Oibs_Validators_city extends Zend_Validate_Abstract
{
	const NOT_VALID = 'notValid';
	
    protected $_messageTemplates = array(
        self::NOT_VALID => "City not valid",
    );
	
	/**
	*	isValid
	* 
	*	Checks if city is valid
	*	@param string $value city to be validated
	*	@return boolean
	*/
	public function isValid($city)
	{
		// city pattern allowed textornumber(. or - or _)textornumber
		// sami.suuriniemi, sami_suuriniemi, sami-suuriniemi.
		// punctioation has to be in middle of city and only 1 at a time
		$pattern = "/^[A-Za-z]+((\.|\_|\-)[A-Za-z]+)*$/";
		$matches = array();
		if (preg_match($pattern, $city)) {
			return true;
		}
		$this->_error(self::NOT_VALID);
		return false;
		
	} // end isValid()
}
?>