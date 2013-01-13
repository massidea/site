<?php

/**
 * ContentCreateForm - class
 *
 * @package     Forms
 * @license     GPL v2
 * @version     1.0
 */
class Default_Form_ContentCreateForm extends Twitter_Bootstrap_Form_Horizontal
{

	/**
	 * @inheritdoc
	 */
	public function init()
	{
        $translate = Zend_Registry::get('Zend_Translate');
        $language = $translate->getLocale();
        $baseurl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $actionUrl = $baseurl.'/'.$language.'/content/create';

		$this->setName('content_form')
            ->setAction($actionUrl)
			->setAttrib('id', 'content_form')
			->addElementPrefixPath('Oibs_Validators', 'OIBS/Validators/', 'validate', 'decorate');

		$this->addElement('text', 'content_title', array(
			'label'      => 'content-title',
			'required'   => true,
			'validators' => array(
				array('NotEmpty', true, array('messages' => array('isEmpty' => 'error-field-empty'))),
			),
		));

		$this->addElement('text', 'content_desc', array(
			'label'      => 'content-desc',
			'required'   => true,
			'validators' => array(
                array('NotEmpty', true, array('messages' => array('isEmpty' => 'error-field-empty'))),
             ),
		));
		));

		$this->addElement('captcha', 'register_captcha', array(
			'captcha'    => array(
				'captcha' => 'Image',
				'wordLen' => 8,
				'timeout' => 300,
				'font'    => APPLICATION_PATH . '/../library/Fonts/Verdana.ttf',
				'imgDir'  => APPLICATION_PATH . '/../www/img/captcha',
				'imgUrl'  => '/img/captcha',
                'Messages'    => array(
                    'badCaptcha' => 'error-captcha-no-same',
                 )
			),
			'required'   => true,
			'label'      => 'account-register-captcha',
		));

		$this->addElement('submit', 'content_submit', array(
			'label'      => 'content-submit',
			'required'   => true,
			'validators' => array(),
		));

		parent::init();
	}
}