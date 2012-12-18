<?php
    /**
     *  Meta -> Meta database model for meta table.
     *
     *  Copyright (c) <2012>, Wilhelm Hofbauer
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
     *  Meta - class
     *
     *  @package    models
     *  @author     Wilhelm Hofbauer
     *  @copyright  2012 Wilhelm Hofbauer
     *  @license    GPL v2
     *  @version    1.0
     */
class Default_Model_Meta extends Zend_Db_Table_Abstract
{
    // Table name
    protected $_name = 'meta';

    // Table primary key
    protected $_primary = 'id_meta';

    /**
     * Adds a new meta to the db.
     *
     * @author Wilhelm Hofbauer
     * @param groupname string
     * @return id of the new group
     */
    public function createMeta($jobId, $ctgId, $location, $offerId = 0, $needsId = 0, $attributes)
    {
        // Create new empty row.
        $row = $this->createRow();

        // Set meta data.
        $row->id_job = $jobId;
        $row->id_ctg = $ctgId;
        $row->location = $location;
        if ($offerId != 0)
            $row->id_offer = $offerId;
        if ($needsId != 0)
            $row->id_needs = $needsId;

        // Save data to database
        $row->save();

        $model = new Default_Model_MetaHasAttributes();
        $model->createAttributes($row->id_meta, $attributes);

        return $row->id_meta;
    }

    public function editMeta($id, $jobId, $ctgId, $location, $offerId = 0, $needsId = 0, $attributes)
    {
        // Get the original meta
        $meta = $this->getMetaRow($id);

        // Unset fields that are not going to be updated
        unset($meta['id_meta']);

        $meta['id_job'] = $jobId;
        $meta['id_ctg'] = $ctgId;
        $meta['location'] = htmlspecialchars($location);
        $meta['id_offer'] = $offerId;
        $meta['id_needs'] = $needsId;

        $where = $this->getAdapter()->quoteInto('`id_meta` = ?', $id);

        if(!$this->update($meta, $where)) {
            $return = false;
        } else {
            $return = $id;
        }

        $model = new Default_Model_MetaHasAttributes();
        $model->createAttributes($id, $attributes);

        return $return;
    }

    public function getMetaRow($id = -1)
    {
        if($id == -1) {
            $id = $this->_id;
        } // end if

        return $this->find((int)$id)->current()->toArray();
    }

    public function getMetaById($id = 0) {
        $rowset = $this->find((int)$id)->current();

        if ($rowset != null)
            return $rowset->toArray();
        else
            return null;
    }
}