<?php
class Default_Model_ProfileBackgroundImage {
	
	public function uploadBackgroundImage($filePath, $tempFileName, $fileName, $username) {

		$fullFileName = $username.'_'.$fileName;
		
		if(file_exists($filePath . $fullFileName)) {
			$this->deleteBackgroundImage($filePath, $fullFileName);
		}
		
		move_uploaded_file($tempFileName, $filePath . $fullFileName);

		return $filePath.$fullFileName;
	}
	
	public function deleteBackgroundImage($filePath, $fileName) {
		
		if (!unlink($filePath.$fileName)) {
			//echo ('Error deleting '.$fileName);
		}
		else {
			//echo ('Deleted '.$fileName);
		}
	}
}

?>