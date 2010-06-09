<?php 
class Oibs_Decorators_FormElementDecorator extends Zend_Form_Decorator_Abstract
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
        
		// Label is generated only if it's not empty
        if($label != "") {
            return $element->getView()
						->formLabel($element->getName(), $label);
        }
	}

	public function buildInput()
    {
        $element = $this->getElement();
        $helper  = $element->helper;
        $name = $this->getElement()->getName();
		
		$text = '';
        $errors = $this->buildErrors();
		/*
        if($element->isrequired())
		{
			$text = '<span class="form_element_' . $this->getElement()->getName() . '_required">*</span>';
		}
        */
        
		if( $element->isrequired()  && $errors == "" || $element instanceof Zend_Form_Element_Select )
		{
            $text = '<div id="progressbar_' .  $name . '" class="progress"></div>';
		}
        
        // Get the attributes here, and remove annoying helper attribute
        $attribs = $element->getAttribs();
        unset($attribs['helper']);
		
        return $element->getView()->$helper(
            $element->getName(),
            $element->getValue(),
            $attribs,
            $element->options) . $text;
    }

    public function buildErrors()
    {
        $element  = $this->getElement();
        //$belongs  = $element->getView()->getBelongsTo();
        $belongs = $element->getName();
        $messages = $element->getMessages();
        
        if (empty($messages)) 
		{
            return '';
        }
        //return '<div class="error_messages">' .
        //       $element->getView()->formErrors($messages) . '</div>';
        return '<div id="progressbar_'.$belongs.'" class="progress">' .
               $element->getView()->formErrors($messages) . 
               //Zend_Debug::dump($belongs) . 
        	   '</div>';
    }

    public function buildDescription()
    {
        $element = $this->getElement();
        $desc    = $element->getDescription();
        $name    = $this->getElement()->getName();
        
        if (empty($desc)) 
		{
            return '';
        }
        //return '<div class="form_element_' . $this->getElement()->getName() . '_description">' . $desc . '</div>';
        return '<div class="form_element_helptext">' . $desc . '</div>';
    }

    public function render($content)
    {
        $translate = Zend_Registry::get('Zend_Translate'); 
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
        $desc      = $this->buildDescription();
        $name      = $this->getElement()->getName();
        
        $output = '<div id="form_element_' . $name .'_container" class="form_element" >
                    <div id="' . $name . '_div">
                        <div class="form_element_header">'
                            . $desc
                            . $label
                        . '</div>
                        <div style="clear: both;"></div>
                        <div class="form_element_input">'
                            . $input
                            . $errors
                        . '<div style="clear: both;"></div>
                        </div>
                    </div>
                 <div style="clear: both;"></div></div>';
        
        /*if($name == "content_research" || $name == "content_threat" || $name == "content_solution") {
            $output .= '<div id="form_helptext_optional" class="form_helptext" >'
                        .$translate->_('content-add-helptext-optional')
                        .'</div>
                        <div class="form_helptext_line"></div>';
        }*/

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