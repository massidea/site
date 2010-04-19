<?php 

/**
* Build decorator for Registercomplete form
*
* @license GPL v2
* @author Joel Peltonen
*/

class Oibs_Decorators_RegistercompleteDecorator extends Zend_Form_Decorator_Abstract
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
        <div class="error">
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
        return '<div class="form_registercomplete_desc"> ' . $desc . '</div>';
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
        $pbar       = "";
        if ($element->getName() == "bio"){
            $pbar = '<div id="progressbar_bio"></div>';
        }

        if (empty($desc)) {
            $output =   '
                        <div class="form_registercomplete_row" id="form_registercomplete_row_' . $element->getName() . '">
                            ' . $label . '
                            <div class="form_registration_input">
                                '. $input . $text .'
                            </div>
                            ' . $pbar . '
                        </div>
                        '. $errors;
        } else {
             $output = '
                        <div class="form_registercomplete_row">
                            '. $label . $desc .'
                            <div class="form_registration_input">
                                '. $input .'
                                '. $text .'
                            </div>
                            ' . $pbar . '
                        </div>
                        '. $errors;
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