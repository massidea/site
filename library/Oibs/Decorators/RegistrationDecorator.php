<?php 
class Oibs_Decorators_RegistrationDecorator extends Zend_Form_Decorator_Abstract
{
	public function buildLabel($text)
	{
		$element = $this->getElement();
		$label = $element->getLabel();

		return "<label><strong>".$label . " " . $text ."</strong></label>";//$element->getView()
						//->formLabel($element->getName(),$label);
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
        $errors = $element->getView()->formErrors($messages);
        
        return '
        <div class="errors">
        ' . $errors . '
        </div>
        ';
    }

    public function buildDescription()
    {
        $element = $this->getElement();
        $desc    = $element->getDescription();
        if (empty($desc)) 
		{
            return '';
        }
        return '
        <div class="registration_description">
        ' . $desc . '
        </div>
        ';
    }

    public function render($content)
    {
        $element = $this->getElement();
		$text = '';
		if($element->isRequired())
		{
			$text = '*';
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
        $label     = $this->buildLabel($text);
        $input     = $this->buildInput();
        $errors    = $this->buildErrors();
        $desc      = $this->buildDescription();
		$name	   = $element->getName();
        $translator = $element->getTranslator();
		
        $output = "";
        
        
		if ($name == 'city') {
			$output = "<h3>" . $translator->translate('register-personal-information') . "</h3>";
		} else if ($name == 'username') {
			$output = "<h3>" . $translator->translate('register-account-information') . "</h3>";
		}
        if ($element->isRequired()) {
            $output .=  ' <div class="row">' .
						' <div class=span2">' . $label . '</div>' .
						' </div> ' .
						' <div class="row">' .
						' <div class=span6">' . $input .'</div>' .
						' </div> '
                        . $errors;
        } else {
             $output .= '
                        <div class="input-column1">
                            '. $label .'</div>
                            <div class="input-column2">
                                '. $input
                            . '</div>';
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