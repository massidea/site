<?php
/**
 *  FileLinks -> Files database model for linking files to their owner, file_links_fli table.
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
 *  FileLinks - class
 *
 *  @package 	models
 *  @author 	Sami Suuriniemi
 *  @copyright 	2009 Sami Suuriniemi
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class Default_Model_FileLinks extends Zend_Db_Table_Abstract
{
	// Table name
    protected $_name = 'file_links_fli';
    
	// Table primary key
	protected $_primary = array('id_target_fli', 'id_type_fli');
	
	// Table reference map
	protected $_referenceMap    = array(
        'FileType' => array(
            'columns'           => array('id_type_fli'),
            'refTableClass'     => 'Default_Model_PageTypes',
            'refColumns'        => array('type_ptp')
        ),
		'FileFile' => array(
			'columns'			=> array('id_file'),
			'refTableClass'		=> 'Default_Model_Files',
			'refColumns'		=> array('id_fil')
		)
    );

    /**
     * 
     * Enter description here ...
     * @param unknown_type $id_file
     */
    public function fileLinkCount($id_fil) {
    	$select = $this->select();
    	$select->where('id_file = ?', $id_fil)
    			;
    	$result = $this->fetchAll($select);
    	return $result->count();
    }
    
    
    /**
     * 
     * converts files to new multitype format
     */
    public function convert() {
    	$filesModel = new Default_Model_Files();
    	$files = $filesModel->getAll();
    	$newFiles = array();

    	$i = 1;
    	foreach($files as $file) {
    		$fileLoc = "files/".$file['id_usr_fil']."/".$file['hash_fil'];
    		if (file_exists($fileLoc)) {
	    		$hash = hash_file('sha1', $fileLoc);
	    		if (!isset($newFiles[$hash])) {
		    		$newFile = array();
		    		$newFile['hash_fil'] = $hash;
		    		$newFile['id_file'] = $i;
		    		$newFile['filetype_fil'] = $file['filetype_fil'];
		    		$newFile['filename_fil'] = $file['filename_fil'];
		    		$newFile['created_fil'] = $file['created_fil'];
		    		$newFile['modified_fil'] = $file['modified_fil'];
		    		$newFiles[$hash] = $newFile;
		    		if (!file_exists("files/".$hash)) {
						copy($fileLoc, "files/".$hash);
						unlink($fileLoc);
						@rmdir("files/".$file['id_usr_fil']);
		    		}
					$i++;
	    		}
    			$links = array();
	    		$links['id_target_fli'] = $file['id_cnt_fil'];
	    		$links['id_type_fli'] = 2;
	    		$links['oldhash'] = $file['hash_fil'];
    			$newFiles[$hash]['links'][] = $links;
    		}
    	}
    	
    	$filesModel->delete("1=1");
    	
    	foreach($newFiles as $file) {
    		foreach($file['links'] as $fileLink)
    		{
    			$row = $this->createRow();
    			$row->id_target_fli = $fileLink['id_target_fli'];
 	  			$row->id_type_fli = $fileLink['id_type_fli'];
    			$row->id_file = $file['id_file'];
    			$row->save();
 
    		}
    	}

    	foreach($newFiles as $file) {
    		$row = $filesModel->createRow();
    		$row->id_fil = $file['id_file'];
    		$row->hash_fil = $file['hash_fil'];
    		$row->filetype_fil = $file['filetype_fil'];
    		$row->filename_fil = $file['filename_fil'];
    		$row->created_fil = $file['created_fil'];
    		$row->modified_fil = $file['modified_fil'];
    		$row->save();
    	}
    }
    /**
     * 
     * checks if convert has already been done once, 
     * @return bool true if file_links_fli table has rows
     */
    public function convertDone() {
    	$select = $this->select();
    	$select->from($this, array('*'));
    	
    	$result = $this->fetchAll($select);
    	if ($result->count() > 0) return true;
    	return false;
    }
    /**
     * addLink
     * 
     * Add a link to a file
     * @param unknown_type $id_fil
     * @param unknown_type $id_target
     * @param unknown_type $id_type
     */
    public function addLink($id_fil, $id_target, $id_type) {
    	if (!$this->linkExists($id_target, $id_type, $id_fil)) {
			$id_type = $this->getTypeId($id_type);
	    	    	
	    	$row = $this->createRow();
	    	$row->id_file = $id_fil;
	    	$row->id_target_fli = $id_target;
	    	$row->id_type_fli = $id_type;
	    	$row->save();
    	}
    }
    
    /**
     * 
     * 
     * Deletes a link and returns if there is more links on a file 
     * @param $id_fil
     * @param $id_target
     * @param $id_type
     * 
     * @return boolean if file should be saved or deleted
     */
    public function removeLink($id_fil, $id_target, $id_type) {
    	$id_type = $this->getTypeId($id_type);
    	    	
    	$where = array();
    	$where[] = $this->_db->quoteInto('id_file = ?', $id_fil);
    	$where[] = $this->_db->quoteInto('id_type_fli = ?', $id_type);
    	$where[] = $this->_db->quoteInto('id_target_fli = ?', $id_target);
    	$this->delete($where);

    	if($this->fileLinkCount($id_fil) != 0) {
			// Just remove link
			return true;
		} else {
			// Remove link and file;
			return false;
		}
    }
    
    
    /**
     * 
     * Enter description here ...
     * @param $id_target
     * @param $id_type
     * @return array array of ids
     */
    public function getFileIds($id_target, $id_type) {
    	$id_type = $this->getTypeId($id_type);
    	$select = $this->select();
    	$select->from($this, ('id_file'))
    			->where('id_target_fli = ?', $id_target)
    			->where('id_type_fli = ?', $id_type)
    			;
    	$result = $this->fetchAll($select);
    	return $result->toArray();
    }
    
    /**
     * 
     * returns id of type
     * @param mixed $type string ("content") or int(2). 
     * @return int id_type
     */
    private function getTypeId($type) {
    	$ptpModel = new Default_Model_PageTypes();
    	$id_type = $ptpModel->getId($type);
    	return $id_type;
    }
    
    /**
     * 
     * checks if link already exists
     * @param unknown_type $id_target
     * @param unknown_type $type
     * @param unknown_type $id_file
     */
    private function linkExists($id_target, $type, $id_file) {
    	$select = $this->select()
    					->where('id_target_fli = ?', $id_target)
    					->where('id_type_fli = ?', $this->getTypeId($type))
    					->where('id_file = ?', $id_file);
    	return ($this->fetchAll($select)->count() != 0) ? true : false;
    }
}