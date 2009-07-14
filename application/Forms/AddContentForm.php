<?php
/**
 *  AddContentForm -> Form for content adding
 *
* 	Copyright (c) <2009>, Markus Riihelä
* 	Copyright (c) <2009>, Mikko Sallinen
*
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License 
 * as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied  
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for  
 * more details.
 * 
 * You should have received a copy of the GNU General Public License along with this program; if not, write to the Free 
 * Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * License text found in /license/
 */

/**
 *  AddContentForm - class
 *
 *  @package 	Forms
 *  @author 		Markus Riihelä & Mikko Sallinen
 *  @copyright 	2009 Markus Riihelä & Mikko Sallinen
 *  @license 	GPL v2
 *  @version 	1.0
 */
class Forms_AddContentForm extends Zend_Form
{
    public function __construct($options = null, $data = null, $lang = 'en') 
    { 
        parent::__construct($options);
		
		$translate = Zend_Registry::get('Zend_Translate'); 
		
		$this->setDisableLoadDefaultDecorators(true);
 
        $this->addDecorator('FormElements')
             ->addDecorator('HtmlTag', array('tag' => 'div'))
             ->addDecorator('Form');

		
		$this->setName('add_content_form');
        //$this->setAction('/'.$lang.'/content/preview');
		$this->addElementPrefixPath('Oibs_Decorators', 
						'Oibs/Decorators/',
						'decorator');
		
		/*
		// Get content types
		$content_types = array();
		$model_content_types = new Models_ContentTypes();
		$types = $model_content_types->fetchAll()->toArray();

		foreach ($types as $type)
		{
			$content_types[] = $type['name_cty'];
		}
		*/
		
		/* Content type select form element
		$type = new Zend_Form_Element_Select('content_type');
		$type->setLabel("Content type")
				->addFilter('StringtoLower')
				->setDecorators(array('CustomDecorator'))
				->setMultiOptions($data['ContentTypes']);*/
		
		$innovation = new Zend_Form_Element_Select('innovation_type');
		$innovation->setLabel($translate->_("content-add-innovation-type"))
				->addFilter('StringToLower')
				->setDecorators(array('CustomDecorator'))
				->setAttrib('onchange', "checkCF();")
				->setMultiOptions($data['InnovationTypes']);

		// Industry select form element
		$industry = new Zend_Form_Element_Select('content_industry');
		$industry->setLabel($translate->_("content-add-industry"))
				->addFilter('StringtoLower')
				->setDecorators(array('CustomDecorator'))
				->setAttrib('onchange', "getItems('/en/content/division/industry/'+this.value, 'divisiondd', this.value); checkCF();")
				->setMultiOptions($data['Industries']);	
				
		$division = new Zend_Form_Element_Select('content_division');
		$division->setLabel($translate->_("content-add-division"))
				->addFilter('StringtoLower')
				->setDecorators(array('DivisionDecorator'))
				->setMultiOptions($data['Divisions']);
		
		$group = new Zend_Form_Element_Select('content_group');
		$group->setLabel($translate->_("content-add-group"))
				->addFilter('StringtoLower')
				->setDecorators(array('GroupDecorator'))
				->setMultiOptions($data['Groups']);
				
		$class = new Zend_Form_Element_Select('content_class');
		$class->setLabel($translate->_("content-add-class"))
				->addFilter('StringtoLower')
				->setDecorators(array('ClassDecorator'))
				->setMultiOptions($data['Classes']);
		
		// Content header input form element
		$header = new Zend_Form_Element_Text('content_header');
		$header->setLabel($translate->_("content-add-header"))
				->addFilter('StringtoLower')
				->setAttribs(array(
					"onkeydown" => "textCounter(this,'progressbar_header',0,100); checkCF();",
					"onkeyup" => "textCounter(this,'progressbar_header',0,100); checkCF();"
					))
				->setDecorators(array('HeaderDecorator'));
				
		// Content keywords input form element
		$keywords = new Zend_Form_Element_Text('content_keywords');
		$keywords ->setLabel($translate->_("content-add-keywords"))
				->addFilter('StringtoLower')			
				->setAttribs(array(
					"onkeydown" => "textCounter(this,'progressbar_keywords',0,120); checkCF();",
					"onkeyup" => "textCounter(this,'progressbar_keywords',0,120); checkCF();"
					))
				->setDecorators(array('KeywordsDecorator'));
				
		$content_type = new Zend_Form_Element_Hidden('content_type');
		$content_type->setValue($data['content_type']);
		$content_type->removeDecorator('DtDdWrapper');
		
		$content_relatesto_id = new Zend_Form_Element_Hidden('content_relatesto_id');
		$content_relatesto_id->setValue($data['content_relatesto_id']);
		$content_relatesto_id->removeDecorator('DtDdWrapper');
		
		// Content lead text input form element
		$textlead = new Zend_Form_Element_Textarea('content_textlead');
		$textlead->setLabel($translate->_("content-add-textlead"))
				->addFilter('StringtoLower')
				->setAttribs(array(
					"onkeydown" => "textCounter(this,'progressbar_ingress',0,160); checkCF();",
					"onkeyup" => "textCounter(this,'progressbar_ingress',0,160); checkCF();"
					))
				->setDecorators(array('TextleadDecorator'));
		
		// Content text input form element
		$text = new Zend_Form_Element_Textarea('content_text');
		$text ->setLabel($translate->_("content-add-text"))
				->addFilter('StringtoLower')			
				->setAttribs(array(
					"onkeydown" => "textCounter(this,'progressbar_textcontent',1500,4000); checkCF();",
					"onkeyup" => "textCounter(this,'progressbar_textcontent',1500,4000); checkCF();"
					))
				->setDecorators(array('TextbodyDecorator'));	
		
		// Form submit buttom form element
		$submit = new Zend_Form_Element_Submit('content_submit');
		$submit->setLabel("Send");
		$submit->removeDecorator('DtDdWrapper');
        
        $preview = new Zend_Form_Element_Button('preview');
        $preview->setLabel("Preview");
        $preview->removeDecorator('DtDdWrapper');
        $preview->setAttrib('onclick',"populatePreview(); popup('popup_preview')") ;
        //echo "<pre>"; print_r($preview); echo "</pre>"; die;

		
		// Add elements to form
		$this->addElements(array($innovation, $industry, $division, $group, $class, $header, $keywords, $content_type, $content_relatesto_id, $textlead, $text, $submit, $preview));
	}
}
?>