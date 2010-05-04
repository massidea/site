<?php 
class Oibs_Decorators_SettingsNotificationsDecorator extends Zend_Form_Decorator_Abstract
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
        
 //       $translator = $element->getTranslator();
 		$translate = Zend_Registry::get('Zend_Translate');
        //$translate->_('account-registercomplete-formtitle'));
        
        $options = array();
        foreach ($element->options as $id => $option)
        {
        	$options[$id] = $translate->_('account-register-emailnotifications-'.$option);
        }
        
		return $element->getView()->$helper(
            $element->getName(),
            $element->getValue(),
            $element->getAttribs(),
            $options);
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
        return '<div class="description">' . $desc . '</div>';
    }

    public function render($content)
    {
        $element = $this->getElement();
        if (!$element instanceof Zend_Form_Element_Multi) 
		{
            return $content;
        }
        if (null === $element->getView()) 
		{
            return $content;
        }

        $separator = "";
        $placement = $this->getPlacement();
        $label     = $this->buildLabel();
        $input     = $this->buildInput();
        $errors    = $this->buildErrors();
        $desc      = $this->buildDescription();
		$input = str_replace("<label", "<label id='notifications-label'", $input);
		//$input = str_replace("label>", "label>",$input);
        $output = /*'<dl class="form_element">*/
					'<div class="form_notifications">'
					. $label
					."<div style='float: left;' >". $input ."</div>"
					. $errors
					. $desc
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