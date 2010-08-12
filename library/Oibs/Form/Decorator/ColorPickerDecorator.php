<?php
/**
 * Label into input-column3 and input into input-column4
 *
 * @author Janne Vaaraniemi
 */
class Oibs_Form_Decorator_ColorPickerDecorator extends Zend_Form_Decorator_Abstract
{

    public function buildLabel()
    {
        $element = $this->getElement();
        $label = $element->getLabel();
        $temp = '';

        if ($translator = $element->getTranslator()) {
            $label = $translator->translate($label);
        }
        
        $label = '<strong>'.$label.': </strong>';

        return '<label>'.$label.'</label>';
    }

    public function buildInput()
    {
        $element = $this->getElement();
        $helper  = $element->helper;
        return $element->getView()->$helper(
            $element->getName(),
            $element->getValue(),
            $element->getAttribs(),
            $element->options
        );
    }

    public function buildErrors()
    {
        $element  = $this->getElement();
        $messages = $element->getMessages();
        if (empty($messages)) {
            return '';
        }
        return '<div class="errors">' .
               $element->getView()->formErrors($messages) . '</div>';
    }

    public function buildDescription()
    {
        $element = $this->getElement();
        $desc    = $element->getDescription();
        if (empty($desc)) {
            return '';
        }
        return $desc;
    }

    public function render($content)
    {
        $element = $this->getElement();
        if (!$element instanceof Zend_Form_Element) {
            return $content;
        }
        if (null === $element->getView()) {
            return $content;
        }

        $separator = $this->getSeparator();
        $placement = $this->getPlacement();
        $label     = $this->buildLabel();
        $input     = $this->buildInput();
        $errors    = $this->buildErrors();
        $desc      = $this->buildDescription();

        $output = '<div class="input-column3">'
                . $label
                . '</div>'
                . '<div class="input-column4">'
                . $input
                . $desc
                . $errors
                .'<div id="picker"></div>'
                . '</div>';

        switch ($placement) {
            case (self::PREPEND):
                return $output . $separator . $content;
            case (self::APPEND):
            default:
                return $content . $separator . $output;
        }
    }
}
?>
