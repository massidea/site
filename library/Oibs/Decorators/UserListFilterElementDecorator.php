<?php 
class Oibs_Decorators_UserListFilterElementDecorator extends Zend_Form_Decorator_Abstract
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
        // $errors    = $this->buildErrors();
        // $desc      = $this->buildDescription();

        $output = /*'<dl class="form_element">*/
					'<div class="form_userlist_element">
					<div class="form_userlist_element_label">'
                    . $label
                    .'</div>
                    <div class="form_userlist_element_input">'
					. $input
                    .'</div>'
					// . $errors
					// . $desc
					. '</div>';
				 /*</dl>'*/;

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