<?php 
class Oibs_Decorators_RegistrationDecorator extends Zend_Form_Decorator_Abstract
{
	public function buildLabel()
	{
		$element = $this->getElement();
		$label = $element->getLabel();

		return "<label><strong>".$label."</strong></label>";//$element->getView()
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
        $label     = $this->buildLabel();
        $input     = $this->buildInput();
        $errors    = $this->buildErrors();
        $desc      = $this->buildDescription();
		$name	   = $element->getName();
        $translator = $element->getTranslator();
		
        $output = "";
        
        
		if ($name == 'city') {
			$output = "<h3>" . $translator->translate('register-personal-information') . "</h3>";
		} else if ($name == 'username') {
			$output = "<div class='clear'></div><h3>" . $translator->translate('register-account-information') . "</h3>";
		}
        if ($element->isRequired()) {
            $output .=   '<div class="input-column1">' . $label . '</div>' .
                            '<div class="input-column2">'. $input .'</div>'
                            . '<div class="input-column3">'. $text . '</div>'
                        . $errors
            			. '<div class="clear"></div>';
        } else {
             $output .= '
                        <div class="input-column1">
                            '. $label .'</div>
                            <div class="input-column2">
                                '. $input
                            . '</div>' 
                            . '<div class="clear"></div>';
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