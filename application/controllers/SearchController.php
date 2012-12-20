<?php
/**
 *  SearchController
 *
 *  @package    controllers
 *  @license    GPL v2
 *  @version    1.0
 */

/**
 *  SearchController
 *
 *  @package    controllers
 *  @license    GPL v2
 *  @version    1.0
 */
class SearchController extends Oibs_Controller_CustomController
{

	/**
	 * @inheritdoc
	 */
	public function init()
    {
        parent::init();

        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('get-results', 'html')
            ->initContext();

        $this->view->title = 'search-title';
    }

    function indexAction()
    {
        $this->_redirect($this->getUrl(array('action' => 'result')));
	    // todo: show full search form
	}

	public function getResultsAction() {
		$params = $this->getRequest()->getParams();

		$pattern = isset($params['pattern']) ? $params['pattern'] : 0;

		$userModel = new Default_Model_User();
		$userResults = $userModel->getUserByFilter($pattern);

		$groupModel = new Default_Model_Groups();
		$groupResults = $groupModel->getGroupByFilter($pattern);

		$contentModel = new Default_Model_Content();
		$contentResults = $contentModel->getContentByFilter($pattern);

		$this->view->userResults = $userResults;
		$this->view->groupResults = $groupResults;
		$this->view->contentResults = $contentResults;
	}

	private function searchUserByFilter() {

        $params = $this->getRequest()->getParams();
        $pattern = isset($params['pattern']) ? $params['pattern'] : "";

        if($pattern != "") {
            $userModel = new Default_Model_User();
            $searchResults = $userModel->getUserByFilter($pattern);
            $this->getSidebarHelper()->setSearchUserResults($searchResults);
        }

    }

	private function searchContentByFilter() {

        $params = $this->getRequest()->getParams();
        $pattern = isset($params['pattern']) ? $params['pattern'] : "";

        if($pattern != "") {
            $contentModel = new Default_Model_Content();
            $searchResults = $contentModel->getContentByFilter($pattern);
            $this->getSidebarHelper()->setSearchContentResults($searchResults);
        }

    }

	private function searchGroupByFilter() {

        $params = $this->getRequest()->getParams();
        $pattern = isset($params['pattern']) ? $params['pattern'] : "";

        if($pattern != "") {
            $groupModel = new Default_Model_Groups();
            $searchResults = $groupModel->getGroupByFilter($pattern);
            $this->getSidebarHelper()->setSearchGroupResults($searchResults);
        }

    }

	/** Delivers 0-5 users which match best with the request parameters */
	private function matchingUsers() {
        $params = $this->getRequest()->getParams();
        $job = isset($params['job']) ? $params['job'] : "";
        $location = isset($params['location']) ? $params['location'] : "";
        $attribute = isset($params['attribute']) ? $params['attribute'] : "";

        $userModel = new Default_Model_User();
        $matchingUsers = $userModel->getMatchingUser($job, $location, $attribute);
        $this->getSidebarHelper()->setMatchingUsers($matchingUsers);

    }

}
