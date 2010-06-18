<?php
/**
 *  AccountViewBox - Class to make accountviewbox
 *
 *   Copyright (c) <2010>, Sami Suuriniemi <sami.suuriniemi@student.samk.fi>
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
 *  Email - class
 *
 *  @package    plugins
 *  @author     Sami Suuriniemi
 *  @copyright  2010 Sami Suuriniemi
 *  @license    GPL v2
 *  @version    1.0
 */ 
class Oibs_Controller_Plugin_AccountViewBox {
	private $_tabs = array();
	private $_name;
	private $_header;
	private $_partial;
	
	public function __construct() {
		
	}

	public function addTab($header, $type, $class, $extraText = "") {
		$this->_tabs[] = array(	'header' 	=> $header,
								'type'		=> $type,
								'class'		=> $class,
								'extraText' => $extraText);
		return $this;	
	}
	
	public function getTabs() {
		return $this->_tabs;
	}
	
	public function setHeader($header) {
		$this->_header = $header;
		return $this;
	} 
	
	public function getHeader() {
		return $this->_header;
	}
	
	public function getPartial() {
		return $this->_partial;
	}
	
	public function setName($name) {
		$this->_name = $name;
		$this->_partial = "partials/account_view_user_box_contents_".$name.".phtml";
		return $this;
	}
	
	public function getName() {
		return $this->_name;
	}
}
