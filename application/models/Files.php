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
    *   newFile
    *   
    *   handle uploading of new file, file goes to files/[userid]/[hash of filecontent+filename]
    *   
    *   @param  id_cnt		int		content id with which the file is linked 
    *   @param  id_usr      int     user id for the user whose pic to modify
    *   @param  uploadedFile array  has info of file, if left empty will use $_FILES 
    *   @return success boolean was the procedure succesful?
    */
    public function newFile($id_cnt, $id_usr, $uploadedFile = "") 
    {
    	if ($uploadedFile == "") {
    		$uploadedFile = $_FILES['content_file_upload'];
    	}
    	
    	$hash = hash_hmac_file('sha1', $uploadedFile['tmp_name'], $id_cnt.$uploadedFile['name']);
		$dir = "files/".$id_usr."/";
    	if (! file_exists($dir)) {
    		mkdir($dir, 0777, true);
    	} 
    	
    	if ( !file_exists($dir) || file_exists($dir.$hash)) {
    		return false;
    	}
    	
    	move_uploaded_file($uploadedFile['tmp_name'], $dir.$hash);
    	$file = $this->createRow();
    	$file->id_cnt_fil = $id_cnt;
    	$file->id_usr_fil = $id_usr;
    	$file->filetype_fil = $uploadedFile['type'];
    	$file->filename_fil = $uploadedFile['name'];
    	$file->hash_fil = $hash;
    	
 	    $file->created_fil = new Zend_Db_Expr('NOW()');
        $file->modified_fil = new Zend_Db_Expr('NOW()');
    	$file->save();
    }
    
    public function getFilenamesByCntId($id_cnt){
    	$select = $this->select()
    				   ->from($this, array('id_fil', 'filename_fil'))
    				   ->where('id_cnt_fil = ?', $id_cnt);
    	$result = $this->fetchAll($select);
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
        
            // Create query
            /*$select = $this->_db->select()
                                ->from('files_fil', 
                                       array('data_fil', 'filetype_fil'))
                                ->where('id_fil = ?', $id_fil);
            
            // Fetch data from database
            $result = $this->_db->fetchAll($select);*/
        	
        	$rs = $this->find($id_fil);
    		$cur = $rs->current();
    		$dir = "files/".$cur->id_usr_fil."/".$cur->hash_fil;
    		
            $result = file_get_contents($dir);
            return $result;
        }
    }
    
    public function getContentFiles($id_cnt = 0) {
    	if ($id_cnt != 0) {
    		$select = $this->select()->from($this, array("id_fil", "filename_fil"))
    								 ->where('id_cnt_fil = ?', $id_cnt);
    		$result = $this->fetchAll($select);
    		return $result;
    	}
    }
    
    public function deleteFiles($files) {
    	if (isset($files))
    	{
	    	foreach ($files as $file) {
	      		// Delete from filesystem
				$this->deleteFromFilesystem($file);

    			// Delete link from database
    			$this->delete('id_fil = ' . $file);
	    	}	
    	}
    }
    
    /* deleleteFromFilesystem
     * 
     * delete a file from filesystem according to database link
     * 
     * @param	id_fil		files_fil tables id
     * @return  success 	whether removing file from filesystem was successfull or not
     */
    private function deleteFromFilesystem($id_fil)
    {
    	     // Delete from filesystem
    		$rs = $this->find($id_fil);
    		$cur = $rs->current();
    		$dir = "files/".$cur->id_usr_fil."/".$cur->hash_fil;
    		$success = @unlink($dir);

    		return $success;
    }
    
    /* deleteFromFilsystemByContentId
     * 
     * Deletes specified contents files from filesystem
     * 
     * $param 	$id_cnt_fil 	content id
     * $return  bool			if fileremoval was successfull
     */
    private function deleteFromFilesystemByContentId($id_cnt_fil){
        $select = $this->select()//->from($this, array('id_fil'))
        			   ->where('id_cnt_fil = ?', $id_cnt_fil);
		$result = $this->fetchAll($select);
      	$results = array();

        foreach ($result as $row) {
        	array_push($results, $this->deleteFromFilesystem($row->id_fil));
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
    public function removeContentFiles($id_cnt_fil)
    {
        $where = $this->getAdapter()->quoteInto('id_cnt_fil = ?', $id_cnt_fil);
        
        $filesystemDeleteResult = $this->deleteFromFilesystemByContentId($id_cnt_fil);
        $databaseDeleteResult = $this->delete($where);
        
        if ($databaseDeleteResult && $filesystemDeleteResult) {
            return true;
        } else {
            return false;
        }
    }

    public function convertFiles() {
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
	}
} // end of class
?>