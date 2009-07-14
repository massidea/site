<?php
/**
 *  Links -> Links database model for links table.
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
 *  Links - class
 *
 *  @package 	models
 *  @author 		Markus Riihelä & Mikko Sallinen
 *  @copyright 	2009 Markus Riihelä & Mikko Sallinen
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class Models_Links extends Zend_Db_Table_Abstract
{
	// Tagle name
    protected $_name = 'links_lnk';
    
	// Table primary key
	protected $_primary = 'id_lnk';
	
	// Table reference map
	protected $_referenceMap    = array(
        'LinkContent' => array(
            'columns'           => array('id_cnt_lnk'),
            'refTableClass'     => 'Models_Content',
            'refColumns'        => array('id_cnt')
        ),
		'LinkOwner' => array(
            'columns'           => array('id_usr_lnk'),
            'refTableClass'     => 'Models_User',
            'refColumns'        => array('id_usr')
		)
    );
} // end of class
?>