<?php
class Default_Model_CssFiles {
	
	private $defaultCssParams = array('background'=>'#ffffff', 
									  'font-family'=>'Arial,sans-serif',
									  'font-size'=>'12px',
									  'color'=>'#000000');
	
	public function readCssFileContent($_username) {

		// Parse user's custom css filename
		//$username = Zend_Layout::getMvcInstance()->getView()->username;
		$filename = 'css\\user_custom_style\\'.$_username.'.css';
		$defaultFilename = 'css\\user_custom_style\\default.css'; 

		if(file_exists($filename)) {
			$fileContent = file_get_contents($filename);
			return $fileContent;
		} else {
			// create user's new css file
			$fileHandle = fopen($filename, 'w') or die("can't open file");
			
			// get default css file content and 
			$fileContent = file_get_contents($defaultFilename);
			fwrite($fileHandle, $fileContent);
			
			fclose($fileHandle);
			
			// return created file content
			$fileContent = file_get_contents($filename);
			return $fileContent;
		}
	}
	
	public function writeCssFileContent($cssFileContent, $_username) {
		
		//echo 'writeCssFileContent';		// debug
		// Parse user's custom css filename
		//$username = Zend_Layout::getMvcInstance()->getView()->username;
		$filename = 'css\\user_custom_style\\'.$_username.'.css';
		
		// open file or create new file, if not exist
		$fileHandle = fopen($filename, 'w') or die("can't open file");
		
		// write new content to file
		$fileWriteSuccess = fwrite($fileHandle, $cssFileContent);
		
		return fileWriteSuccess;
	}
	
	public function GetStylingParams($cssFileContent, $div) {
		
		$stylingParams = $this->readStylingParams($cssFileContent, $div);
		
		if($stylingParams) {
			// Remodel font-family parameter to suit dropdown element
			$customfontlist = array('Arial', '"Arial Black"', '"Comic Sans MS"', '"Courier New"', 'Georgia', 'Impact', 'Tahoma', '"Times New Roman"', '"Trebuchet MS"', 'Verdana');
			$splitter = strpos($stylingParams['font-family'], ',');
			if($splitter) {
				$stylingParams['font-family'] =  substr($stylingParams['font-family'], 0, $splitter);
				$stylingParams['font-family'] = array_search($stylingParams['font-family'], $customfontlist, true);
			}

			return $stylingParams;
		} else {
			return false;
		}
	}
		
	public function getBackgroundImage() {
		
		$backgroundImage = 'tyhja.png';
		
		return $backgroundImage;
	}
	
	public function setStylingParams($cssFileContent, $div, $stylingParams, $_username) {
		
		// Remodel font-size parameter to suite css file
		$font_sizes = array('0' => '8', '1' => '9', '2' => '10', '3' => '11', '4' => '12', '5' => '13', '6' => '14');
		//$font_size_key = array_search('font-size', $stylingParams); 
		/*if(array_key_exists('font-size', $stylingParams)) {
			$stylingParams['font-size'] = $font_sizes[$stylingParams['font-size']].'px';
		}*/
		
		// Remodel font-family parameter to suite css file
		$font_familylist = array('Arial, sans-serif', '"Arial Black", sans-serif', '"Comic Sans MS", cursive', '"Courier New", monospace', 'Georgia, serif',
								'Impact, sans-serif', 'Tahoma, sans-serif', '"Times New Roman", serif', '"Trebuchet MS", sans-serif', 'Verdana, sans-serif');
		$stylingParams['font-family'] = $font_familylist[$stylingParams['font-family']];
		
		$newCssFileContent = $this->replaceStylingParams($cssFileContent, $div, $stylingParams);
		
		if(strlen($newCssFileContent)) {
			$this->writeCssFileContent($newCssFileContent, $_username);
			
			return $newCssFileContent;
		}
		
		return $newCssFileContent;
	}
		
	public function setFontType($cssFileContent, $newFontType) {

		/*$newCssFileContent = $this->replaceStylingParams($cssFileContent, '#user-page', 'font-family', $newFontType);
		
		$success = $this->writeCssFileContent($newCssFileContent);
		if($success) {
			echo 'setFontType toimii<br>';
		}*/
	}
	
