<?php
class Default_Model_MetaHasAttributes extends Zend_Db_Table_Abstract
{
// Tagle name
    protected $_name = 'meta_has_atr';

    protected $_referenceMap    = array(
        'meta' => array(
            'columns'           => array('id_meta'),
            'refTableClass'     => 'Default_Model_Meta',
            'refColumns'        => array('id_meta')
        ),
        'attributes_atr' => array(
            'columns'           => array('id_atr'),
            'refTableClass'     => 'Default_Model_Attribute',
            'refColumns'        => array('id_atr')
        )
    );

    public function Create($id_meta, $id_atr) {

        $meta_model = new Default_Model_Meta();
        $attribute_model = new Default_Model_Attribute();
        if ($meta_model->getMetaById($id_meta) != null &&
            $attribute_model->getAttributeById($id_atr) != null) {

            $result = $this->_db->insert('meta_has_atr', array(
                'id_meta'       => $id_meta,
                'id_atr'        => $id_atr
            ));

            return $result;
        }
        return null;
    }

    public  function  CreateAttributes($id_meta, array $arr_id_atr) {
        $this->RemoveAll($id_meta);
        foreach ($arr_id_atr as $id_atr) {
            $this->Create($id_meta, $id_atr);
        }
    }

    public function RemoveAll($id_meta) {

        $meta_model = new Default_Model_Meta();
        if ($meta_model->getMetaById($id_meta) != null) {
            $this->delete("id_meta = '$id_meta'");

        }
    }

    public function Remove($id_meta, $id_atr) {

        $meta_model = new Default_Model_Meta();
        $attribute_model = new Default_Model_Attribute();
        if ($meta_model->getMetaById($id_meta) != null &&
            $attribute_model->getAttributeById($id_atr) != null) {

            $this->_db->delete('meta_has_atr', array(
                'id_meta'       => $id_meta,
                'id_atr'        => $id_atr
            ));
        }
    }

    public function getAttributesByMetaId($id_meta = 0) {
        $data = $this->_db->select()
            ->from(array('mha' => 'meta_has_atr'),
                   array('id_atr'))
            ->join(array('atr' => 'attributes_atr'),
                    'atr.id_atr = mha.id_atr',
                    array('name_atr'))
            ->where('id_meta = ?', $id_meta);

        $result = $this->_db->fetchAll($data);

        return $result;
    }


} // end of class
?>