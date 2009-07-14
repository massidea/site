<?php
/**
 *  IndexController -> main pages
 *
* 	Copyright (c) <2008>, Matti Särkikoski <matti.sarkikoski@cs.tamk.fi>
* 	Copyright (c) <2008>, Jani Palovuori <jani.palovuori@cs.tamk.fi>
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
 *  IndexController - class
 *
 *  @package 	controllers
 *  @author 	Matti Särkikoski & Jani Palovuori
 *  @copyright 	2008 Matti Särkikoski & Jani Palovuori
 *  @license 	GPL v2
 *  @version 	1.0
 */
class IndexController extends Oibs_Controller_CustomController
{
	function init()
	{
		parent::init();
	}

	/**
	 *	Show mainpage and list newest and most viewed ideas and problems
	 */
    function indexAction()
    {    	
		//$this->view->title = "OIBS Home";
		$this->view->title = "index-home";
    }
	/*
	function jepjepAction()
	{
	}
	*/
}