	public function setFontSize($cssFileContent, $newFontSize) {
		
		/*$fontSizeSelector = array('8', '9', '10', '11', '12', '13', '14');
		$newCssFileContent = $this->replaceStylingParams($cssFileContent, '#user-page', 'font', $fontSizeSelector[$newFontSize].'px');
		
		$success = $this->writeCssFileContent($newCssFileContent);
		if($success) {
			echo 'setFontSize toimii<br>';
		}
		
		return $success;*/
	}
	
	public function setFontColor($cssFileContent, $newFontColor) {
		
		$newCssFileContent = $this->replaceStylingParam($cssFileContent, '#user-page', 'color', $newFontColor);

		$success = $this->writeCssFileContent($newCssFileContent);
		
		// debug
		if($success) {
			//echo '<br>setFontColor toimii<br>';
		}	
	}
	
	public function setBackground($cssFileContent, $newBackgroundColor) {
		
	}
	
	public function setBackgroundColor($cssFileContent, $newBackgroundColor) {
		
		$newCssFileContent = $this->replaceStylingParam($cssFileContent, '#user-page', 'background', $newBackgroundColor);
		
		$success = $this->writeCssFileContent($newCssFileContent);
		
		// debug
		if($success) {
			//echo '<br>setBackgroundColor toimii<br>';
		}
	}
	
	public function setBackgroundImage() {
		
	}
		
	private function replaceStylingParams($cssFileContent, $div, $newParamValue) {
		
		// Find div from css file content
		$divContent = '';
		$divLoc = strpos($cssFileContent, $div);
		$divStartLoc = 0;
		$divEndLoc = 0;
		if($divLoc) {
			$divStartLoc = strpos($cssFileContent, '{', $divLoc);
			$divEndLoc = strpos($cssFileContent, '}', $divStartLoc+1);
			$divContent = substr($cssFileContent, $divStartLoc+1, $divEndLoc-$divStartLoc-1);
		} else {
			return $divLoc;
		}
		
		// Find styling parameters of div written between {}
		$stylingParamContent = '';
		$stylingParamStartLoc = 0;
		$stylingParamEndLoc = 0;
		$newStylingParam = '';
		$newDiv = '';
		
		for($i=0; $i<count($newParamValue); $i++) {
			$stylingParams = array_keys($newParamValue);
			if(strlen($divContent)) {
				$stylingParamStartLoc = strpos($divContent, $stylingParams[$i]);
				if($stylingParamStartLoc) {
					$stylingParamEndLoc = strpos($divContent, ';', $stylingParamStartLoc);
					$stylingParamContent = substr($divContent, $stylingParamStartLoc, $stylingParamEndLoc-$stylingParamStartLoc+1);
					
					// Parse new styling param
					$paramSplitLoc = strpos($stylingParamContent, ':');
					$newStylingParam = substr_replace($stylingParamContent, $newParamValue[$stylingParams[$i]], $paramSplitLoc+1);
				} else {
					//return false;
				}
			} else {
				//return false;
			}
			
			// Replace styling parameter in div
			$newDivStart = substr($divContent, 0, $stylingParamStartLoc);
			$newDivEnd = substr($divContent, $stylingParamEndLoc);
			$newDiv = $newDivStart.$newStylingParam.$newDivEnd;
			$divContent = $newDiv;
		}
		
		// Parse new css file
		$newCssFileStart = substr($cssFileContent, 0, $divStartLoc+1);
		$newCssFileEnd = substr($cssFileContent, $divEndLoc);
		$newCssFileContent = '';
		$newCssFileContent = $newCssFileStart.$newDiv.$newCssFileEnd;
				
		return $newCssFileContent;
	}
	
