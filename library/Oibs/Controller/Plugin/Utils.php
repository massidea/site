<?php

/**
 *  Utils - class with random small static utility functions
 *
 *   Copyright (c) <2010>, Sami Suuriniemi <sami.suuriniemi@samk.fi>
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
 *  Utils
 *
 *  @package    Oibs/Controller/Plugin
 *  @author     Sami Suuriniemi
 *  @copyright  2010 Sami Suuriniemi
 *  @license    GPL v2
 *  @version    1.0
 */ 

class Oibs_Controller_Plugin_Utils {
    /** clickable
     * 
     * Makes an url in text clickable link
     * loaned from http://www.php.net/manual/en/function.preg-replace.php#85722
     * also makes youtube links viewable videos
     * 
     * @author tal at ashkenazi dot co dot il
     * @param string $url
     * @return string
     */
    public static function clickable($url, $embed = false){
        $url                                    =    str_replace("\\r","\r",$url);
        $url                                    =    str_replace("\\n","\n<BR>",$url);
        $url                                    =    str_replace("\\n\\r","\n\r",$url);
		$urls									=	 explode("\n", $url);
        
		$return = "";
		foreach ($urls as $url) { 
	        $youtubePattern = "/youtube.com\/watch\?v\=([[:alnum:]]{11})/";
	        if (preg_match_all($youtubePattern, $url, $out) && $embed) { // If its a youtube link
		        foreach($out[1] as $match) {
					$return .= 
						'<object>'.
					  '<param name="movie" value="http://www.youtube.com/v/'.$match.'&hl=en&fs=1"></param>'.
  							'<param name="allowFullScreen" value="true"></param>'.
  							'<param name="allowScriptAccess" value="always"></param>'.
							'<embed src="http://www.youtube.com/v/'.$match.'&hl=en&fs=1"'.
						  		' type="application/x-shockwave-flash"'.
						  		' allowfullscreen="true"'.
						  		' allowscriptaccess="always"'.
						  		' width="425" height="344"></embed>'.
						'</object>';
		        }
	        } else { // If not youtube link
		        
		        $in=array(
		      	  	'`((?:https?|ftp)://\S+[[:alnum:]]/?)`si',
		        	'`((?<!//)(www\.\S+[[:alnum:]]/?))`si',
		        );
		        $out=array(
			        '<a href="$1"  rel=nofollow>$1</a> ',
			        '<a href="http://$1" rel=\'nofollow\'>$1</a>',
		        );

		        $return .= preg_replace($in,$out,$url);
	        }
	        $return .= "\n";
		}
		return $return;
    }
}