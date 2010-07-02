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

    // WIP 18.6.2010
    // TODO: Email error messages, filters? validations?
    //       Avatar image, country list?

    /* To usr_profiles_usp:
     * openid
     * phone
     * fistname
     * surname (lastname)
     * gender - 1=Male, 2=Female
     * birthday - dd/mm/yyyy
     * biography - max 4000
     * userlanguage - language id from languages_lng
     * city
     * address
     * country
     * timezone - timezone id from timezones_tmz
     * employment (I am currently status) - ie. private_status
     * company (employer / organization)
     */
    
    public function init()
    {
        $this->setMethod('post');
        $this->setEnctype('multipart/form-data');
        $this->setName('edit_profile_form');
        $this->setAttrib('id', 'edit-profile-form');
        $this->addElementPrefixPath('Oibs_Form_Decorator',
                                'Oibs/Form/Decorator/',
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

        // Headers
        $accountInformation = new Oibs_Form_Element_Note('accountinformation');
        $accountInformation->setValue('<div class="clear"></div><h3>Account information</h3><div class="clear"></div>');

        $personalInformation = new Oibs_Form_Element_Note('personalinformation');
        $personalInformation->setValue('<h3>Personal Information</h3><div class="clear"></div>');

        $locationInformation = new Oibs_Form_Element_Note('locationinformation');
        $locationInformation->setValue('<h3>Location Information</h3><div class="clear"></div>');

        $employmentInformation = new Oibs_Form_Element_Note('employmentinformation');
        $employmentInformation->setValue('<h3>Employment Information</h3><div class="clear"></div>');

        // Public text
        $publictext = 'Public';

        // Clear div
        $clear = '<div class="clear"></div>';

        // Username for description
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();
        $usernametext = $identity->username;
        $username = new Zend_Form_Element_Hidden('username');
        $username->setLabel('Username')
                 ->setDescription($usernametext);
        $usernamepublic = new Zend_Form_Element_Hidden('username_publicity');
        $usernamepublic->setLabel($publictext);

        $openid = new Zend_Form_Element_Text('openid');
        $openid->setLabel('Open-ID')
               ->setAttrib('id', 'open-ID')
               ->addValidators(array(
                   new Oibs_Validators_OpenidExists(),  // Not working?
               ));
        $openidclear = new Oibs_Form_Element_Note('openidclear');
        $openidclear->setValue($clear);

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password')
                 ->setAttrib('id', 'password')
                 ->addValidators(array(
                    new Oibs_Validators_RepeatValidator('confirm_password'),
                    array('NotEmpty', true, array('messages' => array('isEmpty' => 'Empty'))),
                    array('StringLength', false, array(4, 22,
                        'messages' => array('stringLengthTooShort' => 'Password too short (4-22 characters)',
                                            'stringLengthTooLong' => 'Password too long (4-22 characters)'))),
				 ));
        $passwordclear = new Oibs_Form_Element_Note('passwordclear');
        $passwordclear->setValue($clear);

        $confirmpassword = new Zend_Form_Element_Password('confirm_password');
        $confirmpassword->setLabel('Confirm password')
                        ->setAttrib('id', 'confirm-password')
                        ->addValidators(array(
                            array('NotEmpty', true, array('messages' => array('isEmpty' => 'Empty'))),
                            array('StringLength', false, array(4, 22,
                                'messages' =>array('stringLengthTooShort' => 'Password too short (4-22 characters)',
                                                   'stringLengthTooLong' => 'Password too long (4-22 characters)'))),
                        ));
        $confirmpasswordclear = new Oibs_Form_Element_Note('confirm_passwordclear');
        $confirmpasswordclear->setValue($clear);

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email')
              ->setAttrib('id', 'email')
              ->setRequired(true)
              ->addFilter('StringtoLower')
              ->addValidators(array(
                  $mailvalid,
                  array('NotEmpty',
                      true,
                      array('messages' => array('isEmpty' => 'Email empty'))
                  ),
                  array('StringLength',
                      false,
                      array(6, 50, 'messages' => array('stringLengthTooShort' => 'Email too short (6-50 characters)',
                                                       'stringLengthTooLong' => 'Email too long (6-50 characters)'))
                  ),
              ));
        $emailclear = new Oibs_Form_Element_Note('emailclear');
        $emailclear->setValue($clear);

        $gravatar = new Zend_Form_Element_Hidden('gravatartext');
        $gravatar->setLabel('Gravatar')
                 ->setDescription('<div style="text-align: right;">Enable <a href="http://www.gravatar.com">gravatar</a></div>');
        $gravatarcheck = new Zend_Form_Element_Checkbox('gravatar');

        $phone = new Zend_Form_Element_Text('phone');
        $phone->setLabel('Phone')
              ->setAttrib('id', 'phone');
        $phonepublic = new Zend_Form_Element_CheckBox('phone_publicity');
        $phonepublic->setLabel($publictext);

        $firstname = new Zend_Form_Element_Text('firstname');
        $firstname->setLabel('First name')
                  ->setAttrib('id', 'first-name');
        $firstnamepublic = new Zend_Form_Element_CheckBox('firstname_publicity');
        $firstnamepublic->setLabel($publictext);

        // DB: surname
        $lastname = new Zend_Form_Element_Text('surname');
        $lastname->setLabel('Last name')
                 ->setAttrib('id', 'last-name');
        $lastnamepublic = new Zend_Form_Element_CheckBox('surname_publicity');
        $lastnamepublic->setLabel($publictext);

        $gender = new Zend_Form_Element_Select('gender');
        $gender->setLabel('Gender')
               ->setAttrib('id', 'gender')
               ->addMultiOptions(array('Select', 'Male', 'Female'));
        $genderpublic = new Zend_Form_Element_CheckBox('gender_publicity');
        $genderpublic->setLabel($publictext);

        $birthday = new Zend_Form_Element_Text('birthday');
        $birthday->setLabel('Date of Birth')
                 ->setAttrib('id', 'birthday')
                 ->setValidators(array(new Zend_Validate_Date('birthday')));
        $birthdaypublic = new Zend_Form_Element_CheckBox('birthday_publicity');
        $birthdaypublic->setLabel($publictext);

        $biography = new Zend_Form_Element_Textarea('biography');
        $biography->setLabel('Biography')
                  ->setAttrib('id', 'biography')
                  ->setAttrib('rows', 30)
                  ->setAttrib('cols', 45)
                  ->addValidators(array(
                      array('StringLength',
                          false,
                          array(0, 4000, 'messages' => array('stringLengthTooLong' => 'Biography too long'))
                      ),
                  ));
                   //->setDescription('<div id="progressbar_biography" class="progress_ok"></div>');
        $biographypublic = new Zend_Form_Element_CheckBox('biography_publicity');
        $biographypublic->setLabel($publictext);

        $intereststext = new Oibs_Form_Element_Note('intereststext');
        $intereststext->setValue(
                '<div class="input-column1"></div>'
                . '<div class="input-column2 help">(Use commas to seperate tags)</div><div class="clear"></div>');
        $interests = new Zend_Form_Element_Text('interests');
        $interests->setLabel('My interest (tags)')
                   ->setAttrib('id', 'interests');
        $interestsclear = new Oibs_Form_Element_Note('interestsclear');
        $interestsclear->setValue($clear);

        $weblinks_websites = new Oibs_Form_Element_Note('weblinks_websites');
        $weblinks_websites->setValue('<div class="input-column-website1"><label><strong>Links to my websites:</strong></label></div>');
        $weblinks_name = new Oibs_Form_Element_Note('weblinks_name');
        $weblinks_name->setValue('<div class="input-column-website2">Name</div>');
        $weblinks_url = new Oibs_Form_Element_Note('weblinks_url');
        $weblinks_url->setValue('<div class="input-column-website3">Url</div><div class="clear"></div>');

        $weblinks_name_site1 = new Zend_Form_Element_Text('weblinks_name_site1');
        $weblinks_name_site1->setLabel('Web site 1')
                         ->setAttrib('id', 'website1-name');
        $weblinks_url_site1 = new Zend_Form_Element_Text('weblinks_url_site1');
        $weblinks_url_site1->setAttrib('id', 'website1-url');

        $weblinks_name_site2 = new Zend_Form_Element_Text('weblinks_name_site2');
        $weblinks_name_site2->setLabel('Web site 2')
                         ->setAttrib('id', 'website2-name');
        $weblinks_url_site2 = new Zend_Form_Element_Text('weblinks_url_site2');
        $weblinks_url_site2->setAttrib('id', 'website2-url');

        $weblinks_name_site3 = new Zend_Form_Element_Text('weblinks_name_site3');
        $weblinks_name_site3->setLabel('Web site 3')
                         ->setAttrib('id', 'website3-name');
        $weblinks_url_site3 = new Zend_Form_Element_Text('weblinks_url_site3');
        $weblinks_url_site3->setAttrib('id', 'website3-url');

        $weblinks_name_site4 = new Zend_Form_Element_Text('weblinks_name_site4');
        $weblinks_name_site4->setLabel('Web site 4')
                         ->setAttrib('id', 'website4-name');
        $weblinks_url_site4 = new Zend_Form_Element_Text('weblinks_url_site4');
        $weblinks_url_site4->setAttrib('id', 'website4-url');

        $weblinks_name_site5 = new Zend_Form_Element_Text('weblinks_name_site5');
        $weblinks_name_site5->setLabel('Web site 5')
                         ->setAttrib('id', 'website5-name');
        $weblinks_url_site5 = new Zend_Form_Element_Text('weblinks_url_site5');
        $weblinks_url_site5->setAttrib('id', 'website5-url');

        $languages = New Default_Model_Languages();
        $allLanguages = $languages->getAllNamesAndIds();
        $userlanguage = new Zend_Form_Element_Select('userlanguage');
        $userlanguage->setLabel('User interface language')
                     ->setAttrib('id', 'user-interface-language')
                     ->addMultiOption('', 'Select');
        foreach ($allLanguages as $language) {
            $userlanguage->addMultiOption($language['id_lng'], $language['name_lng']);
        }
        $userlanguageclear = new Oibs_Form_Element_Note('userlanguageclear');
        $userlanguageclear->setValue($clear);

        /*
        $avatar = new Zend_Form_Element_File('avatar');
        $avatar->setLabel('Avatar image');

        
        */

        // DB: city
        $hometown = new Zend_Form_Element_Text('city');
        $hometown->setLabel('Hometown')
                 ->setAttrib('id', 'hometown')
                 ->setRequired(true)
                 ->addValidators(array(
                            array('NotEmpty', true, array('messages' => array('isEmpty' => 'Hometown empty')))
                 ));
        $hometownpublic = new Zend_Form_Element_CheckBox('city_publicity');
        $hometownpublic->setLabel($publictext);
        $hometownpublic->helper = 'FormHidden';

        $address = new Zend_Form_Element_Text('address');
        $address->setLabel('Address')
                ->setAttrib('id', 'address');
        $addresspublic = new Zend_Form_Element_CheckBox('address_publicity');
        $addresspublic->setLabel($publictext)
                      ->setAttrib('checked', 'checked')
                      ->setValue(1);
        $addresspublic->helper = 'FormHidden';

        $country_model = new Default_Model_Countries();
        $allCountries = $country_model->getAllCountries();
        $usercountry = new Zend_Form_Element_Select('country');
        $usercountry->setLabel('Country of Residence')
                ->setAttrib('id', 'country')
                ->addMultiOption('', 'Select');
        foreach ($allCountries as $country) {
            $usercountry->addMultiOption($country['iso_ctr'], $country['printable_name_ctr']);
        }
        $usercountrypublic = new Zend_Form_Element_CheckBox('country_publicity');
        $usercountrypublic->setLabel($publictext);

        $timezone_model = new Default_Model_Timezones();
        $allTimezones = $timezone_model->getAllTimezones();
        $usertimezone = new Zend_Form_Element_Select('usertimezone');
        $usertimezone->setLabel('Time Zone')
                 ->setAttrib('id', 'time-zone')
                 ->addMultiOption('', 'Select');
        foreach ($allTimezones as $timezone) {
            $usertimezone->addMultiOption($timezone['id_tmz'], $timezone['gmt_tmz'].' '.$timezone['timezone_location_tmz']);
        }
        $usertimezonepublic = new Zend_Form_Element_CheckBox('usertimezone_publicity');
        $usertimezonepublic->setLabel($publictext);

        $userProfilesModel = new Default_Model_UserProfiles();
        $employments = $userProfilesModel->getEmployments();
        $employments = array_merge(array('' => 'Select'), $employments);
        $employment = new Zend_Form_Element_Select('employment');
        $employment->setLabel('I am currently')
                   ->setAttrib('id', 'status')
                   ->setRequired(true)
                   ->addMultiOptions($employments)
                   ->setErrorMessages(array('Select status'));
        $employmentpublic = new Zend_Form_Element_CheckBox('employment_publicity');
        $employmentpublic->setLabel($publictext);

        // DB: company
        $employer_organization = new Zend_Form_Element_Text('company');
        $employer_organization->setLabel('Employer / Organization')
                              ->setAttrib('id', 'employer-organization');
        $employer_organizationpublic = new Zend_Form_Element_CheckBox('company_publicity');
        $employer_organizationpublic->setLabel($publictext);

        $save = new Zend_Form_Element_Submit('save');
        $save->setLabel('Save profile')
             ->setAttrib('id', 'save-profile')
             ->setAttrib('class', 'submit-button');

        $cancel = new Zend_Form_Element_Submit('cancel');
        $cancel->setLabel('Cancel')
               ->setAttrib('id', 'cancel')
               ->setAttrib('class', 'submit-button');
        
        
        $this->addElements(array(
                            $accountInformation,
                            $username,
                            $usernamepublic,
                            $openid,
                            $openidclear,
                            $password,
                            $passwordclear,
                            $confirmpassword,
                            $confirmpasswordclear,
                            $personalInformation,
                            $email,
                            $emailclear,
                            $gravatar,
                            $gravatarcheck,
                            $phone,
                            $phonepublic,
                            $firstname,
                            $firstnamepublic,
                            $lastname,
                            $lastnamepublic,
                            $gender,
                            $genderpublic,
                            $birthday,
                            $birthdaypublic,
                            $biography,
                            $biographypublic,
            // User intrests (tag)
                            //$intereststext,
                            //$interests,
                            //$interestsclear,
                            $weblinks_websites,
                            $weblinks_name,
                            $weblinks_url,
                            $weblinks_name_site1,
                            $weblinks_url_site1,
                            $weblinks_name_site2,
                            $weblinks_url_site2,
                            $weblinks_name_site3,
                            $weblinks_url_site3,
                            $weblinks_name_site4,
                            $weblinks_url_site4,
                            $weblinks_name_site5,
                            $weblinks_url_site5,
                            $userlanguage,
                            $userlanguageclear,
                            $locationInformation,
                            $hometown,
                            $hometownpublic,
                            $address,
                            $addresspublic,
                            $usercountry,
                            $usercountrypublic,
                            $usertimezone,
                            $usertimezonepublic,
                            $employmentInformation,
                            $employment,
                            $employmentpublic,
                            $employer_organization,
                            $employer_organizationpublic,
                            $save,
                            $cancel,
                           ));

        $accountInformation->setDecorators(array('ViewHelper'));
        $personalInformation->setDecorators(array('ViewHelper'));
        $locationInformation->setDecorators(array('ViewHelper'));
        $employmentInformation->setDecorators(array('ViewHelper'));

        $username->setDecorators(array('InputDecorator'));
        $usernamepublic->setDecorators(array('PublicDecorator'));
        $openid->setDecorators(array('InputDecorator'));
        $openidclear->setDecorators(array('ViewHelper'));
        $password->setDecorators(array('InputDecorator'));
        $passwordclear->setDecorators(array('ViewHelper'));
        $confirmpassword->setDecorators(array('InputDecorator'));
        $confirmpasswordclear->setDecorators(array('ViewHelper'));
        $email->setDecorators(array('InputDecorator'));
        $emailclear->setDecorators(array('ViewHelper'));
        $gravatar->setDecorators(array('InputDecorator'));
        $gravatarcheck->setDecorators(array('PublicDecorator'));
        $phone->setDecorators(array('InputDecorator'));
        $phonepublic->setDecorators(array('PublicDecorator'));
        $firstname->setDecorators(array('InputDecorator'));
        $firstnamepublic->setDecorators(array('PublicDecorator'));
        $lastname->setDecorators(array('InputDecorator'));
        $lastnamepublic->setDecorators(array('PublicDecorator'));
        $gender->setDecorators(array('InputDecorator'));
        $genderpublic->setDecorators(array('PublicDecorator'));
        $birthday->setDecorators(array('InputDecorator'));
        $birthdaypublic->setDecorators(array('PublicDecorator'));
        $biography->setDecorators(array('InputDecorator'));
        $biographypublic->setDecorators(array('PublicDecorator'));
        $intereststext->setDecorators(array('ViewHelper'));
        $interests->setDecorators(array('InputDecorator'));
        $interestsclear->setDecorators(array('ViewHelper'));
        $weblinks_websites->setDecorators(array('ViewHelper'));
        $weblinks_name->setDecorators(array('ViewHelper'));
        $weblinks_url->setDecorators(array('ViewHelper'));
        $weblinks_name_site1->setDecorators(array('InputWebsiteNameDecorator'));
        $weblinks_url_site1->setDecorators(array('InputWebsiteUrlDecorator'));
        $weblinks_name_site2->setDecorators(array('InputWebsiteNameDecorator'));
        $weblinks_url_site2->setDecorators(array('InputWebsiteUrlDecorator'));
        $weblinks_name_site3->setDecorators(array('InputWebsiteNameDecorator'));
        $weblinks_url_site3->setDecorators(array('InputWebsiteUrlDecorator'));
        $weblinks_name_site4->setDecorators(array('InputWebsiteNameDecorator'));
        $weblinks_url_site4->setDecorators(array('InputWebsiteUrlDecorator'));
        $weblinks_name_site5->setDecorators(array('InputWebsiteNameDecorator'));
        $weblinks_url_site5->setDecorators(array('InputWebsiteUrlDecorator'));
        $userlanguage->setDecorators(array('InputDecorator'));
        $userlanguageclear->setDecorators(array('ViewHelper'));
        $hometown->setDecorators(array('InputDecorator'));
        $hometownpublic->setDecorators(array('PublicDecorator'));
        $address->setDecorators(array('InputDecorator'));
        $addresspublic->setDecorators(array('PublicDecorator'));
        $usercountry->setDecorators(array('InputDecorator'));
        $usercountrypublic->setDecorators(array('PublicDecorator'));
        $usertimezone->setDecorators(array('InputDecorator'));
        $usertimezonepublic->setDecorators(array('PublicDecorator'));
        $employment->setDecorators(array('InputDecorator'));
        $employmentpublic->setDecorators(array('PublicDecorator'));
        $employer_organization->setDecorators(array('InputDecorator'));
        $employer_organizationpublic->setDecorators(array('PublicDecorator'));
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
/*
    public function __construct($options = null)
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

		$translate = Zend_Registry::get('Zend_Translate');
        $description = $translate->translate("account-profile-public");

		// First name input form element
		$firstname = new Zend_Form_Element_Text('firstname');
		$firstname->setLabel($translate->_("account-profile-first-name"))
				//->setRequired(true)
				//->addFilter('StringtoLower')
				->addValidators(array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Empty'))),
				))
				->setDecorators(array('SettingsTextDecorator'));

        $firstname_publicity = new Zend_Form_Element_Checkbox('firstname_publicity');
		$firstname_publicity->setDecorators(array('SettingsCheckboxDecorator'))
                ->setDescription($description);

		// Surname input form element
		$surname = new Zend_Form_Element_Text('surname');
		$surname->setLabel($translate->_("account-profile-surname"))
				//->setRequired(true)
				//->addFilter('StringtoLower')
				->addValidators(array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Empty'))),
				))
				->setDecorators(array('SettingsTextDecorator'));

        $surname_publicity = new Zend_Form_Element_Checkbox('surname_publicity');
		$surname_publicity->setDecorators(array('SettingsCheckboxDecorator'))
                ->setDescription($description);

		// Gender input form element
		$gender = new Zend_Form_Element_Select('gender');
		$gender->setLabel($translate->_("Gender"))
				->addFilter('StringtoLower')
				->setDecorators(array('SettingsSelectDecorator'))
				->setMultiOptions(array(1=>"Male",2=>"Female"));

        $gender_publicity = new Zend_Form_Element_Checkbox('gender_publicity');
		$gender_publicity->setDecorators(array('SettingsCheckboxDecorator'))
                ->setDescription($description);

		// Profession input form element
		$profession = new Zend_Form_Element_Text('profession');
		$profession->setLabel($translate->_("account-profile-profession"))
					->addValidators(array(
							array('NotEmpty', true, array('messages' => array('isEmpty' => 'Empty'))),
							))
					->setDecorators(array('SettingsTextDecorator'));

        $profession_publicity = new Zend_Form_Element_Checkbox('profession_publicity');
		$profession_publicity->setDecorators(array('SettingsCheckboxDecorator'))
                ->setDescription($description);

		// Company input form element
		$com = new Zend_Form_Element_Text('company');
		$com->setLabel($translate->_("account-profile-company"))
					->addValidators(array(
							array('NotEmpty', true, array('messages' => array('isEmpty' => 'Empty'))),
							))
					->setDecorators(array('SettingsTextDecorator'));

        // $com_publicity = new Zend_Form_Element_Checkbox('com_publicity');
        $com_publicity = new Zend_Form_Element_Checkbox('company_publicity');
		$com_publicity->setDecorators(array('SettingsCheckboxDecorator'))
                ->setDescription($description);

		// City input form element
		$city = new Zend_Form_Element_Text('city');
		$city->setLabel($translate->_("account-profile-city"))
					->addValidators(array(
							array('NotEmpty', true, array('messages' => array('isEmpty' => 'Empty'))),
							))
					->setDecorators(array('SettingsTextDecorator'));

        $city_publicity = new Zend_Form_Element_Checkbox('city_publicity');
		$city_publicity->setDecorators(array('SettingsCheckboxDecorator'))
                ->setDescription($description);

		// Phone input form element
		$phone = new Zend_Form_Element_Text('phone');
		$phone->setLabel($translate->_("account-profile-phone"))
					->addValidators(array(
							array('NotEmpty', true, array('messages' => array('isEmpty' => 'Empty'))),
							))
					->setDecorators(array('SettingsTextDecorator'));

        $phone_publicity = new Zend_Form_Element_Checkbox('phone_publicity');
		$phone_publicity->setDecorators(array('SettingsCheckboxDecorator'))
                ->setDescription($description);

		// OpenID input form element !! ADD DUPE VALIDATOR !!
		$openID = new Zend_Form_Element_Text('openid');
		$openID->setLabel($translate->_("account-profile-openid"))
					->addValidators(array(
							new Oibs_Validators_OpenidExists(),
							array('NotEmpty', true, array('messages' => array('isEmpty' => 'Empty'))),
							))
					->setDecorators(array('CustomDecorator'));

        // lets not allow publicity for this one just yet
        // $openID_publicity = new Zend_Form_Element_Checkbox('openID_publicity');
        // $openID_publicity->setDecorators(array('SettingsCheckboxDecorator'))
        //           ->setDescription($description);

		// Birthday input form element
		$birth = new Zend_Form_Element_Text('birthday');
		$birth->setLabel($translate->_("account-profile-birth"))
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
		$bio->setLabel($translate->_("account-profile-biography"))
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

		//Country input form element
		$country =  new Zend_Form_Element_Select('country');
		$country->setLabel($translate->_('Country'))
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

		// E-mail input form element
		$email = new Zend_Form_Element_Text('email');
		$email->setLabel($translate->_("account-register-email"))
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
				->setDecorators(array('SettingsTextDecorator'));
				// ->removeDecorator('errors');
		$gravatar = new Zend_Form_Element_Checkbox('gravatar');
		$gravatar->setDecorators(array('SettingsCheckboxDecorator'))
                ->setDescription("Enable <a href=\"http://www.gravatar.com\">gravatar</a>");
                
        $email_publicity = new Zend_Form_Element_Checkbox('email_publicity');
		$email_publicity->setDecorators(array('SettingsCheckboxDecorator'))
                ->setDescription($description);

		// E-mail confirm input form element
		$confirm_email = new Zend_Form_Element_Text('confirm_email');
		$confirm_email->setLabel($translate->_("account-register-email_confirmation"))
				//->setRequired(true)
				->addFilter('StringtoLower')
				->addValidators(array(
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


		// Password input form element
		$password = new Zend_Form_Element_Password('password');
		$password->setLabel($translate->_("account-register-password"))
				//->setRequired(true)
				->addValidators(array(
				new Oibs_Validators_RepeatValidator('confirm_password'),
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Empty'))),
				array('StringLength', false, array(4, 22, 'messages' => array('stringLengthTooShort' => 'PASSWORD TOO SHORT'))),
				))
				->setDecorators(array('CustomDecorator'));

		// Password confirm input form element
		$password_confirm = new Zend_Form_Element_Password('confirm_password');
		$password_confirm->setLabel($translate->_("account-register-password_confirm"))
				//->setRequired(true)
				->addValidators(array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Empty'))),
				array('StringLength', false, array(4, 22, 'messages' => array('stringLengthTooShort' => 'PASSWORD TOO SHORT'))),
				))
				->setDecorators(array('CustomDecorator'));

		// E-mail confirm input form element
		$current_password = new Zend_Form_Element_Password('current_password');
		$current_password->setLabel($translate->_("account-register-current_password"))
				->setRequired(true)
				->addValidators(array(
				new Oibs_Validators_CurrentPasswordValidator(),
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'Empty'))),
				))
				->setDecorators(array('CustomDecorator'));

		// Email notifications checkboxes
		$notificationsModel = new Default_Model_Notifications();
        $notificationsList = $notificationsModel->getForSettingsForm();
		$notifications = new Zend_Form_Element_MultiCheckbox('notifications');
		$notifications->setMultiOptions($notificationsList)
					     ->setDecorators(array('SettingsNotificationsDecorator'))
						 ->setLabel($translate->_("account-register-emailnotifications"));

		// Form submit buttom form element
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel($translate->_("account-register-submit"));

		// Add elements to form
		$this->setAttrib('enctype', 'multipart/form-data');
		$this->addElements(array(   $firstname, $firstname_publicity,
                                    $surname, $surname_publicity,
                                    $gender, $gender_publicity,
                                    $profession, $profession_publicity,
                                    $com, $com_publicity,
                                    $country, $country_publicity,
                                    $city, $city_publicity,
                                    $birth, $birth_publicity,
                                    $bio, $bio_publicity,
                                    $phone, $phone_publicity,
                                    // $openID, $openid_publicity,
                                    $openID, //$openid_publicity,
                                    $password, $password_confirm,
                                    $email, $gravatar,
                                    $confirm_email,
                                    $current_password,
                                    $notifications,
                                    $submit));



        // if you use try..catch Don't echo e!!
        }catch(Zend_Exception $e){echo '<pre>General error occurred! Please try again.';echo '</pre>';}
    }*/

    /*  What is this? Why is this here? Do we need it? Filename?? -Joel
	public function formRename($filename)
    {
            $path = $_SERVER['REQUEST_URI'];
            $id = basename($path);
            $ext = substr($filename, strrpos($filename, '.') + 1);
            $newName = $id.'_photo.'.$ext;
            return $newName;
    }
    */

}