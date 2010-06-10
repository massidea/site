<?php
/**
 *  AccountSettingsForm -> Form for account settings
 *
 *     Copyright (c) <2009>, Markus RiihelÃ¤
 *     Copyright (c) <2009>, Mikko Sallinen
 *  Copyright (c) <2009>, Joel Peltonen
 *  Copyright (c) <2010>, Mikko Korpinen
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
 *  AccountSettingsForm - class
 *
 *  @package     Forms
 *  @author     Markus RiihelÃ¤, Mikko Sallinen, Joel Peltonen, Mikko Korpinen
 *  @copyright     2009 Markus RiihelÃ¤ & Mikko Sallinen, 2010 Mikko Korpinen
 *  @license     GPL v2
 *  @version     1.0
 */
 
class Default_Form_AccountSettingsForm extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        $this->setEnctype('multipart/form-data');
        $this->setName('edit_profile_form');
        $this->setAttrib('id', 'edit-profile-form');
        $this->addElementPrefixPath('Oibs_Form_Decorator',
                                'Oibs/Form/Decorator/',
                                'decorator');

        // Headers
        $accountInformation = new Oibs_Form_Element_Note('accountinformation');
        $accountInformation->setValue('<h3>Account information</h3>')
                           ->setDecorators(array(
                               array('ViewHelper')
                           ));
        $personalInformation = new Oibs_Form_Element_Note('personalinformation');
        $personalInformation->setValue('<h3>Personal Information</h3>')
                            ->setDecorators(array(
                                array('ViewHelper')
                            ));
        $locationInformation = new Oibs_Form_Element_Note('locationinformation');
        $locationInformation->setValue('<h3>Location Information</h3>')
                            ->setDecorators(array(
                                array('ViewHelper')
                            ));
        $employmentInformation = new Oibs_Form_Element_Note('employmentinformation');
        $employmentInformation->setValue('<h3>Employment Information</h3>')
                              ->setDecorators(array(
                                  array('ViewHelper')
                              ));

        // Array for div clear elements (30)
        $clear = array();
        for ($i=0; $i<30; $i++) {
            $clear[$i] = new Oibs_Form_Element_Note('clear'.$i);
            $clear[$i]->setValue('<div class="clear"></div>')
                      ->setDecorators(array(
                          array('ViewHelper')
                      ));
        }

        // Username for description, I tried to getValue() but it will return NULL
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();
        $usernametext = $identity->username;
        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('Username')
                 ->setDescription($usernametext);
        $username->helper = 'formHidden';
        $usernamepublic = new Zend_Form_Element_CheckBox('usernamepublic');
        $usernamepublic->setDescription(true);
        $usernamepublic->helper = 'formHidden';

        $openid = new Zend_Form_Element_Text('openid');
        $openid->setLabel('Open-ID')
               ->setAttrib('id', 'open-ID');

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password')
                 ->setAttrib('id', 'password');

        $confirmpassword = new Zend_Form_Element_Password('confirmpassword');
        $confirmpassword->setLabel('Confirm password')
                        ->setAttrib('id', 'confirm-password');

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email')
              ->setAttrib('id', 'email')
              ->setRequired(true);

        $firstname = new Zend_Form_Element_Text('firstname');
        $firstname->setLabel('First name')
                  ->setAttrib('id', 'first-name');
        $firstnamepublic = new Zend_Form_Element_CheckBox('firstnamepublic');
        $firstnamepublic->setDescription(true);
        //$fnamepublic->helper = 'formHidden';

        $lastname = new Zend_Form_Element_Text('lastname');
        $lastname->setLabel('Last name')
                 ->setAttrib('id', 'last-name');
        $lastnamepublic = new Zend_Form_Element_CheckBox('lastnamepublic');
        $lastnamepublic->setDescription(true);

        $gender = new Zend_Form_Element_Select('gender');
        $gender->setLabel('Gender')
               ->setAttrib('id', 'gender')
               ->addMultiOptions(array('Select', 'Male', 'Female'));
        $genderpublic = new Zend_Form_Element_CheckBox('genderpublic');
        $genderpublic->setDescription(true);

        $birthday = new Zend_Form_Element_Select('birthday');
        $birthday->setLabel('Date of Birth')
                 ->setAttrib('id', 'dob-day')
                 ->addMultiOptions(array('1', '1', '1'));
        $birthmonth = new Zend_Form_Element_Select('birthmonth');
        $birthmonth->setAttrib('id', 'dob-month')
                   ->addMultiOptions(array('1', '1', '1'));
        $birthyear = new Zend_Form_Element_Select('birthyear');
        $birthyear->setAttrib('id', 'dob-year')
                  ->addMultiOptions(array('1', '1', '1'));
        $birthdaypublic = new Zend_Form_Element_CheckBox('birthdaypublic');
        $birthdaypublic->setDescription(true);

        $biography = new Zend_Form_Element_Textarea('biography');
        $biography->setLabel('Biography')
                  ->setAttrib('id', 'biography')
                  ->setAttrib('rows', 30)
                  ->setAttrib('cols', 45)
                  ->setDescription('<div id="progressbar_biography" class="progress"></div>');

        $intereststext = new Oibs_Form_Element_Note('intereststext');
        $intereststext->setValue(
                '<div class="input-column1"></div>'
                . '<div class="input-column2 help">(Use commas to seperate tags)</div>');
        $interests = new Zend_Form_Element_Text('interests');
        $interests->setLabel('My interest (tags)')
                   ->setAttrib('id', 'interests');

        $language = new Zend_Form_Element_Select('language');
        $language->setLabel('User interface language')
                 ->setAttrib('id', 'user-interface-language')
                 ->addMultiOptions(array('Select', '1', '1'));

        $hometown = new Zend_Form_Element_Text('hometown');
        $hometown->setLabel('Hometown')
                 ->setAttrib('id', 'hometown')
                 ->setRequired(true);
        $hometownpublic = new Zend_Form_Element_CheckBox('hometownpublic');
        $hometownpublic->helper = 'formHidden';
        $hometownpublic->setDescription(true);

        $country = new Zend_Form_Element_Select('country');
        $country->setLabel('Country of Residence')
                ->setAttrib('id', 'country-of-residence')
                ->addMultiOptions(array('Select', '1', '1'));
        $countrypublic = new Zend_Form_Element_CheckBox('countrypublic');
        $countrypublic->setDescription(true);

        $timezone = new Zend_Form_Element_Select('timezone');
        $timezone->setLabel('Time Zone')
                 ->setAttrib('id', 'time-zone')
                 ->addMultiOptions(array('Select', '1', '1'));
        $timezonepublic = new Zend_Form_Element_CheckBox('timezonepublic');
        $timezonepublic->setDescription(true);

        $status = new Zend_Form_Element_Select('status');
        $status->setLabel('I am currently')
               ->setAttrib('id', 'status')
               ->setRequired(true)
               ->addMultiOptions(array('Select', '1', '1'));
        $statuspublic = new Zend_Form_Element_CheckBox('statuspublic');
        $statuspublic->setDescription(true);

        $employer_organization = new Zend_Form_Element_Text('employer_organization');
        $employer_organization->setLabel('EmployerOrganization')
                              ->setAttrib('id', 'employer-organization');
        $employer_organizationpublic = new Zend_Form_Element_CheckBox('employer_organizationpublic');
        $employer_organizationpublic->setDescription(true);

        $position = new Zend_Form_Element_Select('position');
        $position->setLabel('Position')
                 ->setAttrib('id', 'status')
                 ->addMultiOptions(array('Select', '1', '1'));
        $positionpublic = new Zend_Form_Element_CheckBox('positionpublic');
        $positionpublic->setDescription(true);

        $save = new Zend_Form_Element_Submit('save');
        $save->setLabel('Save profile')
             ->setAttrib('id', 'save-profile')
             ->setAttrib('class', 'submit-button');

        $cancel = new Zend_Form_Element_Submit('cancel');
        $cancel->setLabel('Cancel')
               ->setAttrib('id', 'cancel')
               ->setAttrib('class', 'submit-button');
        
        
        $this->addElements(array(
                            next($clear),
                            $accountInformation,
                            next($clear),
                            $username,
                            $usernamepublic,
                            next($clear),
                            $openid,
                            next($clear),
                            $password,
                            next($clear),
                            $confirmpassword,
                            next($clear),
                            $personalInformation,
                            next($clear),
                            $email,
                            next($clear),
                            $firstname,
                            $firstnamepublic,
                            next($clear),
                            $lastname,
                            $lastnamepublic,
                            next($clear),
                            $gender,
                            $genderpublic,
                            next($clear),
                            $birthday,
                            $birthmonth,
                            $birthyear,
                            $birthdaypublic,
                            next($clear),
                            $biography,
                            next($clear),
                            $intereststext,
                            next($clear),
                            $interests,
                            next($clear),
                            $language,
                            next($clear),
                            $locationInformation,
                            next($clear),
                            $hometown,
                            $hometownpublic,
                            next($clear),
                            $country,
                            $countrypublic,
                            next($clear),
                            $timezone,
                            $timezonepublic,
                            next($clear),
                            $employmentInformation,
                            next($clear),
                            $status,
                            $statuspublic,
                            next($clear),
                            $employer_organization,
                            $employer_organizationpublic,
                            next($clear),
                            $position,
                            $positionpublic,
                            next($clear),
                            $save,
                            $cancel,
                           ));

        $accountInformation->setDecorators(array('ViewHelper'));
        $personalInformation->setDecorators(array('ViewHelper'));
        $locationInformation->setDecorators(array('ViewHelper'));
        $employmentInformation->setDecorators(array('ViewHelper'));

        $username->setDecorators(array('InputColumn1And2Decorator'));
        $usernamepublic->setDecorators(array('InputColumn3Decorator'));
        $openid->setDecorators(array('InputColumn1And2Decorator'));
        $password->setDecorators(array('InputColumn1And2Decorator'));
        $confirmpassword->setDecorators(array('InputColumn1And2Decorator'));
        $email->setDecorators(array('InputColumn1And2Decorator'));
        $firstname->setDecorators(array('InputColumn1And2Decorator'));
        $firstnamepublic->setDecorators(array('InputColumn3Decorator'));
        $lastname->setDecorators(array('InputColumn1And2Decorator'));
        $lastnamepublic->setDecorators(array('InputColumn3Decorator'));
        $gender->setDecorators(array('InputColumn1And2Decorator'));
        $genderpublic->setDecorators(array('InputColumn3Decorator'));
        $birthday->setDecorators(array('InputColumn1And2NoEndTagDecorator'));
        $birthmonth->setDecorators(array('ViewHelper'));
        $birthyear->setDecorators(array(
            'ViewHelper',
            array('HtmlTag', array('tag' => 'div', 'closeOnly' => true)),
            ));
        $birthdaypublic->setDecorators(array('InputColumn3Decorator'));
        $biography->setDecorators(array('InputColumn1And2Decorator'));
        $intereststext->setDecorators(array('ViewHelper'));
        $interests->setDecorators(array('InputColumn1And2Decorator'));
        $language->setDecorators(array('InputColumn1And2Decorator'));
        $hometown->setDecorators(array('InputColumn1And2Decorator'));
        $hometownpublic->setDecorators(array('InputColumn3Decorator'));
        $country->setDecorators(array('InputColumn1And2Decorator'));
        $countrypublic->setDecorators(array('InputColumn3Decorator'));
        $timezone->setDecorators(array('InputColumn1And2Decorator'));
        $timezonepublic->setDecorators(array('InputColumn3Decorator'));
        $status->setDecorators(array('InputColumn1And2Decorator'));
        $statuspublic->setDecorators(array('InputColumn3Decorator'));
        $position->setDecorators(array('InputColumn1And2Decorator'));
        $positionpublic->setDecorators(array('InputColumn3Decorator'));
        $employer_organization->setDecorators(array('InputColumn1And2Decorator'));
        $employer_organizationpublic->setDecorators(array('InputColumn3Decorator'));
        $save->setDecorators(array(
            'ViewHelper',
            array('HtmlTag', array('tag' => 'div', 'openOnly' => true, 'id' => 'save_changes')),
            ));
        $cancel->setDecorators(array(
            'ViewHelper',
            array('HtmlTag', array('tag' => 'div', 'closeOnly' => true)),
            ));

        $this->setDecorators(array(
            'FormElements',
            'Form'
        ));
    }

    public function construct($options = null)
    { 
        parent::__construct($options);
        try{
        $translate = Zend_Registry::get('Zend_Translate');

        $this->setName('account_settings_form');
        $this->addElementPrefixPath('Oibs_Decorators',
                                'Oibs/Decorators/',
                                'decorator');

        $mailvalid = new Zend_Validate_EmailAddress();
        $mailvalid->setMessage(
            'email-invalid',
            Zend_Validate_EmailAddress::INVALID);
        $mailvalid->setMessage(
            'email-invalid-hostname',
            Zend_Validate_EmailAddress::INVALID_HOSTNAME);
        $mailvalid->setMessage(
            'email-invalid-mx-record',
            Zend_Validate_EmailAddress::INVALID_MX_RECORD);
        $mailvalid->setMessage(
            'email-dot-atom',
            Zend_Validate_EmailAddress::DOT_ATOM);
        $mailvalid->setMessage(
            'email-quoted-string',
            Zend_Validate_EmailAddress::QUOTED_STRING);
        $mailvalid->setMessage(
            'email-invalid-local-part',
            Zend_Validate_EmailAddress::INVALID_LOCAL_PART);
        $mailvalid->setMessage(
            'email-length-exceeded',
            Zend_Validate_EmailAddress::LENGTH_EXCEEDED);
        $mailvalid->hostnameValidator->setMessage(
            'hostname-invalid-hostname',
            Zend_Validate_Hostname::INVALID_HOSTNAME);
        $mailvalid->hostnameValidator->setMessage(
            'hostname-local-name-not-allowed',
            Zend_Validate_Hostname::LOCAL_NAME_NOT_ALLOWED);
        $mailvalid->hostnameValidator->setMessage(
            'hostname-unknown-tld',
            Zend_Validate_Hostname::UNKNOWN_TLD);
        $mailvalid->hostnameValidator->setMessage(
            'hostname-invalid-local-name',
            Zend_Validate_Hostname::INVALID_LOCAL_NAME);
        $mailvalid->hostnameValidator->setMessage(
            'hostname-undecipherable-tld',
            Zend_Validate_Hostname::UNDECIPHERABLE_TLD);
            
        // Public text
        $description = "Public";

        // OpenID input form element !! ADD DUPE VALIDATOR !!
        $openID = new Zend_Form_Element_Text('openid');
        $openID->setLabel("Open-ID:")
                    ->addValidators(array(
                            new Oibs_Validators_OpenidExists(),
                            array('NotEmpty', true, array('messages' => array('isEmpty' => 'Empty'))),
                            ))
                    ->setDecorators(array('CustomDecorator'));
        /*
        // Password input form element
        $password = new Zend_Form_Element_Password('password');
        $password->setLabel("Password")
                //->setRequired(true)
                ->addValidators(array(
                new Oibs_Validators_RepeatValidator('confirm_password'),
                array('NotEmpty', true, array('messages' => array('isEmpty' => 'Empty'))),
                array('StringLength', false, array(4, 22, 'messages' => array('stringLengthTooShort' => 'PASSWORD TOO SHORT'))),
                ))
                ->setDecorators(array('CustomDecorator'));

        // Password confirm input form element
        $password_confirm = new Zend_Form_Element_Password('confirm_password');
        $password_confirm->setLabel("Confirm password")
                //->setRequired(true)
                ->addValidators(array(
                array('NotEmpty', true, array('messages' => array('isEmpty' => 'Empty'))),
                array('StringLength', false, array(4, 22, 'messages' => array('stringLengthTooShort' => 'PASSWORD TOO SHORT'))),
                ))
                ->setDecorators(array('CustomDecorator'));

        // E-mail input form element
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel("Email:")
                //->setRequired(true)
                ->addFilter('StringtoLower')
                ->addValidators(array(
                    new Oibs_Validators_RepeatValidator('confirm_email'),
                    $mailvalid,
                    array('NotEmpty',
                        true,
                        array('messages' => array('isEmpty' => 'Empty'))
                    ),
                    array('StringLength',
                        false,
                        array(6, 50, 'messages' => array('stringLengthTooShort' => 'E-MAIL TOO SHORT'))
                    ),
                ))
                ->setDecorators(array('CustomDecorator'));
                // ->removeDecorator('errors');

        $email_publicity = new Zend_Form_Element_Checkbox('email_publicity');
        $email_publicity->setDecorators(array('SettingsCheckboxDecorator'))
                ->setDescription($description);

        // First name input form element
        $firstname = new Zend_Form_Element_Text('firstname');
        $firstname->setLabel("First name:")
                //->setRequired(true)
                //->addFilter('StringtoLower')
                ->addValidators(array(
                array('NotEmpty', true, array('messages' => array('isEmpty' => 'Empty'))),
                ))
                ->setDecorators(array('SettingsTextDecorator'));
                
        $firstname_publicity = new Zend_Form_Element_Checkbox('firstname_publicity');
        $firstname_publicity->setDecorators(array('SettingsCheckboxDecorator'))
                ->setDescription($description);

        // Lastname input form element
        $lastname = new Zend_Form_Element_Text('lastname');
        $lastname->setLabel("Last name")
                //->setRequired(true)
                //->addFilter('StringtoLower')
                ->addValidators(array(
                array('NotEmpty', true, array('messages' => array('isEmpty' => 'Empty'))),
                ))
                ->setDecorators(array('SettingsTextDecorator'));
                
        $lastname_publicity = new Zend_Form_Element_Checkbox('lastname_publicity');
        $lastname_publicity->setDecorators(array('SettingsCheckboxDecorator'))
                ->setDescription($description);

        // Gender input form element
        $gender = new Zend_Form_Element_Select('gender');
        $gender->setLabel("Gender:")
                ->addFilter('StringtoLower')
                ->setDecorators(array('SettingsSelectDecorator'))
                ->setMultiOptions(array(1=>"Male",2=>"Female"));
             
        $gender_publicity = new Zend_Form_Element_Checkbox('gender_publicity');
        $gender_publicity->setDecorators(array('SettingsCheckboxDecorator'))
                ->setDescription($description);

        // Birthday input form element
        $birth = new Zend_Form_Element_Text('birthday');
        $birth->setLabel("Date of birth:")
                    ->addValidators(array(
                            array('NotEmpty', true, array('messages' => array('isEmpty' => 'Empty'))),
                            ))
                    ->setDecorators(array('SettingsTextDecorator'));

        // $birth_publicity = new Zend_Form_Element_Checkbox('birth_publicity');
        $birth_publicity = new Zend_Form_Element_Checkbox('birthday_publicity');
        $birth_publicity->setDecorators(array('SettingsCheckboxDecorator'))
                ->setDescription($description);

        // Biography input form element
        $bio = new Zend_Form_Element_Text('biography');
        $bio->setLabel("Biography")
                    ->addValidators(array(
                            array('NotEmpty', true, array('messages' => array('isEmpty' => 'Empty'))),
                            ))
                    //->setAttrib('rows','4')
                    //->setAttrib('cols','31')
                    ->setDecorators(array('SettingsTextDecorator'));

        // $bio_publicity = new Zend_Form_Element_Checkbox('bio_publicity');
        $bio_publicity = new Zend_Form_Element_Checkbox('biography_publicity');
        $bio_publicity->setDecorators(array('SettingsCheckboxDecorator'))
                ->setDescription($description);

        // Hometown input form element
        $hometown = new Zend_Form_Element_Text('hometown');
        $hometown->setLabel("Hometown:")
                    ->addValidators(array(
                            array('NotEmpty', true, array('messages' => array('isEmpty' => 'Empty'))),
                            ))
                    ->setDecorators(array('SettingsTextDecorator'));
                
        $hometown_publicity = new Zend_Form_Element_Checkbox('hometown_publicity');
        $hometown_publicity->setDecorators(array('SettingsCheckboxDecorator'))
                ->setDescription($description);

        //Country input form element
        $country =  new Zend_Form_Element_Select('country');
        $country->setLabel("Country of Residence")
                ->setDecorators(array('SettingsSelectDecorator'));
        $countries = new Default_Model_UserCountry();
        $a = $countries->fetchAll();
        foreach ($a as $b)
        {
            $countryarray[$b->id_ctr]=$b->name_ctr;
        }
        if (isset($countryarray) )
            $country->setMultiOptions($countryarray);
        else
            $country->setMultiOptions(array("None",""));

        $country_publicity = new Zend_Form_Element_Checkbox('country_publicity');
        $country_publicity->setDecorators(array('SettingsCheckboxDecorator'))
                ->setDescription($description);             
        */
        // Form submit buttom form element
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel($translate->_("account-register-submit"));

        // Add elements to form
        $this->setAttrib('enctype', 'multipart/form-data');
        $this->addElements(array(   $openID,
                                    /*$password,
                                    $password_confirm,
                                    
                                    $email,
                                    $firstname, $firstname_publicity,
                                    $lastname, $lastname_publicity,
                                    $gender, $gender_publicity,
                                    $birth, $birth_publicity,
                                    $bio, $bio_publicity,

                                    $hometown, $hometown_publicity,
                                    $country, $country_publicity,*/

                                    $submit));

        // if you use try..catch Don't echo e!!
        }catch(Zend_Exception $e){echo '<pre>General error occurred! Please try again.';echo '</pre>';}
    }

}