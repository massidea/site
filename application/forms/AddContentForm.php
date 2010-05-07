<?php
/**
 *  AddContentForm -> Form for content adding
 *
* 	Copyright (c) <2009>, Markus Riihelä
* 	Copyright (c) <2009>, Mikko Sallinen
* 	Copyright (c) <2009>, Pekka Piispanen
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
 *  @author 	Markus Riihelä, Mikko Sallinen & Pekka Piispanen
 *  @copyright 	2009 Markus Riihelä, Mikko Sallinen & Pekka Piispanen
 *  @license 	GPL v2
 *  @version 	1.0
 */
class Default_Form_AddContentForm extends Zend_Form
{
    public function __construct($options = null, $data = null, $lang = 'en', $contentType = 'problem') 
    { 
        parent::__construct($options);
        
		$translate = Zend_Registry::get('Zend_Translate'); 
		$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
		$this->setDisableLoadDefaultDecorators(true);
 
        $this->addDecorator('FormElements')
             ->addDecorator('HtmlTag', array('tag' => 'div'))
             ->addDecorator('Form')
             ->removeDecorator('DtDdWrapper');

		
		$this->setName('add_content_form');
		//$this->setName('contentAdd');
        $this->setAction($baseUrl.'/'.$lang.'/content/add/'.$contentType);
		
        $this->addElementPrefixPath('Oibs_Decorators', 
						'Oibs/Decorators/',
						'decorator');
		
        $this->setAttrib('enctype', 'multipart/form-data');
        
        /**
        * 
        * Form Elements
        * 
        */
        
		// Header, input
		$header = new Zend_Form_Element_Text('content_header');
		$header->addFilter('StringtoLower')
				->setAttribs(array(
					"onfocus" => "textCounter(this,'progressbar_content_header',1,120,'".$lang."'); checkCF();",
					"onblur" => "textCounter(this,'progressbar_content_header',1,120,'".$lang."'); checkCF();",
					"onkeydown" => "textCounter(this,'progressbar_content_header',1,120,'".$lang."'); checkCF();",
					"onkeyup" => "textCounter(this,'progressbar_content_header',1,120,'".$lang."'); checkCF();"
					))
                ->setRequired(true)
                ->addValidators(array(
                                      array('NotEmpty', 
                                            true, 
                                            array('messages' => 
                                                    array('isEmpty' => 
                                                      'content-add-field-empty'))),
                                      array('StringLength', 
                                            true, 
                                            array(1, 
                                                  120,
                                                  'messages' => 
                                                      array('stringLengthTooLong' => 
                                                        'content-add-field-too-long')))
                                     )
                               )
                ->setLabel($translate->_("content-add-header"))
				->setDecorators(array('FormElementDecorator'));
				
		// Keywords, input
		$keywords = new Zend_Form_Element_Text('content_keywords');
		$keywords->addFilter('StringtoLower')			
				->setAttribs(array(
					"onfocus" => "textCounter(this,'progressbar_content_keywords',1,120,'".$lang."'); checkCF();",
					"onblur" => "textCounter(this,'progressbar_content_keywords',1,120,'".$lang."'); checkCF();",
					"onkeydown" => "textCounter(this,'progressbar_content_keywords',1,120,'".$lang."'); checkCF();",
					"onkeyup" => "textCounter(this,'progressbar_content_keywords',1,120,'".$lang."'); checkCF();"
					))
                ->setRequired(true)
                ->addValidators(array(array('NotEmpty', 
                                            true, 
                                            array('messages' => 
                                                array('isEmpty' => 
                                                    'content-add-field-empty')
                                                 )
                                           ),
                                        array('StringLength', 
                                              true, 
                                              array(1, 
                                                    120,
                                                    'messages' => 
                                                        array('stringLengthTooLong' => 
                                                            'content-add-field-too-long')
                                                   )
                                             )
                                     )
                               )
                ->setLabel($translate->_("content-add-keywords"))
                ->setDescription($translate->_("content-add-keywords-help-text"))
				->setDecorators(array('FormElementDecorator'));
		
		// Content type, Hidden
		$content_type = new Zend_Form_Element_Hidden('content_type');
		$content_type->setValue($data['content_type'])
                     ->setDecorators(array('FormHiddenElementDecorator'));
        
        // Related content, Hidden
		$content_relatesto_id = new Zend_Form_Element_Hidden('content_relatesto_id');
		$content_relatesto_id->setValue($data['content_relatesto_id'])
                             ->setDecorators(array('FormHiddenElementDecorator'));
		
		// Lead, Textarea
		$textlead = new Zend_Form_Element_Textarea('content_textlead');
		$textlead->addFilter('StringtoLower')
				->setAttribs(array(
					"onfocus" => "textCounter(this,'progressbar_content_textlead',1,160,'".$lang."'); checkCF();",
					"onblur" => "textCounter(this,'progressbar_content_textlead',1,160,'".$lang."'); checkCF();",
					"onkeydown" => "textCounter(this,'progressbar_content_textlead',1,160,'".$lang."'); checkCF();",
					"onkeyup" => "textCounter(this,'progressbar_content_textlead',1,160,'".$lang."'); checkCF();"
					))
                ->setRequired(true)
                ->setAttrib('class', 'textlead')
                ->addValidators(array(array('NotEmpty', 
                                            true, 
                                            array('messages' => 
                                                array('isEmpty' => 
                                                    'content-add-field-empty')
                                                 )
                                           ),
                                        array('StringLength', 
                                              true, 
                                              array(1, 
                                                    160,
                                                    'messages' => 
                                                        array('stringLengthTooLong' => 
                                                            'content-add-field-too-long')
                                                   )
                                             )
                                     )
                               )
                ->setLabel($translate->_("content-add-textlead"))
                ->setDescription($translate->_("content-add-textlead-help-text"))
				->setDecorators(array('FormElementDecorator'));
		        
		// Body, Textarea
		$text = new Zend_Form_Element_Textarea('content_text');
		$text->addFilter('StringtoLower')
				->setAttribs(array(
					"onblur" => "textCounter(this,'progressbar_content_text',1000,4000,'".$lang."'); checkCF();",
					"onfocus" => "textCounter(this,'progressbar_content_text',1000,4000,'".$lang."'); checkCF();",
					"onkeydown" => "textCounter(this,'progressbar_content_text',1000,4000,'".$lang."'); checkCF();",
					"onkeyup" => "textCounter(this,'progressbar_content_text',1000,4000,'".$lang."'); checkCF();"
                    ))
                ->setRequired(true)
                ->setAttrib('class', 'textbody')
                ->addValidators(array(array('NotEmpty', 
                                            true, 
                                            array('messages' => 
                                                array('isEmpty' => 
                                                    'content-add-field-empty')
                                                 )
                                           ),
                                        array('StringLength', 
                                              true, 
                                              array(1000, 
                                                    4000,
                                                    'messages' => 
                                                        array('stringLengthTooShort' => 
                                                                'content-add-field-too-short', 
                                                            'stringLengthTooLong' => 
                                                                'content-add-field-too-long')))
                                     )
                               )
                ->setLabel($translate->_("content-add-text"))
                ->setDescription($translate->_('content-add-' . $contentType . '-textbody-help-text'))
                ->setDecorators(array('FormElementDecorator'));
        
        // Related companies, Input
		$related_companies = new Zend_Form_Element_Text('content_related_companies');
		$related_companies->addFilter('StringtoLower')			
				->setAttribs(array(
					"onfocus" => "textCounter(this,'progressbar_content_related_companies',1,120,'".$lang."'); checkCF();",
					"onblur" => "textCounter(this,'progressbar_content_related_companies',1,120,'".$lang."'); checkCF();",
					"onkeydown" => "textCounter(this,'progressbar_content_related_companies',1,120,'".$lang."'); checkCF();",
					"onkeyup" => "textCounter(this,'progressbar_content_related_companies',1,120,'".$lang."'); checkCF();"
					))
                ->setRequired(true)
                ->addValidators(array(array('NotEmpty', 
                                            true, 
                                            array('messages' => 
                                                array('isEmpty' => 
                                                    'content-add-field-empty')
                                                 )
                                           ),
                                        array('StringLength', 
                                              true, 
                                              array(1, 
                                                    120,
                                                    'messages' => 
                                                        array('stringLengthTooLong' => 
                                                            'content-add-field-too-long')
                                                   )
                                             )
                                     )
                               )
                ->setLabel($translate->_("content-add-related_companies"))
                ->setDescription($translate->_("content-add-related_companies-help-text"))
				->setDecorators(array('FormElementDecorator')); 
        
        // Problem research question, Input
		$research = new Zend_Form_Element_Text('content_research');
		$research->addFilter('StringtoLower')
				->setAttribs(array(
					"onkeydown" => "textCounter(this,'progressbar_content_research',1,120,'".$lang."'); checkCF();",
					"onkeyup" => "textCounter(this,'progressbar_content_research',1,120,'".$lang."'); checkCF();",
					"onkeydown" => "textCounter(this,'progressbar_content_research',1,120,'".$lang."'); checkCF();",
					"onkeyup" => "textCounter(this,'progressbar_content_research',1,120,'".$lang."'); checkCF();"
					))
                ->setRequired(true)
                ->addValidators(array(array('NotEmpty', 
                                            true, 
                                            array('messages' => 
                                                array('isEmpty' => 
                                                    'content-add-field-empty')
                                                 )
                                           ),
                                        array('StringLength', 
                                            true, 
                                            array(1, 
                                                  120,
                                                  'messages' => 
                                                    array('stringLengthTooLong' => 
                                                        'field-too-long')))
                                     )
                               )
                ->setLabel($translate->_("content-add-research"))
                ->setDescription($translate->_("content-add-research-help-text"))
				->setDecorators(array('FormElementDecorator'));
        
        // Future info opportunity, Input
		$opportunity = new Zend_Form_Element_Text('content_opportunity');
		$opportunity->addFilter('StringtoLower')
				->setAttribs(array(
					"onblur" => "textCounter(this,'progressbar_content_opportunity',1,120,'".$lang."'); checkCF();",
					"onfocus" => "textCounter(this,'progressbar_content_opportunity',1,120,'".$lang."'); checkCF();",
					"onkeydown" => "textCounter(this,'progressbar_content_opportunity',1,120,'".$lang."'); checkCF();",
					"onkeyup" => "textCounter(this,'progressbar_content_opportunity',1,120,'".$lang."'); checkCF();"
					))
                ->setRequired(true)
                ->addValidators(array(array('NotEmpty', 
                                            true, 
                                            array('messages' => 
                                                array('isEmpty' => 
                                                    'content-add-field-empty')
                                                )
                                           ),
                                        array('StringLength', 
                                              true, 
                                              array(1, 
                                                    120,
                                                    'messages' => 
                                                        array('stringLengthTooLong' => 
                                                            'field-too-long')
                                                   )
                                             )
                                     )
                               )
                ->setLabel($translate->_("content-add-opportunity"))
                ->setDescription($translate->_("content-add-opportunity-help-text"))
				->setDecorators(array('FormElementDecorator'));

        // Future info threat, Input
		$threat = new Zend_Form_Element_Text('content_threat');
		$threat->addFilter('StringtoLower')
				->setAttribs(array(
					"onblur" => "textCounter(this,'progressbar_content_threat',1,120,'".$lang."'); checkCF();",
					"onfocus" => "textCounter(this,'progressbar_content_threat',1,120,'".$lang."'); checkCF();",
					"onkeydown" => "textCounter(this,'progressbar_content_threat',1,120,'".$lang."'); checkCF();",
					"onkeyup" => "textCounter(this,'progressbar_content_threat',1,120,'".$lang."'); checkCF();"
					))
                ->setRequired(true)
                ->addValidators(array(array('NotEmpty', 
                                            true, 
                                            array('messages' => 
                                                array('isEmpty' => 
                                                    'content-add-field-empty')
                                                 )
                                           ),
                                        array('StringLength', 
                                              true, 
                                              array(1,
                                                    120,
                                                    'messages' => 
                                                        array('stringLengthTooLong' => 
                                                            'field-too-long')
                                                   )
                                             )
                                     )
                               )
                ->setLabel($translate->_("content-add-threat"))
                ->setDescription($translate->_("content-add-threat-help-text"))
				->setDecorators(array('FormElementDecorator'));
        
        // Idea/solution in one sentence, Input
		$solution = new Zend_Form_Element_Text('content_solution');
		$solution->addFilter('StringtoLower')
				->setAttribs(array(
					"onblur" => "textCounter(this,'progressbar_content_solution',1,120,'".$lang."'); checkCF();",
					"onfocus" => "textCounter(this,'progressbar_content_solution',1,120,'".$lang."'); checkCF();",
					"onkeydown" => "textCounter(this,'progressbar_content_solution',1,120,'".$lang."'); checkCF();",
					"onkeyup" => "textCounter(this,'progressbar_content_solution',1,120,'".$lang."'); checkCF();"
					))
                ->setRequired(true)
                ->addValidators(array(array('NotEmpty', 
                                            true, 
                                            array('messages' => 
                                                array('isEmpty' => 
                                                    'content-add-field-empty')
                                                 )
                                           ),
                                        array('StringLength', 
                                              true, 
                                              array(1,
                                                    120,
                                                    'messages' => 
                                                        array('stringLengthTooLong' => 
                                                            'field-too-long')
                                                   )
                                             )
                                     )
                               )
                ->setLabel($translate->_("content-add-solution"))
				->setDecorators(array('FormElementDecorator'));
        
        // Campaigns, Input
		$campaigns = new Zend_Form_Element_Text('content_campaigns');
		$campaigns->addFilter('StringtoLower')
                ->setAttribs(array(
					"onblur" => "textCounter(this,'progressbar_content_campaigns',0,120,'".$lang."'); checkCF();",
					"onfocus" => "textCounter(this,'progressbar_content_campaigns',0,120,'".$lang."'); checkCF();",
					"onkeydown" => "textCounter(this,'progressbar_content_campaigns',0,120,'".$lang."'); checkCF();",
					"onkeyup" => "textCounter(this,'progressbar_content_campaigns',0,120,'".$lang."'); checkCF();"
					))
                ->addValidators(array(array('StringLength', 
                                              true, 
                                              array(1,
                                                    120,
                                                    'messages' => 
                                                        array('stringLengthTooLong' => 
                                                            'field-too-long')
                                                   )
                                             )
                                     )
                               )
                ->setLabel($translate->_("content-add-campaigns"))
                ->setDescription($translate->_("content-add-campaigns-help-text"))
				->setDecorators(array('FormOptionalElementDecorator'));
        
        // File upload, File
        $file = new Zend_Form_Element_File('content_file_upload');
        $file->setDestination('../www/upload')
             ->removeDecorator('DtDdWrapper')
			 //->addValidator('Count', false, 1)
			 ->addValidator('Size', false, 2097152)
			 ->addValidator('Extension', false, 'png,gif,jpg,jpeg,doc,zip,xls,mpp,pdf,wmv,avi,mkv,mov,mpeg,mp4,divx,flv,ogg,3gp');
        $file->setLabel($translate->_("content-add-upload-file"))
             ->setDescription($translate->_("content-add-file-upload-help-text"))
             ->setDecorators(array('UploadDecorator'));
        
        // References, Textarea
		$references = new Zend_Form_Element_Textarea('content_references');
		$references->addFilter('StringtoLower')
                ->setAttribs(array(
					"onblur" => "textCounter(this,'progressbar_content_references',0,2000,'".$lang."'); checkCF();",
					"onfocus" => "textCounter(this,'progressbar_content_references',0,2000,'".$lang."'); checkCF();",
					"onkeydown" => "textCounter(this,'progressbar_content_references',0,2000,'".$lang."'); checkCF();",
					"onkeyup" => "textCounter(this,'progressbar_content_references',0,2000,'".$lang."'); checkCF();"
					))
                ->setAttrib('class', 'textlead')
                ->addValidators(array(array('StringLength', 
                                              true, 
                                              array(0,
                                                    2000,
                                                    'messages' => 
                                                        array('stringLengthTooLong' => 
                                                            'field-too-long')
                                                   )
                                             )
                                     )
                               )
                ->setLabel($translate->_("content-add-references"))
                ->setDescription($translate->_("content-add-references-help-text"))
				->setDecorators(array('FormOptionalElementDecorator'));
        
        // Language, Select
        $language = new Zend_Form_Element_Select('content_language');
		$language->addFilter('StringtoLower')
                ->setLabel($translate->_("content-add-language"))
				->setDecorators(array('FormElementDecorator'))
				->setMultiOptions($data['languages']);
        
        // Future info classification, Select
        $finfoClasses = new Zend_Form_Element_Select('content_finfo_class');
        $finfoClasses->addFilter('StringtoLower')
                ->setLabel($translate->_("content-add-finfo-classification"))
				->setDecorators(array('FormElementDecorator'))
				->setMultiOptions($data['FutureinfoClasses']);
        
		// Industry, Select
		$industry = new Zend_Form_Element_Select('content_industry');
        $industry->addFilter('StringtoLower')
                ->setLabel($translate->_("content-view-industry-classification"))
                ->setDecorators(array('FormElementDecorator'))
                // This should probably be converted to use jquery
                ->setAttrib('onchange', "getItems('".$baseUrl."/".$lang."/content/division/industry/'+this.value, 
                            'content_division_div', this.value);")
                ->setMultiOptions($data['Industries']);    
        
        // Division, Select
        $division = new Zend_Form_Element_Select('content_division');
        $division->addFilter('StringtoLower')
                ->setDecorators(array('FormElementDecorator'))
                ->setMultiOptions($data['Divisions'])
                ->setRegisterInArrayValidator(false);
        
        // Group, Select
        $group = new Zend_Form_Element_Select('content_group');
        $group->addFilter('StringtoLower')
                ->setDecorators(array('FormElementDecorator'))
                ->setMultiOptions($data['Groups'])
                ->setRegisterInArrayValidator(false);

        // Class, Select
        $class = new Zend_Form_Element_Select('content_class');
        $class->addFilter('StringtoLower')
                ->setDecorators(array('FormElementDecorator'))
                ->setMultiOptions($data['Classes'])
                ->setRegisterInArrayValidator(false);
        
        // Innovation type, Select
        $innovation = new Zend_Form_Element_Select('innovation_type');
        $innovation->addFilter('StringToLower')
                ->setLabel($translate->_("content-view-innovationtype-classification"))
                ->setDecorators(array('FormElementDecorator'))
                ->setMultiOptions($data['InnovationTypes']);
        
        // Form buttons
        
        $publish = new Zend_Form_Element_Submit('content_publish');
        $publish->setLabel($translate->_("content-add-publish"))
                ->removeDecorator('DtDdWrapper');
        
        $save = new Zend_Form_Element_Submit('content_save');
        $save->setLabel($translate->_("content-add-save"))
             ->removeDecorator('DtDdWrapper');
        
        $preview = new Zend_Form_Element_Button('preview');
        $preview->setLabel($translate->_("content-add-preview"))
                ->removeDecorator('DtDdWrapper')
                ->setAttrib('onclick',"populatePreview(); previewRoll('open')");
        
        // Set custom form layout
        $this->setDecorators(array(array('ViewScript', array(
            'viewScript' => 'forms/contentAddForm.phtml',
            'placement' => false
        ))));

        // Add generic elements to form
        $this->addElements(array(
            $header,
            $keywords,
            $content_type,
            $content_relatesto_id,
            $textlead,
            $text,
            $related_companies,
            $campaigns,
            $file,
            $references,
            $language,
            $industry,
            $division,
            $group,
            $class,
            $publish,
            $save,
            $preview,
        ));
        
        // Add content type specific elements
        switch($contentType) {
            case 'problem':
                $this->addElements(array($research));
                break;
            case 'finfo':
                $this->addElements(array($opportunity, $threat, $finfoClasses));
                break;
            case 'idea':
                $this->addElements(array($solution, $innovation));
                break;
            default:
                break;
        }
        
        // Add elements to form
        /*
        $elements = array(
            $header,
            $keywords,
            $content_type,
            $content_relatesto_id,
            $textlead,
            $text,
            $related_companies
        );
                          
        if($contentType == "problem") {
            array_push($elements, $research);
        }
        else if($contentType == "finfo") {
            array_push($elements, $opportunity, $threat);
        }
        else if($contentType == "idea") {
            array_push($elements, $solution);
        }
        
        array_push($elements, $campaigns, $file, $references, $language);
        
        if($contentType == "finfo") {
            array_push($elements, $finfoClasses);
        }
        
        array_push($elements, $industry, $division, $group, $class);

        if($contentType == "idea") {
            array_push($elements, $innovation);
        }
        
        array_push($elements, $publish, $save, $preview);
        
        $this->addElements($elements);
        */
    }
}