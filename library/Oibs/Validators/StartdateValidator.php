<?php
/**
 *  StartdateValidator -> Check if start date is later or same than this date
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
 *  StartdateValidator - class
 *
 *  @package     Oibs
 *  @subpackage  Validators
 *  @author      Mikko Korpinen
 *  @license     GPL v2
 *  @version     1.0
 */ 
class Oibs_Validators_StartdateValidator extends Zend_Validate_Abstract
{
	const NOT_MATCH = 'notMatch';

	protected $_messageTemplates = array(
		self::NOT_MATCH => 'Start day cannot be before this day.'
	);

	public function isValid($startDay)
	{
	    $start = (string) $startDay;
        // If start day is empty return true, Campaings-model set this day
        if (empty($start)) {
            return true;
        } else {
            $start = new Zend_Date($start, Zend_Date::ISO_8601);
        }
	    $this->_setValue($start);

        $thisDay = date("Y-m-d", time());
        $thisDay = new Zend_Date($thisDay, Zend_Date::ISO_8601);
        $thisDay->subDay(1);

        if ($start->compare($thisDay) == 1) {
            return true;
        }

	    $this->_error(self::NOT_MATCH);
	    return false;
	}

}