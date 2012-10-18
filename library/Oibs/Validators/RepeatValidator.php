<?php
/**
 *  RepeatValidator -> Generic two-field repeating validator 
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
 *  @package     Oibs
 *  @subpackage  Validators
 *  @author      Joel Peltonen
 *  @license     GPL v2
 *  @version     1.0
 */ 
class Oibs_Validators_RepeatValidator extends Zend_Validate_Abstract
{
	const NOT_MATCH = 'notMatch';

	protected $_messageTemplates = array(
		self::NOT_MATCH => 'error-value-not-same'
	);
	
	private $repeatedField; 

	/* set field against which to compare values */
	public function __construct($repeatedField)
	{
		$this->repeatedField = $repeatedField;
	}

	public function isValid($value, $context = null)
	{
	    $value = (string) $value;
	    $this->_setValue($value);

		/* context is an array with the entire set of form values in our case,
		* the value we are interested in is indeed $context["$this->repeatedField"],
		* (confirm_password field) which should be the same as $value (password field)
		*/
	    if (is_array($context)) {
	        if (isset($context["$this->repeatedField"])
	            && ($value == $context["$this->repeatedField"]))
	        {
	            return true;
	        }
	    } elseif (is_string($context) && ($value == $context)) {
	        return true;
	    }

	    $this->_error(self::NOT_MATCH);
	    return false;
	}

}