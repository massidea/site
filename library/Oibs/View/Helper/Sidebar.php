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

	/**
	 * Renders the sidebar.
	 *
	 * @param string $script The view script path to render (relative to /views/helpers)
	 * @return string
	 */
	public function sidebar($script)
	{
		return $this->renderView($script);
	}

}
