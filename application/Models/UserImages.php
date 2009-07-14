<?php
/**
 *  UserImages -> UserImages database model for userimages table.
 *
 *  Copyright (c) <2009>, Markus Riihelä
 *  Copyright (c) <2009>, Mikko Sallinen
 *  Copyright (c) <2009>, Joel Peltonen
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
 *  UserImages - class
 *
 *  @package    models
 *  @author     Markus Riihelä & Mikko Sallinen & Joel Peltonen
 *  @copyright  2009 Markus Riihelä & Mikko Sallinen & Joel Peltonen
 *  @license    GPL v2
 *  @version    1.0
 */ 
class Models_UserImages extends Zend_Db_Table_Abstract
{
	// Name of table
    protected $_name = 'usr_images_usi';
    
	// Primary key of table
	protected $_primary = 'id_usi';
	
	// Tables reference map
	protected $_referenceMap    = array(
        'UserUser' => array(
            'columns'           => array('id_usr_usi'),
            'refTableClass'     => 'Models_User',
            'refColumns'        => array('id_usr')
        )
    );
    
    /**
    *   newUserImage > set new user image to default, used in registration
    *   
    *   @param  idUsr      int     user id for the user whose pic to modify
    *   @return success boolean was the procedure succesful?
    */
    public function newUserImage($idUsr = 0) 
    {
        // Read thumbnail file info
		$thumbdata = fopen("images/userimage_tiny_64_74.jpg", 'r');
		$thumbnail = fread($thumbdata, filesize("images/userimage_tiny_64_74.jpg"));

        // Read fullsized file info location relates to index.php bootstrap loader
        $fullsizedata = fopen("images/userimage_big_200_230.jpg", 'r');
        $fullsize = fread($fullsizedata, filesize("images/userimage_big_200_230.jpg"));

        // generate new row
        $image = $this->createRow();

        // Set images and user id
        $image->thumbnail_usi = $thumbnail;
        $image->image_usi = $fullsize;
        $image->id_usr_usi = $idUsr;

        $image->created_usi = new Zend_Db_Expr('NOW()');
        $image->modified_usi = new Zend_Db_Expr('NOW()');
        
        return $image->save();
    }
} // end of class
?>