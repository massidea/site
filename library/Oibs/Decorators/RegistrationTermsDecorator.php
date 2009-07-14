<?php 
class Oibs_Decorators_RegistrationTermsDecorator extends Zend_Form_Decorator_Abstract
{
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
        if (empty($messages)) 
		{
            //echo "MESSAGES R EMPTY"; die; 
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
        return '<span class="registration_terms_description">' . $desc . '</span>';
    }
	
    public function render($content)
    {
		$input     = $this->buildInput();
		$errors    = $this->buildErrors();
		$desc      = $this->buildDescription();
		
		$output = /*'<dl class="form_element">'
					.*/'<div class="registration_terms_input">'
						.$input
					.'</div><div>'
						.$desc
						. $errors
				   .'</div>';
				   
        return $output;
	}
	
}
?>