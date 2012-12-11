<?php
/**
 *  MetaContent - class
 *
 *  @package    models
 *  @author     Martin Chalupar & Florian Paar
 *  @copyright  2009 Martin Chalupar & Florian Paar
 *  @version    1.0
 */
class Default_Model_MetaContent extends Zend_Db_Table_Abstract
{
	// Table name
	protected $_name = 'meta_cnt';

	// Table primary key
	protected $_primary = 'id_meta_cnt';

	// Dependet tables
	protected $_dependentTables = array('Default_Model_Jobs', 'Default_Model_Category',
                                        'Default_Model_Content');

	// Table reference map
	protected $_referenceMap    = array(
        'Jobs' => array(
            'columns'           => array('id_job'),
            'refTableClass'     => 'Default_Model_Jobs',
            'refColumns'        => array('id_job')
        ),
        'Content' => array(
            'columns'           => array('id_cnt'),
            'refTableClass'     => 'Default_Model_Content',
            'refColumns'        => array('id_cnt')
        ),
        'Category' => array(
            'columns'           => array('id_ctg'),
            'refTableClass'     => 'Default_Model_Category',
            'refColumns'        => array('id_ctg')
        )
    );
	protected $_id = 0;
	protected $_data = null;

	public function __construct($id = -1)
	{
		parent::__construct();

		$this->_id = $id;

		if ($id != -1){
			$this->_data = $this->find((int)$id)->current();
		} // end if
	}

    public function getJobByCntId($id_cnt)
    {
        $adapter = $this->getAdapter();

        $sql = "(SELECT j.description_job" +
               " FROM meta_cnt m, jobs_job j" +
               " WHERE m.id_job = j.id_job AND m.id_cnt = $id_cnt" +
               " LIMIT 1)";

        $statement = $adapter->query($sql);
        $result = $statement->fetchAll();
        return $result;
    }

    public function getCategoryByCntId($id_cnt)
    {
        $adapter = $this->getAdapter();

        $sql = "(SELECT c.title_ctg" +
            " FROM meta_cnt m, category_ctg c" +
            " WHERE m.id_ctg = c.id_ctg AND m.id_cnt = $id_cnt" +
            " LIMIT 1)";

        $statement = $adapter->query($sql);
        $result = $statement->fetchAll();
        return $result;
    }

    public function findAllContentByCtgId($id_ctg)
    {
        $adapter = $this->getAdapter();
        //TODO write useful functions for search
        $sql = "(SELECT c.title_ctg" +
            " FROM meta_cnt m, category_ctg c" +
            " WHERE m.id_ctg = c.id_ctg AND m.id_cnt = $id_cnt" +
            " LIMIT 1)";

        $statement = $adapter->query($sql);
        $result = $statement->fetchAll();
        return $result;
    }

    public function listRandom()
    {
        $adapter = $this->getAdapter();

        $sql = "(SELECT c.id_cnt, c.title_cnt, c.published_cnt, "+
                      " c.body_cnt, u.login_name_usr, t.key_cty "+
                      " FROM contents_cnt c, users_usr u, content_types_cty t "+
                      " WHERE c.id_cty_cnt =1 AND c.published_cnt = u.id_usr AND t.id_cty = c.id_cty_cnt "+
                      " ORDER BY RAND( ) LIMIT 1)";

        $statement = $adapter->query($sql);

        $result = $statement->fetchAll();
        return $result;
    }


} // end of class

