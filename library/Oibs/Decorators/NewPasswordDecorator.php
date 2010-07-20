<?php 
class Oibs_Decorators_NewPasswordDecorator extends Zend_Form_Decorator_Abstract
{
    public function buildLabel()
    {
        $element = $this->getElement();
        $label = $element->getLabel();
        
        return $element->getView()
                        ->formLabel($element->getName(), $label);
    }

    public function buildInput()
    {
        $element = $this->getElement();
        $helper  = $element->helper;
        
        $text = '';
        
        return $element->getView()->$helper(
            $element->getName(),
            $element->getValue(),
            $element->getAttribs(),
            $element->options) . $text;
    }

    public function buildErrors()
    {
        $element  = $this->getElement();
        $messages = $element->getMessages();
        if (empty($messages)) 
        {
            return '';
        };
        return '<div style="font-weight: bold;">' .
               $element->getView()->formErrors($messages) . '</div>';
    }

    public function render($content)
    {
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
        $label     = $this->buildLabel();
        $input     = $this->buildInput();
        $errors    = $this->buildErrors();

        $output = '<div style="clear: both;">
                   <div style="float: left; width: 120px; line-height: 20px;>' . $label . '</div>
                   <div>' . $input . '</div>
                   <div style="float: left; padding-left: 95px;">' . $errors . '</div>
                   </div>';
        if ($errors != '') {
            $output .= '<br clear="all" />';
        }

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