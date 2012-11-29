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
    public function getCategories() {
        $adapter = $this->getAdapter();
        $sql = "SELECT * FROM categories_ctg";

        $statement = $adapter->query($sql);

        $result = $statement->fetchAll();
        return $result;
    }
}