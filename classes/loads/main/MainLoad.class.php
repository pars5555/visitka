<?php

require_once (CLASSES_PATH . "/loads/main/BaseGuestLoad.class.php");

class MainLoad extends BaseGuestLoad{

    public function load() {
    }

     public function getDefaultLoads($args) {
        $loads = array();
        $page = 'home';
        if (isset($_REQUEST['page'])) {
            $page = $this->secure($_REQUEST['page']);
        }
        $pagePartsArray = explode('_', $page);
        function _ucfirst(&$item, $key) {
            $item = ucfirst($item);
        }
        array_walk($pagePartsArray, '_ucfirst');
        $loadName = implode('', $pagePartsArray) . "Load";
        $this->addParam('contentLoad', $page);
        $loads["content"]["load"] = "loads/main/" . $loadName;
        $loads["content"]["args"] = array("mainLoad" => &$this);
        $loads["content"]["loads"] = array();
        return $loads;
    }

    public function getTemplate() {
        return TEMPLATES_DIR . "/main/main.tpl";
    }

    public function isMain() {
        return true;
    }

}

?>
