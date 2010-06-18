<?php
/**
 *  EditContentForm -> Form for content editing
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
 *  EditContentForm - class
 *
 *  @package 	Forms
 *  @author 	Markus Riihelä, Mikko Sallinen & Pekka Piispanen
 *  @copyright 	2009 Markus Riihelä, Mikko Sallinen & Pekka Piispanen
 *  @license 	GPL v2
 *  @version 	1.0
 */
class Default_Form_EditContentForm extends Zend_Form
{
    public function __construct($options = null, $data = null, $contentId = 0, $contentType='problem', $lang = 'en') 
    { 
        parent::__construct($options);
		
		$translate = Zend_Registry::get('Zend_Translate'); 
		$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
		$this->setDisableLoadDefaultDecorators(true);
        $this->addDecorator('FormElements')
             ->addDecorator('HtmlTag', array('tag' => 'div'))
             ->addDecorator('Form')
             ->removeDecorator('DtDdWrapper');

		
		$this->setName('edit_content_form');
        $this->setAction($baseUrl.'/'.$lang.'/content/edit/'.$contentId);
		$this->addElementPrefixPath('Oibs_Decorators', 
						'Oibs/Decorators/',
						'decorator');
		
        $this->setAttrib('enctype', 'multipart/form-data');
        
		// Content header input form element
		$header = new Zend_Form_Element_Text('content_header');
		$header->setValue($data['content_header'])
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
                                                  140,
                                                  'messages' => 
                                                      array('stringLengthTooLong' => 
                                                        'content-add-field-too-long')))
                                     )
                               )
                ->setLabel($translate->_("content-add-header"))
				->setDecorators(array('FormElementDecorator'));
		if ($data['published_cnt']) { // Disable editing of header if data is already published
			$header->setAttrib('readonly', true);
		}

		// Content keywords input form element
		$keywords = new Zend_Form_Element_Text('content_keywords');
		$keywords->addFilter('StringtoLower')
				->setValue($data['content_keywords'])
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
                                             ),
                                        array('Regex', true, array('/^[\\p{L}0-9, ]*$/'))
                                     )
                               )
                ->setLabel($translate->_("content-add-keywords"))
                ->setDescription($translate->_("content-add-keywords-help-text"))
				->setDecorators(array('FormElementDecorator'));
				
		$content_type = new Zend_Form_Element_Hidden('content_type');
		$content_type->setValue($data['content_type'])
                     ->setDecorators(array('FormHiddenElementDecorator'));
		
		// Content lead text input form element
		$textlead = new Zend_Form_Element_Textarea('content_textlead');
		$textlead->setValue($data['content_textlead'])
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
                                                    320,
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
		        
		// Content text input form element
		$text = new Zend_Form_Element_Textarea('content_text');
		$text->setValue($data['content_text'])
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
                                              array(0, 
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
                ->setDecorators(array('FormOptionalElementDecorator'));
        
        // Content keywords input form element
		$related_companies = new Zend_Form_Element_Text('content_related_companies');
		$related_companies//->setValue($data['content_related_companies'])
                ->setRequired(false)
                ->addValidators(array(array('StringLength', 
                                			true, 
                                    		array(0, 
                                          		  120,
                                          		  'messages' => array('stringLengthTooLong' => 
                                                  			          'content-add-field-too-long')
                                         		 )
                                     	   )
                               		 )
                               )
                ->setLabel($translate->_("content-add-related_companies"))
                ->setDescription($translate->_("content-add-related_companies-help-text"))
				->setDecorators(array('FormOptionalElementDecorator')); 
        
        // Problem research question input form element
		$research = new Zend_Form_Element_Text('content_research');
		$research->setValue($data['content_research'])
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
        
        // Future info opportunity
		$opportunity = new Zend_Form_Element_Text('content_opportunity');
		$opportunity->setValue($data['content_opportunity'])
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

        // Future info threat
		$threat = new Zend_Form_Element_Text('content_threat');
		$threat->setValue($data['content_threat'])
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
        
        // Idea/solution in one sentence
		$solution = new Zend_Form_Element_Text('content_solution');
		$solution->setValue($data['content_solution'])
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
        
        // File upload
        $file = new Zend_Form_Element_File('content_file_upload');
        $file->setDestination('../www/upload')
        	 ->removeDecorator('DtDdWrapper')
			 //->addValidator('Count', false, 1)
			 ->addValidator('Size', false, 2097152)
			 ->addValidator('Extension', false, 'png,gif,jpg,jpeg,doc,zip,xls,mpp,pdf,wmv,avi,mkv,mov,mpeg,mp4,divx,flv,ogg,3gp');
        $file->setLabel($translate->_("content-add-upload-file"))
             ->setDescription($translate->_("content-add-file-upload-help-text"))
             ->setDecorators(array('UploadDecorator'))
             ->setAttrib("onchange", "multiFile(this, '".$translate->_("content-add-file-delete-file-button")."');");
        
        $uploadedFilesBoxes = new Zend_Form_Element_MultiCheckbox('uploadedFiles');
        $uploadedFilesBoxes->setMultiOptions($data['filenames'])
        				   ->setRequired(false)
        				   ->setDecorators(array('FormElementDecorator'))
        				   ->setLabel($translate->_('content-add-file-delete-files-label'));
        				   //->setDecorators(array('SettingsCheckboxDecorator'));
        
        // References
		$references = new Zend_Form_Element_Textarea('content_references');
		$references->setValue($data['content_references'])
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
        
        // Language select form element
        $language = new Zend_Form_Element_Select('content_language');
		$language->addFilter('StringtoLower')
                ->setLabel($translate->_("content-add-language"))
				->setDecorators(array('FormElementDecorator'))
				->setMultiOptions($data['languages']);
        
        // Future info classification
        $finfoClasses = new Zend_Form_Element_Select('content_finfo_class');
        $finfoClasses->setLabel($translate->_("content-add-finfo-classification"))
				->setDecorators(array('FormElementDecorator'))
				->setMultiOptions($data['FutureinfoClasses']);
        
		/*
		// Industry select form element
		$industry = new Zend_Form_Element_Select('content_industry');
		$industry->addFilter('StringtoLower')
                ->setValue($data['industryIds'][0])
                ->setLabel($translate->_("content-view-industry-classification"))
				->setDecorators(array('FormElementDecorator'))
				->setAttrib('onchange', "getItems('".$baseUrl."/".$lang."/content/division/industry/'+this.value, 
                            'content_division_div', this.value);")
				->setMultiOptions($data['Industries']);	
        
		$division = new Zend_Form_Element_Select('content_division');
		$division->addFilter('StringtoLower')
                ->setValue($data['industryIds'][1])
				->setDecorators(array('FormElementDecorator'))
                ->setAttrib('onchange', "getItems('".$baseUrl."/".$lang."/content/group/division/'+this.value, 
                            'content_group_div', this.value);")
				->setMultiOptions($data['Divisions'])
                ->setRegisterInArrayValidator(false);
		
		$group = new Zend_Form_Element_Select('content_group');
		$group->addFilter('StringtoLower')
                ->setValue($data['industryIds'][2])
				->setDecorators(array('FormElementDecorator'))
                ->setAttrib('onchange', "getItems('".$baseUrl."/".$lang."/content/class/group/'+this.value, 
                            'content_class_div', this.value);")
				->setMultiOptions($data['Groups'])
                ->setRegisterInArrayValidator(false);
				
		$class = new Zend_Form_Element_Select('content_class');
		$class->addFilter('StringtoLower')
                ->setValue($data['industryIds'][3])
				->setDecorators(array('FormElementDecorator'))
				->setMultiOptions($data['Classes'])
                ->setRegisterInArrayValidator(false);
        */
        
        $innovation = new Zend_Form_Element_Select('innovation_type');
		$innovation->setLabel($translate->_("content-view-innovationtype-classification"))
				->setDecorators(array('FormElementDecorator'))
				->setMultiOptions($data['InnovationTypes']);
        
		// Form buttons
        
        $publish = new Zend_Form_Element_Submit('content_publish');
		$publish->setLabel($translate->_("content-add-publish"))
                ->removeDecorator('DtDdWrapper');
        
		$save = new Zend_Form_Element_Submit('content_save');
		$save->setLabel($translate->_("content-add-save"))
             ->removeDecorator('DtDdWrapper');
             
        $preview = new Zend_Form_Element_Submit('preview');
        $preview->setLabel($translate->_("content-add-preview"))
                ->removeDecorator('DtDdWrapper');
                //->setAttrib('onclick',"populatePreview(); previewRoll('open')");
                
		// Add elements to form
        $elements = array($header, $keywords, $content_type,
                          $textlead, $text, $related_companies);
                          
        if($contentType == "problem") {
            array_push($elements, $research);
        }
        else if($contentType == "finfo") {
            array_push($elements, $opportunity, $threat);
        }
        else if($contentType == "idea") {
            array_push($elements, $solution);
        }
        
        array_push($elements, $file);
        
        if (count($data['filenames'])) {
        	array_push($elements, $uploadedFilesBoxes);
        }
        
        array_push($elements, $references, $language);
        
        /*
        if($contentType == "finfo") {
            array_push($elements, $finfoClasses);
        }
        
        array_push($elements, $industry, $division, $group, $class);

        if($contentType == "idea") {
            array_push($elements, $innovation);
        }
        */
        
        // If data is published dont draw the publish button
 		if ($data['published_cnt'] == false) {
        	array_push($elements, $publish, $save, $preview);
 		}
 		else {
 	        array_push($elements, $save, $preview);		
 		}
 		
        // Set custom form layout
        
        $this->setDecorators(array(array('ViewScript', array(
            'viewScript' => 'forms/contentAddForm.phtml',
            'placement' => false
        ))));
        
        $this->addElements($elements);
        
		/*$this->addElements(array($innovation, $industry, 
                                 $division, $group,
                                 $class, $header,
                                 $keywords, $content_type,
                                 $content_relatesto_id, $textlead,
                                 $text, $submit,
                                 $preview, $publish));*/
	}
}