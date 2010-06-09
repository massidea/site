<?php
/**
 *  Plugin for handling bbCode
 *
 *  Copyright (c) <2009>, Markus Riihelä
 *  Copyright (c) <2009>, Mikko Sallinen
 *
 *  This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License 
 *  as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied  
 *  warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for  
 *  more details.
 * 
 *  You should have received a copy of the GNU General Public License along with this program; if not, write to the Free 
 *  Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 *  License text found in /license/
 */

/**
 *  Oibs_Controller_Plugin_BBCode - class
 *  
 *  Very basic class to handle bbCode. This needs further development.
 *
 *  @author     Markus Riihelä & Mikko Sallinen
 *  @copyright  2009 Markus Riihelä & Mikko Sallinen
 *  @license    GPL v2
 *  @version    1.0
 */ 
class Oibs_Controller_Plugin_BBCode extends Zend_Controller_Plugin_Abstract 
{
    // bbCode tags in regular expression format
    private $tag = array(
        '/\[\*\]/',
        '/\[b\]/',
        '/\[\/b\]/',
        '/\[i\]/',
        '/\[\/i\]/',
        '/\[u\]/',
        '/\[\/u\]/',
        '/\[img\](.+?)\[\/img\]/s',
        '/\[url\](.+?)\[\/url\]/s',
        '/\[url=(.+?)\](.+?)\[\/url\]/s',
        '/\[original=(.+?)\]/',
        '/\[\/original\]/',
        '/\[align=(left|center|right|justify)\]/',
        '/\[\/align\]/',
        '/\[list\]/',
        '/\[\/list\]/',
        '/\[nlist\]/',
        '/\[\/nlist\]/',      
        '/\[quote\]/',
        '/\[quote=(.+?)\]/',
        '/\[\/quote\]/',     
        '/\[code\]/',
        '/\[\/code\]/',       
        '/\[color=(.+?)\]/', 
        '/\[\/color\]/',       
        '/\[size=(.+?)\]/',
        '/\[\/size\]/',
    );
    
    // Replacing tags
    private $tagReplace = array(
        '<li>',
        '<strong>',
        '</strong>',
        '<em>',
        '</em>',
        '<span style="text-decoration:underline;">',
        '</span>',
        '<img src="$1" alt="Image"/>',
        '<a href="$1">$1</a>',
        '<a href="$1">$2</a>',
        '<div class="ori"><div class="ori_t">Original message from $1</div>',
        '</div>',
        '<div align="$1">',
        '</div>',
        '<ul>',
        '</ul>',
        '<ol>',
        '</ol>',
        '<div class="quote"><div class="quote_t">Quote</div>',
        '<div class="quote"><div class="quote_t">Quote <span class="quote_st">$1</span></div>',
        '</div>',
        '<div class="code"><div class="code_t">Code</div>',
        '</div>',
        '<span style="color:$1;">',
        '</span>',
        '<span style="font-size:$1%;">',
        '</span>',
    );
    
    /**
    *   Constructor
    *
    * 
    */
    /*
    public function __construct($permanent = false) 
    {
        $this->permanent=$permanent;
    }
    */
    
    /**
    *   parse
    *
    *   Parses bbCode string to html.
    *
    *   @param string $str BbCode string
    *   @return string
    */
    function parse($str)
    {
        $str = nl2br(htmlentities($str, ENT_COMPAT, 'UTF-8'));
        
        $str = preg_replace($this->tag, $this->tagReplace, $str);
        
        return $str;
    }
}