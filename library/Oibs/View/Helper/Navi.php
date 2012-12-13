<?php
/**
 * User: jauer
 * Date: 13.12.12
 * Description
 */

class Oibs_View_Helper_Navi extends Oibs_View_Helper_Viewable
{
	/** @var string */
	private $_active_section = 'main-nav-section';
	/** @var array */
	private $_groups = array();
	/** @var string */
	private $_active_group = 'main-nav-mygroups';
	/** @var array */
	private $_categories = array();
	/** @var string */
	private $_active_category = 'main-nav-categories';

	/**
	 * Renders the navigation.
	 *
	 * @param string $script The view script path to render (relative to /views/helpers)
	 * @return string
	 */
	public function navi($script)
	{
		return $this->renderView($script, array(
			'active_area'     => Zend_Controller_Front::getInstance()->getRequest()->getParam('controller'),
			'active_section'  => $this->getActiveSection(),
			'groups'          => $this->getGroups(),
			'active_group'    => $this->getActiveGroup(),
			'categories'      => $this->getCategories(),
			'active_category' => $this->getActiveGroup(),
		));
	}

	/**
	 * @return string
	 */
	public function getActiveSection()
	{
		return $this->_active_section;
	}

	/**
	 * @param $active_section
	 * @return Oibs_View_Helper_Navi
	 */
	public function setActiveSection($active_section)
	{
		$this->_active_section = $active_section;
		return $this;
	}

	/**
	 * Returns a list of group objects.
	 *
	 * @return array
	 */
	public function getGroups()
	{
		return $this->_groups;
	}

	/**
	 * Defines a list of groups which are displayed in the navigation.
	 *
	 * @param $groups
	 * @return Oibs_View_Helper_Navi
	 */
	public function setGroups($groups)
	{
		$this->_groups = $groups;
		return $this;
	}

	/**
	 * Returns a list of category models.
	 *
	 * @return array
	 */
	public function getCategories()
	{
		return $this->_categories;
	}

	/**
	 * Defines a list of categories which are displayed in the navigation.
	 *
	 * @param $categories
	 * @return Oibs_View_Helper_Navi
	 */
	public function setCategories($categories)
	{
		$this->_categories = $categories;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getActiveGroup()
	{
		return $this->_active_group;
	}

	/**
	 * @param $active_group
	 * @return Oibs_View_Helper_Navi
	 */
	public function setActiveGroup($active_group)
	{
		$this->_active_group = $active_group;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getActiveCategory()
	{
		return $this->_active_category;
	}

	/**
	 * @param $active_category
	 * @return Oibs_View_Helper_Navi
	 */
	public function setActiveCategory($active_category)
	{
		$this->_active_category = $active_category;
		return $this;
	}

}
