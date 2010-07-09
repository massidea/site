<?php 
class Oibs_Decorators_UserListFilterCheckboxNextToInputDecorator extends Zend_Form_Decorator_Abstract
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
		if($element->isrequired())
		{
			$text = '<span class="form_register_required">*</span>';
		}
		
        return $element->getView()->$helper(
            $element->getName(),
            $element->getValue(),
            $element->getAttribs(),
            $element->options) . $text;
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

        $output = 	'<div class="form_userlist_element_checkbox" style="display:inline;">'
					. $input
                    .'</div>
                    <div class="form_userlist_element_label_right" style="display:inline;">'
                    . $label
                    .'</div>
                    </div>';

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