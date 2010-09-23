<?php
/**
 * Use this for make element into form
 *
 * @author Mikko Korpinen
 */
class Oibs_Form_Element_Note extends Zend_Form_Element {
    public $helper = 'formNote';
    
    public function isValid($value) {
    	return true;
    }
}
?>
