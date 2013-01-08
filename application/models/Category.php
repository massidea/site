<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jürgen
 * Date: 22.11.12
 * Time: 10:41
 * To change this template use File | Settings | File Templates.
 */

class Default_Model_Category extends Zend_Db_Table_Abstract
{
    // Table name
    protected $_name = 'categories_ctg';

    // Primary key of table
    protected $_primary = 'id_ctg';


    public function getCategories() {
        $adapter = $this->getAdapter();
        $sql = "SELECT * FROM categories_ctg";

        $statement = $adapter->query($sql);

        $result = $statement->fetchAll();
        return $result;
    }

    public function getCategoryById($id)
    {
        // Find category
        $rowset = $this->find((int)$id)->current();

        if($rowset != null)
            return $rowset->toArray();
        else
            return null;
    }
}
