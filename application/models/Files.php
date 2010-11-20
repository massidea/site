<?php
/**
 *  Files -> Files database model for content files table.
 *
* 	Copyright (c) <2009>, Markus Riihel�
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
 *  Files - class
 *
 *  @package 	models
 *  @author 		Markus Riihel� & Mikko Sallinen
 *  @copyright 	2009 Markus Riihel� & Mikko Sallinen
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class Default_Model_Files extends Zend_Db_Table_Abstract
{
	// Table name
    protected $_name = 'files_fil';
    
	// Table primary key
	protected $_primary = 'id_fil';
	
	// Table reference map
	protected $_referenceMap    = array(
        'FileContent' => array(
            'columns'           => array('id_cnt_fil'),
            'refTableClass'     => 'Default_Model_Content',
            'refColumns'        => array('id_cnt')
        ),
		'FileUser' => array(
			'columns'			=> array('id_usr_fil'),
			'refTableClass'		=> 'Default_Model_User',
			'refColumns'		=> array('id_usr')
		)
    );
    
    
    /**
     * 
     * adds files to database
     * @param mixed $id_target id_cnt/id_cmp whatever
     * @param string $type "content"/"account" etc
     * @param array $data $_FILES
     * @author sami suuriniemi 2010
     */
    public function newFiles($id_target, $type, $data) {

    	for ($i=1;$i < count($data['name']);$i++)
		{
			$files = $data;
			$file['name'] = $files['name'][$i];
			$file['type'] = $files['type'][$i];
			$file['tmp_name'] = $files['tmp_name'][$i];
			$file['error'] = $files['error'][$i];
			$file['size'] = $files['size'][$i];
			$this->newFile($id_target, $type, $file);
		}
    }
    
    /**
    *   newFile
    *   
    *   handle uploading of new file, file goes to files/[userid]/[hash of filecontent+filename]
    *   
    *   @param  id_cnt		int		content id with which the file is linked 
    *   @param  id_usr      int     user id for the user whose pic to modify
    *   @param  uploadedFile array  has info of file, if left empty will use $_FILES 
    *   @return success boolean was the procedure succesful?
    */
    private function newFile($id_target, $type, $uploadedFile = "") 
    {
    	if ($uploadedFile == "") {
    		$uploadedFile = $_FILES['content_file_upload'];
    	}
    	
    	$hash = hash_file('sha1', $uploadedFile['tmp_name']);
		$dir = "files/";
    	if (! file_exists($dir)) {
    		mkdir($dir, 0777, true);
    	} 
    	
    	$select = $this->select();
    	$select->from($this, array("*"))
    			->where("hash_fil = ?", $hash);
    	$result = $this->fetchAll($select);
    	$id = 0;
    	if ( !file_exists($dir)) {
    		return false;
    	} else if ($result->count() != 0) {
    		$id = $result->current()->id_fil;
    	} else {
	    	move_uploaded_file($uploadedFile['tmp_name'], $dir.$hash);
	    	$file = $this->createRow();
	    	$file->filetype_fil = $uploadedFile['type'];
	    	$file->filename_fil = $uploadedFile['name'];
	    	$file->hash_fil = $hash;
	    	
	 	    $file->created_fil = new Zend_Db_Expr('NOW()');
	        $file->modified_fil = new Zend_Db_Expr('NOW()');
	    	$id = $file->save();
    	}
    	$ptpModel = new Default_Model_PageTypes();
    	$id_type = $ptpModel->getId($type);
    	$fliModel = new Default_Model_FileLinks();
    	
    	$fliModel->addLink($id, $id_target, $id_type);
    	return true;
    }
    
    public function getFilenames($id_cnt, $type){
    	$fliModel = new Default_Model_FileLinks();
    	$ptpModel = new Default_Model_PageTypes();
    	
    	$id_type = $ptpModel->getId($type); 
    	$ids = $fliModel->getFileIds($id_cnt, $id_type);
    	$result =  $this->find($ids);
    	$rows = array();
    	foreach ($result as $row) {
    		$rows[$row->id_fil] = $row->filename_fil;
    	}
    	return $rows;
    }
    
    public function getFile($id_fil = 0)
    {
        if ($id_fil != 0) {
            return $this->find($id_fil);         
        }
    }
    
    public function getFileData($id_fil = 0)
    {
        // Get file data        
        if ($id_fil != 0) {
        	
        	$rs = $this->find($id_fil);
    		$cur = $rs->current();
    		$dir = "files/".$cur->hash_fil;
    		
            $result = file_get_contents($dir);
            return $result;
        }
    }
    
    /* Commenting since its not in use
    public function getContentFiles($id_cnt = 0) {
    	if ($id_cnt != 0) {
    		$select = $this->select()->from($this, array("id_fil", "filename_fil"))
    								 ->where('id_cnt_fil = ?', $id_cnt);
    		$result = $this->fetchAll($select);
    		return $result;
    	}
    }*/
    /**
     * 
     * Enter description here ...
     * @param unknown_type $id_target
     * @param unknown_type $type
     * @param array $files ids of files to be deleted from certain content/campaign
     */
    public function deleteCertainFiles($id_target, $type, $files) {
    	if (isset($files))
    	{
	    	foreach ($files as $file) {
				$this->deleteFile($file, $id_target, $type);
	    	}	
    	}
    }
    
    /** deleleteFromFilesystem
     * 
     * delete a file from filesystem according to database link
     * 
     * @param	mixed files_fil tables id
     * @return  boolean  whether removing file from filesystem was successfull or not
     */
    private function deleteFromFilesystem($id_fil)
    {
    	     // Delete from filesystem
    		$rs = $this->find($id_fil);
    		$cur = $rs->current();
    		$dir = "files/".$cur->hash_fil;
    		$success = unlink($dir);

    		return $success;
    }
    
    /** deleteFromFilsystem
     * 
     * Deletes specified contents files from filesystem
     * 
     * @param  	int			 	content id
     * @param 	mixed
     * @param   bool			if fileremoval was successfull
     */
    private function deleteFilesById($id_target, $type){
    	$fliModel = new Default_Model_FileLinks();
    	$ids = $fliModel->getFileIds($id_target, $type);

      	$results = array();
        foreach ($ids as $id) {
        	$results[] = $this->deleteFile($id['id_file'], $id_target, $type);
        }
        return !in_array(false, $results);
    }

    /**
     * 
     * delete single file from single content, check if the file is still needed
     * @param $id file id
     * @param $id_target
     * @param $type
     */
    public function deleteFile($id, $id_target, $type) {
		$fliModel = new Default_Model_FileLinks();
    	$results = array();
		if (!$fliModel->removeLink($id, $id_target, $type)) {
        	array_push($results, $this->deleteFromFilesystem($id['id_file']));
        	$where = $this->_db->quoteInto('id_fil = ?', $id['id_file']);
        	array_push($results, $this->delete($where));
        }
        return !in_array(false, $results);
    }
    
    /**
    *   fileExists
    *
    *   Check if file exists in database.
    *
    *   @param $id_fil integer Id of file
    *   @return boolean
    */
    public function fileExists($id_fil = 0)
    {
        $exists = false;
        
        if ($id_fil != 0) {
            $select = $this->select()
                            ->from($this, array('filename_fil'))
                            ->where('id_fil = ?', $id_fil);
            
            $result = $this->fetchAll($select)->toArray();
            
            if(isset($result[0]) && !empty($result[0])) {
                $exists = true;
            }
        }
        
        return $exists;
    }

    /**
    *   removeContentFiles
    *   Removes specified file
    *
    *   @param		int		id_cnt_fil	Id of the content
    *   @author		Mikko Korpinen
    */
    public function removeFiles($id_target, $type)
    {
        $deleteResult = $this->deleteFilesById($id_target, $type);
        return $deleteResult;
    }

    /* Older convert files
     * public function convertFiles() {
    	$select = $this->_db->select()
    						->from("files_fil_old");
    	$rs = $this->_db->fetchAll($select);
    	foreach ($rs as $row) {
    		$hash = hash_hmac('sha1', $row['data_fil'], $row['id_cnt_fil'].$row['filename_fil'] );
    		$dir = "files/".$row["id_usr_fil"]."/";
    		if (! file_exists($dir)) {
    			mkdir($dir, 0777, true);
    		}
    		if (! file_exists($dir.$hash)) {
	    		if (($fh = fopen($dir.$hash, "w"))) {
	    			fwrite($fh, $row['data_fil']);
	    			fclose($fh);
	    			
	    			$file = $this->createRow();
	    			$file->id_cnt_fil = $row['id_cnt_fil'];
	    			$file->id_usr_fil = $row['id_usr_fil'];
	    			$file->filetype_fil = $row['filetype_fil'];
	    			$file->filename_fil = $row['filename_fil'];
	    			$file->hash_fil = $hash;
			 	    $file->created_fil = new Zend_Db_Expr('NOW()');
			        $file->modified_fil = new Zend_Db_Expr('NOW()');
			        $file->save();
	    		}
    		}
    	}
	}*/
	
	public function getAll() {
		$select = $this->select();
		$select->from($this, '*');
		$result = $this->fetchAll($select)->toArray();
		return $result;	
	}
} // end of class
?>