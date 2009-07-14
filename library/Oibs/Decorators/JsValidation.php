<?php
/**
 * Generates JS validation rules for form fields
 *
 * @author Jani Hartikainen <firstname at codeutopia net>
 */
class Oibs_Decorator_JsValidation extends Zend_Form_Decorator_Abstract 
{
	/**
	 * The name of the form
	 * @var string
	 */
	protected $_formName;
	
	/**
	 * The namespace that will be used for the forms in JavaScript
	 * @var string
	 */
	protected $_formNamespace = 'Forms';
	
	/**
	 * Where to look for Zend/*.js files
	 * @var string
	 */
	protected $_javaScriptPath;

	/**
	 * JavaScript function called for valid elements
	 * @var string
	 */
	protected $_validElementCallback;

	/**
	 * JavaScript function called for invalid elements
	 * @var string
	 */
	protected $_invalidElementCallback;
	 
	/**
	 * Unique ID counter for generated form names
	 * @var int
	 */
	protected static $_uniqueId = 0;

	public function __construct($options = array())
	{
		if(isset($options['formNamespace']))
			$this->_formNamespace = $options['formNamespace'];

		if(isset($options['javaScriptPath']))
			$this->_javaScriptPath = $options['javaScriptPath'];
		else
			$this->_javaScriptPath = Zend_Controller_Front::getInstance()->getBaseUrl() . '/js/';


		if(isset($options['validElementCallback']))
			$this->_validElementCallback = $options['validElementCallback'];

		if(isset($options['invalidElementCallback']))
			$this->_invalidElementCallback = $options['invalidElementCallback'];
	}
	
	public function render($content)
	{
		$this->_formName = $this->getElement()->getName();
		
		if(!$this->_formName)
		{
			$this->_formName = 'zend_form_' . self::$_uniqueId;
			self::$_uniqueId++;
			$content = preg_replace('/<form([^>]*)>/', '<form name="' . $this->_formName . '"$1>', $content);
		}
		
		$content = preg_replace('/<form([^>]*)>/', '<form onsubmit="return Zend.Form.isValid(this)"$1>', $content);

		$this->_generateJavaScript();	

		$view = $this->getElement()->getView();
		
		$view->headScript()->appendFile($this->_javaScriptPath . 'Zend/Form.js');
		$view->headScript()->appendFile($this->_javaScriptPath . 'Zend/Validate.js');
		
		return $content;
	}

	/**
	 * Generates all JavaScript needed
	 */
	protected function _generateJavaScript()
	{
		$form = $this->getElement();
		$view = $form->getView();

		$script = "var " . $this->_formNamespace . " = " . $this->_formNamespace . " || { };\r\n"
				 . $this->_formNamespace . "." . $this->_formName . " = { };\r\n";

		$script .= $this->_parseElements($form);

		$view->inlineScript()->captureStart();
		echo $script;
		$view->inlineScript()->captureEnd();
	}

	/**
	 * Loop over elements in a parent element and generate the scripts
	 * @param $parent Parent element
	 * @return string Generated JS code
	 */
	protected function _parseElements($parent)
	{
		$script = '';
		
		foreach($parent->getElements() as $element)
		{
			if($this->_isA($element, 'Zend_Form_DisplayGroup') || $this->_isA($element, 'Zend_Form_SubForm'))
			{
				$script .= $this->_parseElements($element);
				continue;
			}

			$validators = $element->getValidators();
			
			if(count($validators) > 0)
				$script .= $this->_generateValidationRules($element);	
		}

		return $script;
	}

	/**
	 * Checks if the object is of this class or has this class as one of its parents
	 * @param object $object
	 * @param string $class
	 * @return bool
	 */
	protected function _isA($object, $class)
	{
		return ($object instanceof $class || is_subclass_of($object, $class));
	}
	
	/**
	 * Generate the JavaScript code for the validation rules
	 * @param Zend_Form_Element $element
	 * @return string
	 */
	protected function _generateValidationRules(Zend_Form_Element $element)
	{
		$name = $element->getName();
		$formName = $this->_formName;
		$validators = $element->getValidators();
		
		$rules = array();
		foreach($validators as $validator)
		{
			$class = get_class($validator);
			$params = $this->_generateValidatorParameters($class, $validator);
			$rules[] = "{ name: '$class', parameters: $params }";
		}
		
		if(count($rules) > 0)
			$script = $this->_formNamespace . "." . $this->_formName . ".$name = [ " . implode(', ', $rules) . " ];\r\n";
		
		return $script;
	}
	
	/**
	 * Generate parameters for a validator rule
	 * @param string $class The name of the validator class
	 * @param Zend_Validate_Interface $validator the validator
	 * @return string
	 */
	protected function _generateValidatorParameters($class, Zend_Validate_Interface $validator)
	{
		$params = '{}';
		switch($class)
		{
			case 'Zend_Validate_Alnum':
			case 'Zend_Validate_Alpha':
				$params = '{ allowWhiteSpace: ' . (($validator->allowWhiteSpace) ? 'true' : 'false') . ' } ';
				break;
			
			case 'Zend_Validate_Between':
				$params = '{ min: ' . $validator->getMin() . ', max: ' . $validator->getMax() . ' } ';
				break;

			case 'Zend_Validate_Date':
				$params = '{ format: ' . $validator->getFormat() . ' } ';
				break;

			case 'Zend_Validate_GreaterThan':
				$params = '{ min: ' . $validator->getMin() . ' } ';
				break;
		}
		
		return $params;
	}
}
