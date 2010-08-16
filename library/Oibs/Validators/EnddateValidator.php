<?php
/**
 *  EnddateValidator -> Check if end date is later than start date
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
 *  EnddateValidator - class
 *
 *  @package     Oibs
 *  @subpackage  Validators
 *  @author      Mikko Korpinen
 *  @license     GPL v2
 *  @version     1.0
 */ 
class Oibs_Validators_EnddateValidator extends Zend_Validate_Abstract
{
	const NOT_MATCH = 'notMatch';

	protected $_messageTemplates = array(
		self::NOT_MATCH => 'End day should be after start day.'
	);
	
	private $dateField;

	/* set field against which to compare values */
	public function __construct($dateField)
	{
		$this->dateField = $dateField;
	}

	public function isValid($endDay, $startDay = null)
	{
	    $end = (string) $endDay;
        // If end day is empty return true, Campaings-model set 0000-00-00
        if (empty($end)) {
            return true;
        } else {
            $end = new Zend_Date($end, Zend_Date::ISO_8601);
        }
	    $this->_setValue($end);

		/* context is an array with the entire set of form values in our case,
		* the value we are interested in is $context["$this->dateField"],
		* (start day field) which should before as $value (end date field)
		*/
	    if (is_array($startDay)) {
	        if (isset($startDay["$this->dateField"])) {
                // If start day is empty, set this day
                if (empty($startDay["$this->dateField"])) {
                    $start = date("Y-m-d", time());
                } else {
                    $start = $startDay["$this->dateField"];
                }
                $start = new Zend_Date($start, Zend_Date::ISO_8601);
                if ($end->compare($start) == 1) {
                    return true;
                }
	        }
	    } elseif (is_string($startDay)) {
            // If start day is empty, set this day
            if (empty($startDay)) {
                $start = date("Y-m-d", time());
            } else {
                $start = $startDay;
                $start = new Zend_Date($start, Zend_Date::ISO_8601);
            }
            if ($end->compare($start) == 1) {
                return true;
            }
	    }

	    $this->_error(self::NOT_MATCH);
	    return false;
	}

}