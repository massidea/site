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
	protected $_name = 'usr_favourites_fvr';

	// Primary keys of table
	protected $_primary = array('id_cnt_fvr','id_usr_fvr');

	protected $_referenceMap = array(
        'FavouritesContent' => array(
            'columns'           => array('id_cnt_fvr'),
            'refTableClass'     => 'Default_Model_Content',
            'refColumns'        => array('id_cnt')
	),
		 'FavouritesUser' => array(
            'columns'           => array('id_usr_fvr'),
            'refTableClass'     => 'Default_Model_User',
            'refColumns'        => array('id_usr')
	),
	);
	
	//Get all favourite content ID:s that user has
	public function getAllFavouriteContentIdsFromUser($id_usr_fvr = 0)
	{
		if($id_usr_fvr != 0) {
			$select = $this->select()
			->from($this, array('id_cnt_fvr'))
			->where('id_usr_fvr = ?',$id_usr_fvr);

			$result = $this->fetchAll($select)->toArray();
			return $result;
		} else {
			return NULL;
		}
	}

	//Get all user ID:s that have same favourite content ID:s
	public function getAllUserIdsFromFavouriteContent($id_cnt_fvr = 0)
	{
		if($id_cnt_fvr != 0) {
			$select = $this->select()
			->from($this, array('id_usr_fvr'))
			->where('id_cnt_fvr = ?',$id_cnt_fvr);

			$result = $this->fetchAll($select)->toArray();
			return $result;
		} else {
			return NULL;
		}
	}

	//Get favourites count by user
	public function getFavouritesCountByUser($id_usr_fvr = 0)
	{
		if($id_usr_fvr != 0) {
			$select = $this->select()
			->from($this, array('favourites_count_fvr' => 'COUNT(id_cnt_fvr)'))
			->where('id_usr_fvr = ?',$id_usr_fvr);

			$result = $this->fetchAll($select)->toArray();
			return $result;
		} else {
			return NULL;
		}
	}

	//Get users count by favourite content
	public function getUsersCountByFavouriteContent($id_cnt_fvr = 0)
	{
		if($id_cnt_fvr != 0) {
			$select = $this->select()
			->from($this, array('users_count_fvr' => 'COUNT(id_usr_fvr)'))
			->where('id_cnt_fvr = ?',$id_cnt_fvr);

			$result = $this->fetchAll($select)->toArray();
			return $result;
		} else {
			return NULL;
		}
	}

	//Check if user has content favourited
	public function checkIfUserHasFavouriteContent($id_usr_fvr = 0)
	{
		$return = false;

		if($id_usr_fvr != 0) {
			$select = $this->select()
			->from($this, array('*'))
			->where('id_usr_fvr = ?',$id_usr_fvr);

			$this->fetchAll($select)->count() == 0 ? $return = false : $return = true;
				
		}
		return $return;
	}

	//Check if content is added to favourites by any user
	public function checkIfContentIsFavourited($id_cnt_fvr = 0)
	{
		$return = false;

		if($id_cnt_fvr != 0) {
			$select = $this->select()
			->from($this, array('*'))
			->where('id_cnt_fvr = ?',$id_cnt_fvr);

			$this->fetchAll($select)->count() == 0 ? $return = false : $return = true;
				
		}
		return $return;
	}

	//Check if content is added to user favourites
	public function checkIfContentIsUsersFavourite($id_cnt_fvr = 0, $id_usr_fvr = 0)
	{
		$return = false;

		if($id_cnt_fvr != 0 && $id_usr_fvr != 0) {
			$select = $this->select()
			->from($this, array('*'))
			->where('id_cnt_fvr = ?',$id_cnt_fvr)
			->where('id_usr_fvr = ?',$id_usr_fvr);

			$this->fetchAll($select)->count() == 0 ? $return = false : $return = true;
				
		}
		return $return;
	}

	//Add content to favourites
	public function addContentToFavourites($id_cnt_fvr = 0, $id_usr_fvr = 0)
	{
		$return = false;

		if($id_cnt_fvr != 0 && $id_usr_fvr != 0) {
			$content = $this->createRow();

			$content->id_cnt_fvr = $id_cnt_fvr;
			$content->id_usr_fvr = $id_usr_fvr;

			if(!$content->save()) {
				$return = false;
			} else {
				$return = true;
			}
		}
		return $return;
	}
	
	//Removes all favourite content from user by user id
	//This is used when user wants to remove all of his favourite content
	public function removeAllFavouriteContentByUserId($id_usr_fvr = 0)
	{
		$return = false;

		if($id_usr_fvr != 0) {

			$where = $this->getAdapter()->quoteInto('id_usr_fvr = ?', (int)$id_usr_fvr);

			if(!$this->delete($where)) {
				$return = false;
			} else {
				$return = true;
			}
		}
		return $return;
	}
	
	//Removes favourite content from user by user id
	//This is used when user wants to remove single content from his favourite list
	public function removeUserFavouriteContent($id_cnt_fvr = 0, $id_usr_fvr = 0)
	{
		$return = false;

		if($id_cnt_fvr != 0 && $id_usr_fvr != 0) {
			$where[] = $this->getAdapter()->quoteInto('id_usr_fvr = ?', (int)$id_usr_fvr);
			$where[] = $this->getAdapter()->quoteInto('id_cnt_fvr = ?', (int)$id_cnt_fvr);
			
			if(!$this->delete($where)) {
				$return = false;
			} else {
				$return = true;
			}
		}
		return $return;
	}
	
	//Removes all favourite content from users by content id
	//If content is deleted this is used to remove references to deleted content
	public function removeContentsFromFavouritesByContentId($id_cnt_fvr = 0) 
	{
		$return = false;

		if($id_cnt_fvr != 0 && $id_usr_fvr != 0) {
			$where = $this->getAdapter()->quoteInto('id_cnt_fvr = ?', (int)$id_cnt_fvr);

			if(!$this->delete($where)) {
				$return = false;
			} else {
				$return = true;
			}
		}
		return $return;
	}


} // end of class

/*
 $fav = new Default_Model_UserFavourites();
 $count = $fav->getFavouritesCountByUser(2);
  print_r($count);
 $conid = $fav->getAllFavouriteContentIdsFromUser(2);
 print_r($conid);
 $usrid = $fav->getAllUserIdsFromFavouriteContent(141);
 print_r($usrid);
 $exists[] = $fav->checkIfUserHasFavouriteContent(2);
 $exists[] = $fav->checkIfUserHasFavouriteContent(22);
print_r($exists);
$delete[] = $fav->removeUserFavouriteContent(53, 2);
print_r($delete);
die;
 */
?>