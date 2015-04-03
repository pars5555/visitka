<?php

require_once(CLASSES_PATH . "/framework/AbstractLoadMapper.class.php");

class LoadMapper extends AbstractLoadMapper {

    public $isStatic = false;
    private $PROTOCOL;
    private $SITE_URL;
    private $SITE_PATH;

    public function initialize() {
        $this->PROTOCOL = "http://";
        if (isset($_SERVER["HTTPS"])) {
            $this->PROTOCOL = "https://";
        }
       
        $this->SITE_URL = $_SERVER["HTTP_HOST"];
        $this->SITE_PATH = $this->PROTOCOL . $this->SITE_URL;      
    }

    public function notFoundHandler($exCode) {
        
    }

    public function getDynamicLoad($url, $matches) {
        
    }

    public function getCurrentLoads() {
        return array();
    }

    public function __get($nm) {
        return $this->__call($nm, array());
    }

    public function __call($nm, $arguments) {
        $url = null;
        return $url;
    }

    private function normalize($strComponent) {
        $pattern1 = '/\W+/i';
        $pattern2 = '/(-\w{0,2}-)|(^\w{0,2}-)|(-\w{0,2}$)/i';
        $pattern3 = '/(^-)|(-$)/i';
        $replacement = '-';
        $strComponent = preg_replace($pattern1, $replacement, $strComponent);
        $strComponent = preg_replace($pattern2, $replacement, $strComponent);
        $strComponent = preg_replace($pattern3, "", $strComponent);
        $strComponent = urlencode($strComponent);
        return $strComponent;
    }

}

?>