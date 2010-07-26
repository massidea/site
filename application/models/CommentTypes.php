<?php
/**
 *  CommentTypes
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
 *  CommentTypes - class
 *
 *  @package 	models
 *  @author 	Sami Suuriniemi
 *  @copyright 	2010 Sami Suuriniemi
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class Default_Model_CommentTypes extends Zend_Db_Table_Abstract
{
	// Table name
    protected $_name = 'comment_types_ctp';
    
	// Table primary key
	protected $_primary = 'id_ctp';
	
	// Table dependet tables
	protected $_dependentTables = array('Default_Model_Comments');
	
	// Table reference map
	protected $_referenceMap    = array(
        'CommentType' => array(
            'columns'           => array('type_ctp'),
            'refTableClass'     => 'Default_Model_Comments',
            'refColumns'        => array('type_cmt')
        )
    );

    /**
     * getId
     * 
     * returns id of specified type 
     * 
     * @param string $type
     * @return int
     */
    public function getId($type) {
    	$select = $this->select()->from($this, array('type_ctp'))
    							->where("type_name_ctp = ?", $type)
    							->orWhere("type_ctp = ?", $type)
    							;
    	$result = $this->fetchAll($select)->toArray();
    	return $result[0]['type_ctp'];
    							
    }
} // end of class