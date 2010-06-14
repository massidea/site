<?php
/**
 *  Message time validator, prevents spamming of messages 
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
 *  Username - class
 *
 *  @package 	Oibs
 *  @subpackage	Validators
 *  @author		Sami Suuriniemi
 *  @copyright 	2010 Sami Suuriniemi
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class Oibs_Validators_MessageTime extends Zend_Validate_Abstract
{
	const NOT_VALID = 'notValid';
	
    protected $_messageTemplates = array(
        self::NOT_VALID => "message-sent-too-fast",
    );
	
	/**
	*	isValid
	* 
	*	Checks if user has sent message too fast
	*	@return boolean
	*/
	public function isValid($data)
	{
		$session = new Zend_Session_Namespace('timers');
		
    	if (!isset($session->lastMessage)) {
    		$session->lastMessage = time();
    		return true;
    	} else if (time() - $session->lastMessage > 5) {
    		return true;
    	}
		$this->_error(self::NOT_VALID);
		return false;
	} // end isValid()
}
