<?php
/**
 *  CampaignWeblinks -> CampaignWeblinks database model for cmp_weblinks_cwl table.
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
 *  CampaignWeblinks - class
 *
 *  @package 	models
 *  @author     Mikko Korpinen
 *  @copyright 	2010 Mikko Korpinen
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class Default_Model_CampaignWeblinks extends Zend_Db_Table_Abstract
{
	// Name of table
    protected $_name = 'cmp_weblinks_cwl';
	
	// Primary key of table
	protected $_primary = 'id_cwl';
	
	// Tables reference map
	protected $_referenceMap    = array(
        'CampaignWeblinks' => array(
            'columns'           => array('id_usr_cwl'),
            'refTableClass'     => 'Default_Model_Campaigns',
            'refColumns'        => array('id_cmp')
        )
    );
	
    /**
     * setWeblink - Set web link
     * 
     * @author Mikko Korpinen
     * @param int $id_cmp
     * @param string (45) $name
     * @param string (150) $url
     * @param int $count
     */
    public function setWeblink($id_cmp, $name, $url, $count) {
        // get old values
        $select = $this->select()
            ->from($this, array('name_cwl', 'url_cwl'))
            ->where('id_cmp_cwl = ?', $id_cmp)
            ->where('count_cwl = ?', $count);
        $result = $this->fetchAll($select)->toArray();

        $new = array(
                'id_cmp_cwl' => $id_cmp,
                'name_cwl' => $name,
                'url_cwl' => $url,
                'count_cwl' => $count,
                'created_cwl' => new Zend_Db_Expr('NOW()'),
                'modified_cwl' => new Zend_Db_Expr('NOW()')
        );

        $update = array(
                'id_cmp_cwl' => $id_cmp,
                'name_cwl' => $name,
                'url_cwl' => $url,
                'count_cwl' => $count,
                'modified_cwl' => new Zend_Db_Expr('NOW()')
        );

        // if old values found (= not new link field)
		if(isset($result[0]['name_cwl'])) {
            if ($result[0]['name_cwl'] == $name
            && $result[0]['url_cwl'] == $url
            && $result[0]['count_cwl'] == $count) {
                return true;    // dont set the same values
            }

            // update old values
            $where[] = $this->getAdapter()->quoteInto('id_cmp_cwl = ?', $id_cmp);
            $where[] = $this->getAdapter()->quoteInto('count_cwl = ?', $count);

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
				->where('id_cwl = ?', $id);

		$result = $this->fetchAll($select)->toArray();

        return $result[0];
    }

    /**
     * getCampaignWeblinks - Get campaign weblinks
     *
     * @author Mikko Korpinen
     * @param int $id_cmp
     * @return array
     */
    public function getCampaignWeblinks($id_cmp) {
        $select = $this->select()
				->from($this, array('*'))
				->where('id_cmp_cwl = ?', $id_cmp)
                ->order('count_cwl');

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
     * removeCampaignWeblinks - Remove campaign weblinks
     *
     * @author Mikko Korpinen
     * @param int $id_cmp
     */
    public function removeCampaignWeblinks($id_cmp) {
        $where = $this->getAdapter()->quoteInto('id_cmp_cwl = ?', $id_cmp);
        if ($this->delete($where)) {
            return true;
        } else {
            return false;
        }
    }
    
} // end of class
