<?php
/**
 *  UserProfiles -> UserProfiles database model for userprofiles table.
 *
* 	Copyright (c) <2009>, Markus Riihelä
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
 *  UserProfiles - class
 *
 *  @package 	models
 *  @author 		Markus Riihelä & Mikko Sallinen
 *  @copyright 	2009 Markus Riihelä & Mikko Sallinen
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class Models_Help extends Zend_Db_Table_Abstract
{
	// Name of table
    protected $_name = 'help_hlp';
	
	// Primary key of table
	protected $_primary = 'id_hlp';
	
	 
    public function getAllHelp($language) {
    //$language = 'en';
    
    //searches content of current language
    $select = $this->_db->select()
            ->from('help_hlp', array('title_hlp','content_hlp', 'lang_hlp'))
            ->where('lang_hlp = ?', $language);
        
        $stmt = $this->_db->query($select);
        $result = $stmt->fetchAll();
        return $result;
    }
    
} // end of class
?>