	private function replaceStylingParam($cssFileContent, $div, $stylingParam, $newParamValue) {
		
		// Find div from css file content
		$divContent = '';
		$divLoc = strpos($cssFileContent, $div);
		$divStartLoc = 0;
		$divEndLoc = 0;
		if($divLoc) {
			$divStartLoc = strpos($cssFileContent, '{', $divLoc);
			$divEndLoc = strpos($cssFileContent, '}', $divStartLoc+1);
			$divContent = substr($cssFileContent, $divStartLoc+1, $divEndLoc-$divStartLoc-1);
			
			//echo $divContent;	// debug
		} else {
			return $divLoc;
		}
		
		// Find styling parameters of div written between {}
		$stylingParamContent = '';
		$stylingParamStartLoc = 0;
		$stylingParamEndLoc = 0;
		$newStylingParam = '';
		if(strlen($divContent)) {
			$stylingParamStartLoc = strpos($divContent, $stylingParam);
			if($stylingParamStartLoc) {
				$stylingParamEndLoc = strpos($divContent, ';', $stylingParamStartLoc);
				$stylingParamContent = substr($divContent, $stylingParamStartLoc, $stylingParamEndLoc-$stylingParamStartLoc+1);
				
				// Parse new styling param
				$paramSplitLoc = strpos($stylingParamContent, ':');
				$newStylingParam = substr_replace($stylingParamContent, $newParamValue, $paramSplitLoc+1);
								
				//echo '<br>'.$stylingParamContent.' new:'.$newStylingParam;	// debug
			} else {
				return false;
			}
		} else {
			return false;
		}
		
		// Replace styling parameter in div
		$newDivStart = substr($divContent, 0, $stylingParamStartLoc);
		$newDivEnd = substr($divContent, $stylingParamEndLoc);
		$newDiv = $newDivStart.$newStylingParam.$newDivEnd;
		
		// Parse new css file
		$newCssFileStart = substr($cssFileContent, 0, $divStartLoc+1);
		$newCssFileEnd = substr($cssFileContent, $divEndLoc);
		$newCssFileContent = $newCssFileStart.$newDiv.$newCssFileEnd;
		
		echo '<br>'.$newDiv;				// debug
		echo '<br>'.$newCssFileContent;		// debug
		
		return $newCssFileContent;
	}
	
	private function readStylingParam($cssFileContent, $div, $stylingParam) {
		
	}
	
	private function readStylingParams($cssFileContent, $div) {
		
		$divParams = array();
		
		// Find div from css file content
		$divContent = '';
		$divLoc = strpos($cssFileContent, $div);
		$divStartLoc = 0;
		$divEndLoc = 0;
		if($divLoc) {
			$divStartLoc = strpos($cssFileContent, '{', $divLoc);
			$divEndLoc = strpos($cssFileContent, '}', $divStartLoc+1);
			$divContent = substr($cssFileContent, $divStartLoc+1, $divEndLoc-$divStartLoc-1);
		} else {
			return $divLoc;
		}
		
		// Find styling parameters of div written between {}
		$divParamCount = substr_count($divContent, ';');
		$stylingParamContent = '';
		$stylingParamStartLoc = 0;
		$stylingParamEndLoc = 0;
		$newStylingParam = '';
		$newDiv = '';
		$stylingParams = array('background', 'font-family', 'font-size', 'color');
		
		for($i=0; $i<$divParamCount; $i++) {
			if(strlen($divContent)) {
				$stylingParamStartLoc = strpos($divContent, $stylingParams[$i]);
				if($stylingParamStartLoc) {
					$stylingParamEndLoc = strpos($divContent, ';', $stylingParamStartLoc);
					$stylingParamContent = substr($divContent, $stylingParamStartLoc, $stylingParamEndLoc-$stylingParamStartLoc+1);
					$paramSplitLoc = strpos($stylingParamContent, ':');
					$divParams[$stylingParams[$i]] = substr($stylingParamContent, $paramSplitLoc+1, strlen($stylingParamContent)-$paramSplitLoc-2);
					$divParams[$stylingParams[$i]] = str_replace(', ', ',', $divParams[$stylingParams[$i]]);
					//echo $stylingParams[$i].' = '.$divParams[$stylingParams[$i]];	// debug
				} else {
					//return false;
				}
			} else {
				$divParams[$stylingParams[$i]] = $this->defaultCssParams[$stylingParams[$i]];
			}	
		}
		
		return $divParams;
	}
}

?>