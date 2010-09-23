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
        if (empty($messages)) {
			return ''; // if there are no messages = errors; return empty
        }
        return '
        <div class="errors">
        ' . $element->getView()->formErrors($messages) . '
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
            <span class="registration_terms_description">
                ' . $desc . '
            </span>';
    }
	
    public function render($content)
    {
		$input     = $this->buildInput();
		$errors    = $this->buildErrors();
		$desc      = $this->buildDescription();
		
		$output = '
            <p>
				'. $input .'
				'. $desc .'
            </p>
            '. $errors .'
            ';
				   
        return $output;
	}
	
}
?>