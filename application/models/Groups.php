<?php
/**
 *  Groups -> Groups database model for groups table.
 *
 *  Copyright (c) <2010>, Mikko Aatola
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
 *  UserRoles - class
 *
 *  @package    models
 *  @author     Mikko Aatola
 *  @copyright  2010 Mikko Aatola
 *  @license    GPL v2
 *  @version    1.0
 */
class Default_Model_Groups extends Zend_Db_Table_Abstract
{
    // Table name
    protected $_name = 'usr_groups_grp';

    // Table primary key
    protected $_primary = 'id_grp';

	/**
	 * Returns data from the user groups table by group id.
	 *
	 * @param $id_grp id of the group
	 * @return array of data of the group specified by id_grp
	 */
	public function getMetaData($id_grp) {

        $select = $this->_db->select()
            ->from('usr_groups_grp', array('id_grp', 'description' => 'description_grp'))
            ->where('usr_groups_grp.id_grp = ?', $id_grp)
            ->join('users_usr',
            'users_usr.id_usr = usr_groups_grp.id_usr',
            array('founder' => 'login_name_usr'))
            ->join('meta',
            'meta.id_meta = usr_groups_grp.id_meta',
            array('location' => 'location'))
            ->join('jobs_job',
            'meta.id_job = jobs_job.id_job',
            array('job' => 'description_job'))
            ->join('categories_ctg',
            'meta.id_ctg = categories_ctg.id_ctg',
            array('category' => 'title_ctg'))
            ->join('offer_needs',
            'meta.id_offer = offer_needs.id_on',
            array('offer' => 'title_on'))
            ->join('offer_needs',
            'meta.id_needs = offer_needs.id_on',
            array('need' => 'title_on'))
            ->joinLeft( "usr_has_grp",
            "usr_has_grp.id_grp = usr_groups_grp.id_grp",
            array("membersCount" => "count(*)"))
        ;
        $select_atr = $this->_db->select()
            ->from('usr_groups_grp', array('id_grp'))
            ->where('id_grp = ?', $id_grp)
            ->join('meta',
            'meta.id_meta = usr_groups_grp.id_meta',
            array())
            ->join('meta_has_atr',
            'meta.id_meta = meta_has_atr.id_meta',
            array())
            ->join('attributes_atr',
            'meta_has_atr.id_atr = attributes_atr.id_atr',
            array('attribute' => 'name_atr'))
        ;

        $result = $this->_db->fetchAll($select);
        if ($result != null) {
            $result_atr = $this->_db->fetchAll($select_atr);
            $i = 0;
            foreach ($result_atr as $atr) {
                $result[0]['attributes'][$i] = $atr['attribute'];
                $i++;
            }
            return $result[0];//->toArray();
        }
        else
            return null;
    }

    public function getGroupData($id_grp)
    {
        $data = $this->_db->select()
            ->from('usr_groups_grp', array('*'))
            ->where('id_grp = ?', $id_grp);

        $result = $this->_db->fetchAll($data);

        return $result[0];
    }

    /**
     * Returns all data from the user group table.
     *
     * @author Mikko Aatola
     * @return array of data of every group in groups table
     */
    public function getAllGroups()
    {
        $data = $this->_db->select()
            ->from('usr_groups_grp', array('*'));

        $result = $this->_db->fetchAll($data);

        return $result;
    }

    /**
     * getRecent
     *
     * Gets the specified number of the most recently created groups.
     *
     * @param int $limit
     * @return array
     */
    public function getRecent($limit)
    {
        if (!isset($limit)) $limit = 10;

        $select = $this->select()
                ->order('id_grp DESC')
                ->limit($limit);
        return $this->fetchAll($select)->toArray();
    }

    /**
     * getRecentFromOffset
     *
     * Gets the specified number of the most recently created groups starting from a specified offset.
     *
     * @param int $page
     * @param int $count
     * @return array
     */
    public function getRecentFromOffset($page, $count)
    {
        $select = $this->select()
                ->order('id_grp DESC')
                ->limitPage($page, $count);
        return $this->fetchAll($select)->toArray();
    }

