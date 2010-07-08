<?php
/**
 *  TagController -> Viewing tags
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
 *  Tagsizes - class
 *
 *  @package        controllers
 *  @author         
 *  @copyright      
 *  @license        GPL v2
 *  @version        1.0
 */
class Zend_Controller_Action_Helper_Tagsizes extends Zend_Controller_Action_Helper_Abstract
{
    /**
    *
    *
    */
    public function direct($tags = null)
    {
        // return $this->tag2($tags);
    }
    
    /**
    *
    *
    */
    public function tagCalc(array $tags = array(), $minSize = 50, $maxSize = 300, $step = 30)
    {
        // resize tags
        foreach ($tags as $k => $tag) {
            $size = round($minSize + ($tag['count'] * $step));
            
            if ($size > $maxSize) {
                $size = $maxSize;
            }
            
            $tags[$k]['tag_size'] = $size;
        }
        
        return $tags;
    }
    
    
    /**
     * Is tags running numer divisible by 2?
     * 
     * @return array
     */
    public function isTagDivisibleByTwo(array $tags = array()) {
    	foreach($tags as $key => $value) {    		
    		if($key%2) {
    			$tags[$key]['divisible'] = true;
    		} else {
    			$tags[$key]['divisible'] = false;
    		}
    	}
    	
    	return $tags;
    }
    
    /**
    *
    *
    */
    public function tag2(array $tags = array(), $minFontSize = 50, $maxFontSize = 300)
    {		
		$maxTagCount = null;
		$minTagCount = null;
        
		foreach ($tags as $tag) {
			$cnt = $tag['count'];
		
			if($maxTagCount == null || $cnt > $maxTagCount) {
				$maxTagCount = $cnt;
            }
				
			if($minTagCount == null || $cnt < $minTagCount) {
				$minTagCount = $cnt;
            }
		}
		
		$spread = $maxTagCount - $minTagCount;
		if($spread == 0) {
			$spread = 1;
        }
		
		$step = ($maxFontSize - $minFontSize) / $spread;
        
		foreach ($tags as $k => $tag) {			
			$tags[$k]['tag_size'] = round(
                $minFontSize + (($tag['count'] - $minTagCount) * $step)
            );		
		}
        
        return $tags;
    }
}