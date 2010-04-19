<?php
/**
 *  ContentViews -> ContentViews database model for content views table.
 *
* 	Copyright (c) <2009>, Joel Peltonen
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
 *  ContentRatings - class
 *
 *  @package 	models
 *  @author 		Joel Peltonen
 *  @copyright 	2009 Joel Peltonen
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class Default_Model_ContentViews extends Zend_Db_Table_Abstract
{
	// Table name
    protected $_name = 'cnt_views_vws';
    
	// Table reference map
	protected $_referenceMap    = array(
        'Content' => array(
            'columns'           => array('id_cnt_vws'),
            'refTableClass'     => 'Default_Model_Content',
            'refColumns'        => array('id_cnt')
        ),  
		 'User' => array(
            'columns'           => array('id_usr_vws'),
            'refTableClass'     => 'Default_Model_User',
            'refColumns'        => array('id_usr')
        ),
    );
	
    
	/**
	*	getByContentId
	*	
	*	Gets views for specific content
	*
	*	@param integer $id Id value of content, which view count is wanted.
	*	@return int rating
	*/
    public function getViewsByContentId($id = 0)
    {
        $return = 0;

		// Find rating
		$select = $this->select()->from($this, array('viewCount' => 'COUNT(id_cnt_vws)'))
                                 ->where('id_cnt_vws = ?', $id)
                                 ->group('id_cnt_vws');
                                 
        $rowset = $this->fetchAll($select);
        
		// If rating found, return value
		if(count($rowset) >= 1) {
            /*
            $views_data = $rowset->toArray();	
            
            for ($i = 0; $i<count($rowset); $i++) {
                $return += $views_data[$i]['views_vws'];
            }
            */
            
            $return = $rowset->toArray();
            
            return $return[0]['viewCount'];

           // Zend_Debug::dump($return, $label=null, $echo=true); die;
		} else {
            return 1;
        }
    }
    
    	/**
	*	getViewsByContentIdAndUserId
	*	
	*	Gets views for specific content by content id and user id
	*
	*	@param integer $id Id value of content, which view count is wanted.
	*	@return int rating
	*/
    public function getViewsByContentIdAndUserId($cnt_id = 0, $usr_id = 0)
    {
        $return = 0;
    
		// Find rating
		$select = $this->select()->where('id_cnt_vws = ?', $cnt_id)
                                ->where('id_usr_vws = ?', $usr_id);
        $rowset = $this->fetchAll($select);
		
		// If rating found, return value
		if(count($rowset) == 1) {
            $view_data = $rowset->toArray();	
            $return = $view_data['0']['views_vws'];
		}
        
        return $return;
    }
    
    /**
	*	increaseViewCount
	*
	*	Incerease contents view count
	*
	*	@param int $id id of content
        *       @return boolean success
	*/	
	function increaseViewCount($id = 0)
	{
        $return = false;

        // set $userid to represent correct identity or 0 for anonymous
        $auth = Zend_Auth::getInstance();
		if ($auth->hasIdentity()) {     
            $userid = (int)$auth->getIdentity()->user_id;
        } else {
            $userid = 0;
        }
     //   echo $userid . " user ja content: " . $id; die;
      // echo $this->getViewsByContentIdAndUserId($id, $userid); die;
        
        // If this is the first view of this user for this content - if not
        if ($this->getViewsByContentIdAndUserId($id, $userid) == 0){
            $row = $this->createRow();
            
            $row->id_usr_vws = $userid;
            $row->id_cnt_vws = $id;
            $row->views_vws = 1;
            //try {
                @$row->save();
            /*}
            catch (Exception $e)
            {
                //echo "<pre>";
                print_r($e);
                //echo "</pre>";
            }*/
            $return = true;
        } else {
            $rowset = $this->fetchAll($this
                                ->select()
                                ->where('id_cnt_vws = ?', $id)
                                ->where('id_usr_vws = ?', $userid)
                            );
        
            $row = $rowset->current();
            $row->views_vws += 1;
            
          //  Zend_Debug::dump($row, $label=null, $echo=true); die;

            $row->save();

            $return = true;
        }

        return $return;
	} // end of increaseViewCount
    
} // end of class
?>