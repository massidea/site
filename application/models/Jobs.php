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

} // end of class
?>