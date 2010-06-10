<?php
/**
 *  Breadcrumbs,
 *
* 	Copyright (c) <2008>, Matti Särkikoski <matti.sarkikoski@cs.tamk.fi>
* 	Copyright (c) <2008>, Jani Palovuori <jani.palovuori@cs.tamk.fi>
*
* 	All rights reserved.
*
* 	Redistribution and use in source and binary forms, with or without
* 	modification, are permitted provided that the following conditions are met:
*	     * Redistributions of source code must retain the above copyright
* 	      notice, this list of conditions and the following disclaimer.
* 	    * Redistributions in binary form must reproduce the above copyright
*	       notice, this list of conditions and the following disclaimer in the
*	       documentation and/or other materials provided with the distribution.
*	     * Neither the name of the organization nor the
*	       names of its contributors may be used to endorse or promote products
*	       derived from this software without specific prior written permission.
*
*	 THIS SOFTWARE IS PROVIDED BY COPYRIGHT HOLDERS ''AS IS'' AND ANY
*	 EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
*	 WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
*	 DISCLAIMED. IN NO EVENT SHALL COPYRIGHT HOLDERS BE LIABLE FOR ANY
*	 DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
*	 (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
*	 LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
*	 ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
*	 (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
*	 SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
/**
 *  Breadcrumbs - class
 *
 *  @package 	Library
 *  @author 	Matti Särkikoski & Jani Palovuori
 *  @copyright 	2008 Matti Särkikoski & Jani Palovuori
 *  @license 	New BSD License
 *  @version 	1.0
 */ 
	class Oibs_Controller_Plugin_BreadCrumbs
	{
		private $_trail = array();
		
		public function addStep($title, $link = '')
		{
			$this->_trail[] = array('title' => $title, 'link' => $link);
		}
		
		public function getTrail()
		{
			return $this->_trail;
		}
		
		public function getTitle()
		{
			if (count($this->_trail) == 0)
				return null;
			
			return $this->_trail[count($this->_trail) - 1]['title'];
		}
}