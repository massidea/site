<?php
/**
 *  FileController -> For handling files uploaded to database
 *
 *  Copyright (c) <2009>, Pekka Piispanen <pekka.piispanen@cs.tamk.fi>
 *
 *  This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License
 *  as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 *  warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
 *  more details.
 *
 *  You should have received a copy of the GNU General Public License along with this program; if not, write to the Free
 *  Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 *  License text found in /license/
 */

/**
 *  FileController - class
 *
 *  @package        controllers
 *  @author         Pekka Piispanen
 *  @copyright      2009 Pekka Piispanen
 *  @license        GPL v2
 *  @version        1.0
 */
class FileController extends Oibs_Controller_CustomController
{
	public function init()
	{
        parent::init();

    }

    public function viewAction()
    {
        // Set an empty layout for view
        $this->_helper->layout()->setLayout('empty');

        // Get requests
        $params = $this->getRequest()->getParams();

        $id_fil = (int)$params['id_fil'];

        if($id_fil != 0) {
            $files = new Default_Model_Files();

            if($files->fileExists($id_fil)) {
                $fileData = $files->getFileData($id_fil);
                $file = $files->getFile($id_fil);
                $this->view->filename = $file[0]['filename_fil'];
                $this->view->file = $fileData;
                $this->view->filetype = $file[0]['filetype_fil'];
            }
            else {
                $message = 'file-invalid-id';
                $url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index',
                                                'language' => $this->view->language),
                                          'lang_default', true);
                $this->addFlashMessage($message, $url);
            }
        }
        else {
            $message = 'file-missing-id';
            $url = $this->_urlHelper->url(array('controller' => 'msg',
                                                'action' => 'index',
                                                'language' => $this->view->language),
                                          'lang_default', true);
            $this->addFlashMessage($message, $url);
        }
    }

    public function convertlinksAction() {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
    	$fliModel = new Default_Model_FileLinks();
    	if (!$fliModel->convertDone()) {
			$fliModel->convert();
			echo "converting..";
    	} else {
    		echo "convert already done";
    	}
    }
}
