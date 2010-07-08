<?php
/**
 *  UserImages -> UserImages database model for userimages table.
 *
 *  Copyright (c) <2009>, Markus Riihel�
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
 *  @author     Markus Riihel� & Mikko Sallinen & Joel Peltonen
 *  @copyright  2009 Markus Riihel� & Mikko Sallinen & Joel Peltonen
 *  @license    GPL v2
 *  @version    1.0
 */ 
class Default_Model_UserImages extends Zend_Db_Table_Abstract
{
	// Name of table
    protected $_name = 'usr_images_usi';
    
	// Primary key of table
	protected $_primary = 'id_usi';
	
	// Tables reference map
	protected $_referenceMap    = array(
        'UserUser' => array(
            'columns'           => array('id_usr_usi'),
            'refTableClass'     => 'Default_Model_User',
            'refColumns'        => array('id_usr')
        )
    );
    
    /**
    *   newUserImage > set new user image to default, used in registration
    *   
    *   @param  idUsr	int     user id for the user whose pic to modify
    *   @param	fsPath	string	Path to the full sized image (original)
    *   @param	tnPath	string	Path to the thumbnail image
    *   @return success	boolean was the procedure succesful?
    */
    public function newUserImage($idUsr, $fsPath, $tnPath) 
    {
		$thumbdata = fopen($tnPath, 'r');
		$thumbnail = fread($thumbdata, filesize($tnPath));

        // Read fullsized file info location relates to index.php bootstrap loader
        $fullsizedata = fopen($fsPath, 'r');
        $fullsize = fread($fullsizedata, filesize($fsPath));

        // generate new row
        $image = $this->createRow();
        
        // Set images and user id
        $image->thumbnail_usi = $thumbnail;
        $image->image_usi = $fullsize;
        $image->id_usr_usi = $idUsr;

        $image->created_usi = new Zend_Db_Expr('NOW()');
        $image->modified_usi = new Zend_Db_Expr('NOW()');
        
        $image->save();
    }
    
    public function getImagesByUsername($id)
    {
        $select = $this->select()
                       ->from($this, array('id_usi', 'created_usi', 'modified_usi'))
                       ->where('id_usr_usi = ?', $id);
    						
    	$result = $this->fetchAll($select)->toArray();
        if($result!= null)
        return $result;
        else
        return array();
    }
    
    public function getImageById($id)
    {
        // Create query
        $select = $this->select()
                            ->from($this)
                            ->where('id_usi = ?', $id);

        // Fetch data from database
        $result = $this->_db->fetchRow($select);    
        
        return $result;
    } // end of getUserImageData
    
    public function updateModDate($id)
    {
        $data['modified_usi'] = date('Y-m-d H:i:s');
        $this->_db->update('usr_images_usi', $data, 'id_usi = '.$id);
    }
    
    public function deleteImageById($id)
    {
        $this->_db->delete('usr_images_usi', 'id_usi = '.$id);
    }
} // end of class
?>