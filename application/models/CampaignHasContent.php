<?php
/**
 *  CampaignHasContent database model for cmp_has_cnt table.
 *
 *     Copyright (c) <2010>, Mikko Aatola
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
 *  UserHasGroup - class
 *
 *  @package    models
 *  @author     Mikko Aatola
 *  @copyright  2010 Mikko Aatola
 *  @license    GPL v2
 *  @version    1.0
 */
class Default_Model_CampaignHasContent extends Zend_Db_Table_Abstract
{
    // Name of table
    protected $_name = 'cmp_has_cnt';

    // Tables reference map
    protected $_referenceMap    = array(
        'Campaign' => array(
            'columns'           => array('id_cmp'),
            'refTableClass'     => 'Default_Model_Campaigns',
            'refColumns'        => array('id_cmp')
        ),
         'Content' => array(
            'columns'           => array('id_cnt'),
            'refTableClass'     => 'Default_Model_Content',
            'refColumns'        => array('id_cnt')
        )

    );

    /**
     * Links a content to a campaign.
     *
     * @author Mikko Aatola
     * @param id_cmp id of the campaign to link the content to
     * @param id_cnt id of the content to add to the campaign
     */
    public function addContentToCampaign($id_cmp = 0, $id_cnt = 0)
    {
        if ($id_cmp != 0 && $id_cnt != 0) {
            // Create a new row.
            $row = $this->createRow();

            // Set values.
            $row->id_cmp = $id_cmp;
            $row->id_cnt = $id_cnt;

            // Add row to db.
            $row->save();
        }
    }

    /*
     * Unlinks a content from a campaign.
     *
     * @author Mikko Aatola
     * @param id_cmp campaign id
     * @param id_cnt content id
     */
    public function removeContentFromCampaign($id_cmp = 0, $id_cnt = 0)
    {
        $return = false;

        $where = $this->getAdapter()->quoteInto('id_cmp = ?', $id_cmp);
        $where = $this->getAdapter()->quoteInto(
            "$where AND id_cnt = ?", $id_cnt);
        if($this->delete($where)) {
            $return = true;
        }

        return $return;
    }

    /**
     * Checks if a content is linked to a campaign.
     *
     * @author Mikko Aatola
     * @param id_cmp campaign id
     * @param id_cnt content id
     * @return true if the content is linked to the campaign, false if not
     */
    public function campaignHasContent($id_cnt, $id_cmp)
    {
        $select = $this->_db->select()
                        ->from('cmp_has_cnt', array('id_cmp', 'id_cnt'))
                        ->where('id_cmp = ?', $id_cmp)
                        ->where('id_cnt = ?', $id_cnt);
        $result = $this->_db->fetchAll($select);

        return isset($result[0]);
    }

    /**
     * Get all campaigns where content is linked.
     *
     * @author Mikko Korpinen
     * @param id_cnt content id
     * @return array
     */
    public function getContentCampaigns($id_cnt)
    {
        $result = array();

        $select = $this->_db->select()
                            ->from(array('chc' => 'cmp_has_cnt'),
                                   array('id_cmp', 'id_cnt'))
                            ->joinLeft(array('cmp' => 'campaigns_cmp'),
                                   'cmp.id_cmp = chc.id_cmp',
                                   array('id_cmp', 'id_grp_cmp', 'name_cmp',
                                         'ingress_cmp', 'description_cmp', 'created_cmp'))
                            ->joinLeft(array('grp' => 'usr_groups_grp'),
                                             'grp.id_grp = cmp.id_grp_cmp',
                                             array('id_grp', 'group_name_grp'))
                            ->where('chc.id_cnt = ?', $id_cnt)
                            ->group('chc.id_cmp');

        $result = $this->_db->fetchAll($select);

        return $result;
    }
    
} // end of class
?>