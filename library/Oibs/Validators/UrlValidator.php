<?php
/**
 *  UrlValidator -> Validator to url
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
 *  UrlValidator - class
 *
 *  @package 	  Oibs
 *  @subpackage Validators
 *  @author     Mikko Korpinen
 *  @license    GPL v2
 *  @version 	  1.0
 */ 
class Oibs_Validators_UrlValidator extends Zend_Validate_Abstract
{
	const INVALID_URL = 'invalidUrl';

    protected $_messageTemplates = array(
        self::INVALID_URL   => "'%value%' is not a valid URL.",
    );

    public function isValid($value)
    {
        $valueString = (string) $value;
        $this->_setValue($valueString);

        if (!Zend_Uri::check($value)) {
            $this->_error(self::INVALID_URL);
            return false;
        }
        return true;
    }
}