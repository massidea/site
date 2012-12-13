<?php
/**
 *  Jobs - class
 *
 *  @package 	models
 *  @author 		Martin Chalupar & Florian Paar
 *  @copyright 	2009 Martin Chalupar & Florian Paar
 *  @version 	1.0
 */ 
class Default_Model_Jobs extends Zend_Db_Table_Abstract
{
	// Tagle name
    protected $_name = 'jobs_job';
    
	// Table primary key
	protected $_primary = 'id_job';

    public function getJobById($id_job) {

        $rowset = $this->find((int)$id_job)->current();
        if ($rowset != null)
            return $rowset->toArray();
        else
            return null;
    }

    public  function getJobs() {
        $select = $this->select()
            ->from($this, array('*'))
            ->order('description_job DESC')
        ;

        $result = $this->fetchAll($select);
        if ($result != null)
            return $result->toArray();
        else
            return null;
    }
} // end of class
?>