<?php
/**
 * Plugin that cleans up querystrings in GET submissions
 */
class Oibs_Controller_Plugin_CleanQuery extends Zend_Controller_Plugin_Abstract {
    /**
     * @var boolean
     */
    protected $permanent;


    /**
     * Takes a flag in the constructor to determine whether the redirects
     * are permanent or just temporary (default);
     *
     * @param boolean $permanent
     */
    public function __construct($permanent=false) {
        $this->permanent=$permanent;
    }


    /**
     * Cleans the GET and if it's changed does a redirect
     */
    public function routeStartup(Zend_Controller_Request_Abstract $request) {
        if($this->getRequest()->isGet()) {
            $params = $this->getRequest()->getParams();
            if($params) {
                $new_params = $this->_filterArray($params);
                if(count($params, COUNT_RECURSIVE)
                        > count($new_params, COUNT_RECURSIVE)) {
//                    $uri = $this->getRequest()->getRequestUri();
                    $uri = $_SERVER['REQUEST_URI'];
                    $path = substr($uri, 0, strpos($uri, '?') + 1)
                            . http_build_query($new_params);
                    $this->getResponse()->setRedirect($path,
                            $this->permanent?301:302);
                    $this->getResponse()->sendResponse();
                    exit;
                }
            }
        }
    }

    /**
     * Cleans out false values from an array
     *
     * @param array $array
     */
    private function _filterArray($array) {
        foreach($array as $key=>$value) {
            if(is_array($value)) {
                $value = $this->_filterArray($value);
                $array[$key]=$value;
            }
            if($value==='') {
                unset($array[$key]);
            }
        }
        return $array;
    }
}