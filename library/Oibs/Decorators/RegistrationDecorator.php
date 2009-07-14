<?php 
class Oibs_Decorators_RegistrationDecorator extends Zend_Form_Decorator_Abstract
{
	public function buildLabel()
	{
		$element = $this->getElement();
		$label = $element->getLabel();
		/*
		if ($translator = $element->getTranslator())
		{
			$label = $translator->translate($label);
		}
		
		if ($label != null)
		{
			if ($element->isrequired())
			{
				$label .= '*';
			}
			$label .= ':';
		}
		*/
		
		return $element->getView()
						->formLabel($element->getName(), $label);
	}

	public function buildInput()
    {
        $element = $this->getElement();
        $helper  = $element->helper;
		
        return $element->getView()->$helper(
            $element->getName(),
            $element->getValue(),
            $element->getAttribs(),
            $element->options);
    }

    public function buildErrors()
    {
        $element  = $this->getElement();
        $messages = $element->getMessages();
        if (empty($messages)) 
		{
            return '';
        }
        
        return '<div class="error" style="width: 300px; margin-left:auto; margin-right: auto;">' .
               $element->getView()->formErrors($messages) . '</div>';
    }

    public function buildDescription()
    {
        $element = $this->getElement();
        $desc    = $element->getDescription();
        if (empty($desc)) 
		{
            return '';
        }
        return '<div class="registration_description">' . $desc . '</div>';
    }

    public function render($content)
    {
        $element = $this->getElement();
		$text = '';
		if($element->isrequired())
		{
			$text = '<div class="registration_required">*</div>';
		}
        
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
        $desc      = $this->buildDescription();

        if (empty($desc)) {
            $output =   '<div class="form_registration_row">'
                        . $label
                        . '<div class="form_registration_input">'
                        . $input
                        . $text
                        . '</div>'
                        . '</div>'
                        . $errors;
        } else {
             $output = '<div class="form_registration_row">'
                        . $label
                        . '<div class="form_registration_input_description">'
                        . $input
                        . $desc
                        . $text
                        . '</div>'
                        . '</div>'
                        . $errors;
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