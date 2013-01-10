<?php
/**
 * User: jauer
 * Date: 11.12.12
 * Description
 */

class Oibs_View_Helper_Sidebar extends Oibs_View_Helper_Viewable
{
    /** @var Array */
    protected $_searchUserResults;
    /** @var Array */
    protected $_searchGroupResults;
    /** @var Array */
    protected $_searchContentResults;
    /** @var Array */
    protected $_matchingUsers;

	/**
	 * Renders the sidebar.
	 *
	 * @param string $script The view script path to render (relative to /views/helpers)
	 * @return string
	 */
	public function sidebar($script)
	{
		return $this->renderView($script, array(
			'matchingUsers'  => $this->getMatchingUsers()
		));
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
	 * @param array $searchGroupResults
	 * @return Oibs_View_Helper_Sidebar
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
	 * @param array $searchContentResults
	 * @return Oibs_View_Helper_Sidebar
	 */
	public function setSearchContentResults($searchContentResults)
	{
		$this->_searchContentResults = $searchContentResults;
		return $this;
	}

    /**
     * @return Array
     */
    public function getMatchingUsers()
    {
        return $this->_matchingUsers;
    }

    /**
     * @param Array $matchingUsers
     */
    public function setMatchingUsers($matchingUsers)
    {
        $this->_matchingUsers = $matchingUsers;
    }

}