    /**
     * Adds a new group to the db.
     *
     * @author Mikko Aatola
     * @param groupname string
     * @return id of the new group
     */
    public function createGroup($name, $typeId = 1, $description = "", $body = "")
    {
        // Create new empty row.
        $row = $this->createRow();

        // Set group data.
        $row->group_name_grp = $name;
        $row->description_grp = $description;
        $row->body_grp = $body;
        $row->created_grp = new Zend_Db_Expr('NOW()');
        $row->modified_grp = new Zend_Db_Expr('NOW()');
        $row->id_type_grp = $typeId;

        // Save data to database
        $row->save();

        return $row->id_grp;
    }

    public function editGroup($id, $name, $typeId, $description, $body)
    {
		$data = array(
            'group_name_grp' => $name,
            'description_grp' => $description,
            'body_grp' => $body,
            'id_type_grp' => $typeId,
        );
		$where = $this->getAdapter()->quoteInto('id_grp = ?', $id);
		$this->update($data, $where);
    }

    /**
    *   removeGroup
    *   Removes the group from the database
    *
    *   @param int id_grp
    *   @author Mikko Aatola
    */
    public function removeGroup($id_grp = 0)
    {
        if (!$id_grp) return false;

        // Delete the group's campaigns.
        $data = $this->_db->select()
            ->from('campaigns_cmp', 'id_cmp')
            ->where('id_grp_cmp = ?', $id_grp);
        $campaigns = $this->_db->fetchAll($data);
        $cmpModel = new Default_Model_Campaigns();
        foreach ($campaigns as $cmp)
            $cmpModel->removeCampaign($cmp['id_cmp']);

        // Delete group weblinks
        $grpWeblinksModel = new Default_Model_GroupWeblinks();
        $grpWeblinksModel->removeGroupWeblinks($id_grp);

        // Delete group-admin links from grp_has_admin_usr.
        $grpAdm = new Default_Model_GroupAdmins();
        $grpAdm->removeAdminsFromGroup($id_grp);

        // Delete groups files
        $filesModel = new Default_Model_Files();
        $filesModel->removeFiles($id_grp, "group");

        // Delete group.
        $where = $this->getAdapter()->quoteInto('id_grp = ?', $id_grp);
        $this->delete($where);
    } // end of removeGroup

    /**
     * Checks if a group exists in db.
     *
     * @author Mikko Aatola
     * @param groupname string
     */
    public function groupExists($groupname)
    {
        $select = $this->_db->select()
                        ->from('usr_groups_grp', array('group_name_grp'))
                        ->where('group_name_grp = ?', $groupname);
        $result = $this->_db->fetchAll($select);

        if (!isset($result[0])) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Checks if a group exists in db.
     *
     * @author Mikko Korpinen
     * @param int $id_grp
     * @return boolean
     */
    public function groupExistsById($id_grp)
    {
        $select = $this->select()->where('id_grp = ?', $id_grp);

        $result = $this->fetchAll($select)->toArray();

        return !empty($result);
    }

    /**
     * getGroupTypeId - Get group type id by group id
     *
     * @param int $id_grp
     * @return string type id
     */
    public function getGroupTypeId($id_grp)
    {
        $data = $this->_db->select()
            ->from('usr_groups_grp', array('id_type_grp'))
            ->where('id_grp = ?', $id_grp);

        $result = $this->_db->fetchAll($data);

        return $result[0]['id_type_grp'];
    }

	/**
	 *
	 * @param $pattern
	 * @return array
	 */
	public function getGroupByFilter($pattern) {
        $adapter = $this->getAdapter();
        $sql = 'SELECT *
                FROM usr_groups_grp, meta, jobs_job, attributes_atr, meta_has_atr, users_usr
                WHERE usr_groups_grp.id_meta = meta.id_meta AND meta.id_job = jobs_job.id_job AND meta.id_meta = meta_has_atr.id_meta
                AND meta_has_atr.id_atr = attributes_atr.id_atr AND usr_groups_grp.id_usr = users_usr.id_usr
                AND (description_grp LIKE "%' . $pattern . '%" OR description_job LIKE "%' . $pattern . '%" OR name_atr LIKE "%' . $pattern . '%" OR login_name_usr LIKE "%' . $pattern . '%");';

        $statement = $adapter->query($sql);

        $result = $statement->fetchAll();
        return $result;
    }

}
