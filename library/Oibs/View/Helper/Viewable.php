<?php
/**
 * User: jauer
 * Date: 13.12.12
 * Description
 */

abstract class Oibs_View_Helper_Viewable extends Zend_View_Helper_Abstract
{
	/** @var \Zend_View_Interface */
	private $_helperView;
	/** @var string */
	private $_language = null;

	/**
	 * Initialize View object
	 *
	 * Initializes {@link $view} if not otherwise a Zend_View_Interface.
	 *
	 * If {@link $view} is not otherwise set, instantiates a new Zend_View
	 * object, using the 'views' subdirectory at the same level as the
	 * controller directory for the current module as the base directory.
	 * It uses this to set the following:
	 * - script path = views/scripts/
	 * - helper path = views/helpers/
	 * - filter path = views/filters/
	 *
	 * @return Zend_View_Interface
	 * @throws Exception if base view directory does not exist
	 */
	private function initView()
	{
		$frontController = Zend_Controller_Front::getInstance();
		$dirs    = $frontController->getControllerDirectory();
		$module  = $frontController->getDispatcher()->getDefaultModule();
		$baseDir = dirname($dirs[$module]) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'helpers';
		if (!file_exists($baseDir) || !is_dir($baseDir)) {
			throw new Exception('Missing base view directory ("' . $baseDir . '")');
		}

		$this->_helperView = new Zend_View(array('scriptPath' => $baseDir));
		return $this->_helperView;
	}

	/**
	 * @return \Zend_View_Interface
	 */
	protected function getHelperView()
	{
		if ($this->_helperView === null) {
			$this->initView();
		}
		return $this->_helperView;
	}

	/**
	 * @param string $script  The view script path to render (relative to /views/helpers)
	 * @param array  $options Optional options, which are passed to the view.
	 * @return string
	 */
	protected function renderView($script, array $options = array()) {
		$view = $this->getHelperView();
		$view->assign('language', $this->getLanguage());

		foreach ($options as $key => $value) {
			$view->assign($key, $value);
		}

		return $this->getHelperView()->render($script);
	}

	/**
	 * @return string
	 */
	public function getLanguage()
	{
		return $this->_language;
	}

	/**
	 * @param string $language
	 * @return Oibs_View_Helper_Navi
	 */
	public function setLanguage($language)
	{
		$this->_language = $language;
		return $this;
	}

}
