<?php
/**
 * Front Controller Plugin
 *
 * @uses       Zend_Controller_Plugin_Abstract
 * @package    Controller
 * @subpackage Plugins
 */
class Oibs_Controller_Plugin_Language extends Zend_Controller_Plugin_Abstract
{

	/**
	 * @inheritdoc
	 */
	public function routeShutdown(Zend_Controller_Request_Abstract $request)
	{
		/** @var $language string */
		$language = $this->getRequest()->getParam('language', '_default');
		/** @var $translate \Zend_Translate_Adapter */
		$translate = null;
		/** @var $config Zend_Config */
		$config = Zend_Registry::get('config');
		/** @var $session Zend_Session_Namespace */
		$session = new \Zend_Session_Namespace('Default');
		/** @var $locale Zend_Locale */
		$locale = new Zend_Locale();

		// create a translator and load all languages

		if (Zend_Registry::isRegistered('Zend_Translate')) {
			$translate = Zend_Registry::get('Zend_Translate');
		} else {
			$languages = $config->language->files->toArray();
			$path      = $config->language->path;
			$translate = $this->createTranslator($languages, $path);
		}

		// set the language with browser language fallback

		if ($language === '_default' || !$translate->isAvailable($language)) {
			$language = isset($session->language) ? $session->language : $locale->getLanguage();
		}

		// apply the language

		$session->language = $language;
		$locale->setLocale($language);
		$translate->setLocale($locale);
		Zend_Form::setDefaultTranslator($translate);

		// save instances to the zend registry

		Zend_Registry::set('Zend_Locale', $locale);
		Zend_Registry::set('Zend_Translate', $translate);
		Zend_Registry::set('Available_Translations', $translate->getList());
	}

	/**
	 * Creates a new Zend_Translate instance based on the configuration
	 *
	 * @param array  $languages
	 * @param string $path
	 * @return Zend_Translate
	 */
	private function createTranslator(array $languages, $path)
	{
		$translate = new Zend_Translate('csv', $path . '/index_en.csv', 'en');

		foreach ($languages as $lang => $files) {
			foreach ($files as $file) {
				$translate->addTranslation($path . '/' . $file, $lang);
			}
		}

		return $translate;
	}

}

?>
