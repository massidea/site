<?php
/**
 *  GroupExists -> Validator to check if a group already exists.
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
 *  Oibs_Validators_GroupExists - class
 *
 *  @package 	  Oibs
 *  @subpackage Validators
 *  @author     Mikko Aatola
 *  @license    GPL v2
 *  @version 	  1.0
 */ 
class Oibs_Validators_CampaignExists extends Zend_Validate_Abstract
{
	const NOT_MATCH = 'notMatch';

	protected $_messageTemplates = array(
		self::NOT_MATCH => 'campaign-exists'
	);
	
    /**
    *   isValid Checks if a campaign exists in db using Campaign model's
    *   campaignExists function.
    *
    *   @param  $value string group name
    *   @return boolean
    */
	public function isValid($value)
	{
	    $value = (string) $value;
	    $this->_setValue($value);

        $campaignModel = new Default_Model_Campaigns();
        
        if ($campaignModel->campaignExists($value)) {
            $this->_error(self::NOT_MATCH);
            return false;   // if the group exists, the form is not valid
        } else {
            return true;    // if the group doesn't exist, the form is valid
        }
	}
}