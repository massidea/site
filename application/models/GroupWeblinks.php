<?php
/**
 *  GroupWeblinks -> GroupWeblinks database model for grp_weblinks_gwl table.
 *
 * 	Copyright (c) <2009>, Mikko Korpinen
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
 *  GroupWeblinks - class
 *
 *  @package 	models
 *  @author     Mikko Korpinen
 *  @copyright 	2010 Mikko Korpinen
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class Default_Model_GroupWeblinks extends Zend_Db_Table_Abstract
{
	// Name of table
    protected $_name = 'grp_weblinks_gwl';
	
	// Primary key of table
	protected $_primary = 'id_gwl';
	
	// Tables reference map
	protected $_referenceMap    = array(
        'GroupWeblinks' => array(
            'columns'           => array('id_grp_gwl'),
            'refTableClass'     => 'Default_Model_Groups',
            'refColumns'        => array('id_grp')
        )
    );
	
    /**
     * setWeblink - Set web link
     * 
     * @author Mikko Korpinen
     * @param int $id_grp
     * @param string (45) $name
     * @param string (150) $url
     * @param int $count
     */
    public function setWeblink($id_grp, $name, $url, $count) {
        // get old values
        $select = $this->select()
            ->from($this, array('name_gwl', 'url_gwl'))
            ->where('id_grp_gwl = ?', $id_grp)
            ->where('count_gwl = ?', $count);
        $result = $this->fetchAll($select)->toArray();

        $new = array(
                'id_usr_gwl' => $id_grp,
                'name_gwl' => $name,
                'url_gwl' => $url,
                'count_gwl' => $count,
                'created_gwl' => new Zend_Db_Expr('NOW()'),
                'modified_gwl' => new Zend_Db_Expr('NOW()')
        );

        $update = array(
                'id_grp_gwl' => $id_grp,
                'name_gwl' => $name,
                'url_gwl' => $url,
                'count_gwl' => $count,
                'modified_gwl' => new Zend_Db_Expr('NOW()')
        );

        // if old values found (= not new link field)
		if(isset($result[0]['name_gwl'])) {
            if ($result[0]['name_gwl'] == $name
            && $result[0]['url_gwl'] == $url
            && $result[0]['count_gwl'] == $count) {
                return true;    // dont set the same values
            }

            // update old values
            $where[] = $this->getAdapter()->quoteInto('id_grp_gwl = ?', $id_grp);
            $where[] = $this->getAdapter()->quoteInto('count_gwl = ?', $count);

			if($this->update($update, $where)) {
                return true;
            } else {
                return false;
            }
        // if no old values (insert new data)
		} else {
            // insert new values
			$this->insert($new);
        }
    }

    /**
     * getWeblink - Get specified weblink
     *
     * @author Mikko Korpinen
     * @param int $id
     * @return array
     */
    public function getWeblink($id) {
        $select = $this->select()
				->from($this, array('*'))
				->where('id_gwl = ?', $id);

		$result = $this->fetchAll($select)->toArray();

        return $result[0];
    }

    /**
     * getGroupWeblinks - Get group weblinks
     *
     * @author Mikko Korpinen
     * @param int $id_grp
     * @return array
     */
    public function getUserWeblinks($id_grp) {
        $select = $this->select()
				->from($this, array('*'))
				->where('id_grp_gwl = ?', $id_grp)
                ->order('count_gwl');

		$result = $this->fetchAll($select)->toArray();

        return $result;
    }

    /**
     * getAllWeblinks - Get all weblinks
     *
     * @author Mikko Korpinen
     * @return array
     */
    public function getAllWeblinks() {
        $select = $this->select()
				->from($this, array('*'));

		$result = $this->fetchAll($select)->toArray();

        return $result;
    }

    /**
     * removeGroupWeblinks - Remove group weblinks
     *
     * @author Mikko Korpinen
     * @param int $id_grp
     */
    public function removeGroupWeblinks($id_grp) {
        $where = $this->getAdapter()->quoteInto('id_grp_gwl = ?', $id_gsr);
        if ($this->delete($where)) {
            return true;
        } else {
            return false;
        }
    }
    
} // end of class
