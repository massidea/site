<?php 
/*
 *  This decorator is just for hidden form elements and just because without
 *  decorator dt and dd tags are generated to the form and they are causing
 *  errors and code is not valid. Here isn't made any decorating, just creating
 *  the element.
 */
class Oibs_Decorators_FormHiddenElementDecorator extends Zend_Form_Decorator_Abstract
{
	public function buildInput()
    {
        $element = $this->getElement();
        $helper  = $element->helper;
        $name = $this->getElement()->getName();
		
		$text = '';
		
        $attribs = $element->getAttribs();
        unset($attribs['helper']);
        
        return $element->getView()->$helper(
            $element->getName(),
            $element->getValue(),
            $attribs,
            $element->options) . $text;
    }

    public function render($content)
    {
        $translate = Zend_Registry::get('Zend_Translate'); 
        $element = $this->getElement();
        if (!$element instanceof Zend_Form_Element) 
		{
            return $content;
        }
        if (null === $element->getView()) 
		{
            return $content;
        }

        $separator = $this->getSeparator();
        $placement = $this->getPlacement();
        $input     = $this->buildInput();
        
        $output = $input;

        switch ($placement) 
		{
            case (self::PREPEND):
                return $output . $separator . $content;
            case (self::APPEND):
            default:
                return $content . $separator . $output;
        }
    }

}