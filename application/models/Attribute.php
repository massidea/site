<?php
    /**
     *  Attribute -> Attribute database model for attribute table.
     *
     *     Copyright (c) <2012>, Wilhelm Hofbauer
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
     *  Attribute - class
     *
     *  @package     models
     *  @author      Wilhelm Hofbauer
     *  @copyright   2012 Wilhelm Hofbauer
     *  @license     GPL v2
     *  @version     1.0
     */
class Default_Model_Attribute extends Zend_Db_Table_Abstract
{
    // Table name
    protected $_name = 'attributes_atr';

    // Primary key of table
    protected $_primary = 'id_atr';

    public function getAttributes() {
        $select = $this->_db->select()->from("attributes_atr", array('id_atr', 'name_atr'));

        $data = $this->_db->fetchAll($select);
        return $data;
    }

    public function getAttributeById($id = 0) {
        $rowset = $this->find((int)$id)->current();

        if ($rowset != null)
            return $rowset->toArray();
        else
            return null;
    }
}