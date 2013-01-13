<?php

/**
 *  ContentCreateController - class
 *
 *  @package        controllers
 *  @license        GPL v2
 *  @version        2.0
 */

class ContentCreateController extends Oibs_Controller_CustomController
{
	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();
        $auth = Zend_Auth::getInstance();
        $logged_in = $auth->hasIdentity();

        $this->view->logged_in = $logged_in;
	}
	
	/**
     * createAction
     *
     * Show the content creation page
     */
    public function createAction()
    {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $usrId = $auth->getIdentity()->user_id;
            $grpId = $this->_request->getParam('grpid');

            if (!$grpId) {
                $redirectUrl = $this->_urlHelper->url(
                    array(
                        'controller' => 'campaign',
                        'action' => 'list',
                        'language' => $this->view->language),
                    'lang_default', true);
                $this->_redirect($redirectUrl);
            }

            $this->view->grpid = $grpId;

            // Add the "create content"-form to the view.
            $form = new Default_Form_ContentCreateForm();
            $this->view->form = $form;

            // Handle posted form.
            $request = $this->getRequest();
            if ($request->isPost()) {
                $post = $request->getPost();
                if ($form->isValid($post)) {
                    $campaignModel = new Default_Model_Content();

                    $title = $post['content_title'];
                    $desc = $post['content_desc'];

					$filesModel = new Default_Model_Files();
					$files = $_FILES['content_file_upload'];

                    $target = $this->_urlHelper->url(
                        array(
                            'groupid'    => $grpId,
                            'language'   => $this->view->language),
                        'group_shortview', true);
                    $this->_redirect($target);
                }
            }
        } else {
            $redirectUrl = $this->_urlHelper->url(array('controller' => 'campaign',
                                                        'action' => 'list',
                                                        'language' => $this->view->language),
                                                  'lang_default', true);
            $this->_redirect($redirectUrl);
        }
    }
}