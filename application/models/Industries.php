<?php
/**
 *  Industries -> Industries database model for industries table.
 *
* 	Copyright (c) <2009>, Markus Riihelä
* 	Copyright (c) <2009>, Mikko Sallinen
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
 *  Industries - class
 *
 *  @package 	models
 *  @author 		Markus Riihelä & Mikko Sallinen
 *  @copyright 	2009 Markus Riihelä & Mikko Sallinen
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class Default_Model_Industries extends Zend_Db_Table_Abstract
{
	// Table name
    protected $_name = 'industries_ind';
    
	// Table primary key
	protected $_primary = 'id_ind';
	
	// Table dependet tables
	protected $_dependentTables = array('Default_Model_ContentHasIndustries');
	
	// Table reference map
	protected $_referenceMap    = array(
        'IndustryLanguage' => array(
            'columns'           => array('id_lng_ind'),
            'refTableClass'     => 'Default_Model_Languages',
            'refColumns'        => array('id_lng')
        ),
		// TEST START
		'IndustryIndustry' => array(
            'columns'           => array('id_parent_ind'),
            'refTableClass'     => 'Default_Model_Industries',
            'refColumns'        => array('id_ind')
        )
		// TEST END
    );
	
	/**
	*	getAllNamesAndIds
	*
	*	Get all industries name and id values.
	*
	*	@return array
	*/
	public function getAllNamesAndIds()
	{
		$select = $this->select()->from($this, array('id_ind', 'name_ind'));
		$result = $this->fetchAll($select)->toArray();
		
        // echo "<pre>"; print_r($result); echo "</pre>"; die;
        
		return $result;
	} // end of getAllNamesAndIds
	
    /** getNameById
    *
    *   @param id_ind   int     id to check a name against
    */
    public function getNameById($id_ind = -1)
    {
        if ($id_ind == -1 || $id_ind == 0) {
            return false;
        }
        
        $select = $this->select()
				->from($this, array('name_ind'))
				->where('`id_ind` = ?', $id_ind);
		$result = $this->fetchAll($select)->toArray();
        return $result[0]['name_ind'];
    }
    
    /**
    *
    *
    */
    public function getById($id = 0)
    {      
        if ($id == 0) {
            return false;
        }
        $select = $this->select()->from($this, array('id_ind', 
                                                     'id_lng_ind', 
                                                     'id_parent_ind', 
                                                     'name_ind'))
                                 ->where('`id_ind` = ?', $id)
                                 ->LIMIT(1);
                                 
		$result = $this->fetchAll($select)->toArray();

        return $result[0];
    }
    
/*
	public function getAllNamesAndIdsTest()
	{
		try{
		
		$id =1;
		
		$select = $this->select()->from(array('n' => 'industries_ind'), array('*'))
								->join(array('p' => 'industries_ind'), '', '')
								->where('n.lft_ind BETWEEN p.lft_ind AND p.rgt_ind')
								->where('p.id_ind = ?', $id)
								->order('n.lft_ind')
								;
		
		$result = $this->fetchAll($select)->toArray();
		
		//$result = $this->_db->query('SELECT n.* FROM industries_ind AS n, industries_ind AS p WHERE n.lft_ind BETWEEN p.lft_ind AND p.rgt_ind AND p.id_ind = ? ORDER BY n.lft_ind;', 1);//fetchAll($select)->toArray();
		
		//$result = $result->fetchAll();
		
		}catch(Zend_Exception $e){
			echo '<pre>';
			print_r($e);
			echo '</pre>';
		}
		
			echo '<pre>';
			print_r($result);
			echo '</pre>';
		// die();
		
		return $result;
	} // end of getAllNamesAndIds
*/	
	
	public function getIndustryId($id = 0)
	{
		$select = $this->select()
				->from($this, array('id_ind'))
				->where('`id_parent_ind` = ?', $id)
				->LIMIT(1);

		$result = $this->fetchAll($select)->toArray();
		
		return $result[0]['id_ind'];
	} // end of getFirstIndustryId
	
    /**
    *   getNamesAndIdsById
    *
    *   Get all content by parent id and language.
    *
    *   @param int $id parent id
    *   @param int $id_lng_ind industry language id
    *   @return array
    */
	public function getNamesAndIdsById($id = 0, $id_lng_ind = 0)
	{
		$select = $this->select()
				->from($this, array('id_ind', 'name_ind'))
				->where('`id_parent_ind` = ?', $id)
                ->where('`id_lng_ind` = ?', $id_lng_ind);

		$result = $this->fetchAll($select)->toArray();
        
		return $result;
	} // end of getNamesAndIdsById
	
    /**
	*	getAllContentIndustryIds
	*
	*	This function is for getting all industry ids of the content. When
    *   content is added to database, to the cnt_has_ind table is added only
    *   the id of last chosen industry of the add content form.
	*
    *   @param int $id_ind Industry id from cnt_has_ind table
    *   @return array
    *
    *   @author Pekka Piispanen
	*/
    public function getAllContentIndustryIds($id_ind)
    {
        // Let's add the first item to an array
        $industry_ids = array($id_ind);
        
        // This loop adds industry ids to an array
        while($id_ind != 0)
        {
            $select = $this->select()
                    ->from($this, array('id_parent_ind'))
                    ->where("`id_ind` = $id_ind");
                    
            $result = $this->fetchAll($select)->toArray();
            
            $id_ind = $result[0]['id_parent_ind'];

            if($id_ind != 0)
            {   
                // Here the values are added to beginning of the array
                array_unshift($industry_ids, $result[0]['id_parent_ind']);
            }
        }
        
        //  If the array has less than 4 items (there are 4 industry types), the
        //  rest items are automatically zeros.
        while(count($industry_ids) < 4)
        {
            array_push($industry_ids, 0);
        }
        return $industry_ids;
    }
    
	/*
	public function getFirstIndustryId()
	{
		$select = $this->select()
				->from($this, array('id_ind'))
				->where("`id_parent_ind` = 0")
				->LIMIT(1);

		$result = $this->fetchAll($select)->toArray();
		
		return $result[0]['id_ind'];
	} // end of getFirstIndustryId
	
	public function getFirstDivisionId($industry_id)
	{
		$select = $this->select()
				->from($this, array('id_ind'))
				->where("`id_parent_ind` = $industry_id")
				->LIMIT(1);

		$result = $this->fetchAll($select)->toArray();
		
		return $result[0]['id_ind'];
	} // end of getFirstDivisionId
	
	public function getMainIndustryNamesAndIds()
	{
		$select = $this->select()
				->from($this, array('id_ind', 'name_ind'))
				->where("`id_parent_ind` = 0");

		$result = $this->fetchAll($select)->toArray();
		
		return $result;
	} // end of getMainIndustryNamesAndIds
	
	public function getNamesAndIdsById($id)
	{
		$select = $this->select()
				->from($this, array('id_ind', 'name_ind'))
				->where("`id_parent_ind` = $id");

		$result = $this->fetchAll($select)->toArray();
		
		return $result;
	} // end of getNamesAndIdsById
	*/
} // end of class
?>