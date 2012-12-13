<?php
/**
 * User: jauer
 * Date: 11.12.12
 * Description
 */

class Oibs_View_Helper_Sidebar extends Zend_View_Helper_Abstract
{
	/** @var \Zend_View_Interface */
	protected $_sidebarView;
    /** @var Array */
    protected $_searchUserResults;
    /** @var Array */
    protected $_searchGroupResults;
    /** @var Array */
    protected $_searchContentResults;

	/**
	 * Renders the sidebar.
	 *
	 * @param string $script The view script path to render (relative to /views/helpers)
	 * @return string
	 */
	public function sidebar($script)
	{
		return $this->getSidebarView()->render($script);
	}

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
	protected function initView()
	{
		$frontController = Zend_Controller_Front::getInstance();
		$dirs    = $frontController->getControllerDirectory();
		$module  = $frontController->getDispatcher()->getDefaultModule();
		$baseDir = dirname($dirs[$module]) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'helpers';
		if (!file_exists($baseDir) || !is_dir($baseDir)) {
			throw new Exception('Missing base view directory ("' . $baseDir . '")');
		}

		$this->_sidebarView = new Zend_View(array('scriptPath' => $baseDir));
		return $this->_sidebarView;
	}

	/**
	 * @return \Zend_View_Interface
	 */
	public function getSidebarView()
	{
		if ($this->_sidebarView === null) {
			$this->initView();
		}
		return $this->_sidebarView;
	}

    /**
     * @return Array
     */
    public function getSearchUserResults()
    {
        return $this->_searchUserResults;
    }

    /**
     * @param $searchUserResults
     * @return Oibs_View_Helper_Sidebar
     */
    public function setSearchUserResults($searchUserResults)
    {
        $this->_searchUserResults = $searchUserResults;
        return $this;
    }

    /**
     * @return Array
     */
    public function getSearchGroupResults()
    {
        return $this->_searchGroupResults;
    }

    /**
     * @param Array $searchGroupResults
     */
    public function setSearchGroupResults($searchGroupResults)
    {
        $this->_searchGroupResults = $searchGroupResults;
        return $this;
    }

    /**
     * @return Array
     */
    public function getSearchContentResults()
    {
        return $this->_searchContentResults;
    }

    /**
     * @param Array $searchContentResults
     */
    public function setSearchContentResults($searchContentResults)
    {
        $this->_searchContentResults = $searchContentResults;
        return $this;
    }


}
