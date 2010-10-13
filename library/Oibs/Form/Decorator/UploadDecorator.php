<?php 
class Oibs_Form_Decorator_UploadDecorator extends Zend_Form_Decorator_File
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
        $name = $this->getElement()->getName();
		
		$text = '';
		if($element->isrequired())
		{
            $text = '<div id="progressbar_'.$name.'" class="progress limit"></div>';
		}
        
		return $element->getView()->$helper(
            $element->getName()."[]", //small hack to add brackets [] to make it possible to upload multiple files
            $element->getAttribs()) . $text;
    }

    public function buildErrors()
    {
        $element  = $this->getElement();
        $messages = $element->getMessages();
        if (empty($messages)) 
		{
            return '';
        }
        return '<div class="error_messages">' .
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
        return '<small class="right">' . $desc . '</small>';
    }

    public function render($content)
    {
        $element = $this->getElement();
        if (!$element instanceof Zend_Form_Element_File) 
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

        /*$output = '<div class="form_addcontent_title_row"><b>'
					. $label
                    . '</b>'
                    . $desc
                    . '</div>'
                    . '<div class="form_addcontent_row">'
					. $input
					. $errors
					. '</div>';*/

        $output = '<div id="form_element_' . $name .'_container" class="row" >
                        <div class="field-label">'
                            . $desc
                            . $label
                        . '</div>
                        <div class="field">'
                            . $input
                            . $errors
                    . '</div>
					</div>
					<div class="clear"></div>';

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
?>