<?php
/**
 *  UserFavourites -> UserFavourites database model for favourites table.
 *
 * 	Copyright (c) <2010> Jari Korpela
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
 *  UserFavourites - class
 *
 *  @package    models
 *  @author     Jari Korpela
 *  @copyright  2010 Jari Korpela
 *  @license    GPL v2
 *  @version    1.0
 */

class Default_Model_UserFavourites extends Zend_Db_Table_Abstract
{
	// Name of table
	protected $_name = 'usr_favourites_ufv';

	// Primary keys of table
	protected $_primary = array('id_usr');

	protected $_referenceMap = array(
		 'FavouritesUser' => array(
            'columns'           => array('id_usr'),
            'refTableClass'     => 'Default_Model_User',
            'refColumns'        => array('id_usr')
	)
	);
	
	public function setFavouritePublicTrue($id_usr = 0)
	{
		$return = false;
		if($id_usr != 0) {
			$data = array('public' => 1);
			$where = $this->getAdapter()->quoteInto('id_usr = ?', $id_usr);

			if(!$this->update($data,$where)) {
				$return = false;
			} else {
				$return = true;
			}

		}
		return $return;
	}
	
	public function setFavouriteNotifyTrue($id_usr = 0)
	{
		$return = false;
		if($id_usr != 0) {
			$data = array('notify' => 1);
			$where = $this->getAdapter()->quoteInto('id_usr = ?', $id_usr);

			if(!$this->update($data,$where)) {
				$return = false;
			} else {
				$return = true;
			}

		}
		return $return;
	}
	
	public function setFavouritePublicFalse($id_usr = 0)
	{
		$return = false;
		if($id_usr != 0) {
			$data = array('public' => 0);
			$where = $this->getAdapter()->quoteInto('id_usr = ?', $id_usr);

			if(!$this->update($data,$where)) {
				$return = false;
			} else {
				$return = true;
			}

		}
		return $return;
	}
	
	public function setFavouriteNotifyFalse($id_usr = 0)
	{
		$return = false;
		if($id_usr != 0) {
			$data = array('notify' => 0);
			$where = $this->getAdapter()->quoteInto('id_usr = ?', $id_usr);

			if(!$this->update($data,$where)) {
				$return = false;
			} else {
				$return = true;
			}

		}
		return $return;
	}

	public function addUserToFavouritesSettings($id_usr = 0)
	{
		$return = false;

		if($id_usr != 0) {
			$content = $this->createRow();

			$content->id_usr = $id_usr;
			$content->public = 0;
			$content->notify = 0;

			if(!$content->save()) {
				$return = false;
			} else {
				$return = true;
			}
		}
		return $return;
	}
	
	public function getFavouritePublicSetup($id_usr = 0)
	{
		if($id_usr != 0) {
			$select = $this->select()
			->from($this, array('public'))
			->where('id_usr = ?',$id_usr);

			$result = $this->fetchAll($select)->toArray();
			return $result;
		} else {
			return NULL;
		}
	}
	
	public function getFavouriteNotifySetup($id_usr = 0)
	{
		if($id_usr != 0) {
			$select = $this->select()
			->from($this, array('notify'))
			->where('id_usr = ?',$id_usr);

			$result = $this->fetchAll($select)->toArray();
			return $result;
		} else {
			return NULL;
		}
	}
	
	public function getAllFavouriteSetup($id_usr = 0)
	{
		if($id_usr != 0) {
			$select = $this->select()
			->from($this, array('public'))
			->where('id_usr = ?',$id_usr);

			$result = $this->fetchAll($select)->toArray();
			return $result;
		} else {
			return NULL;
		}
	}
} // end of class

?>