<?php

require_once (CLASSES_PATH . "/loads/main/BaseGuestLoad.class.php");

/**
 *
 * @author Vahagn Sookiasian
 *
 */
class MainLoad extends BaseGuestLoad {

    public function load() {
        
    }

    public function getDefaultLoads($args) {
        if (!isset($_REQUEST['page']) || empty($_REQUEST['page']))
        {
            $_REQUEST['page'] = 'home';
        }
        $contentLoad = $_REQUEST['page'];
        $contentLoadClass = ucfirst($_REQUEST['page']) . 'Load';
		

        $this->addParam('contentLoad', 'admin_'.$contentLoad);
        $loads["content"]["load"] = "loads/admin/" . $contentLoadClass;
        $loads["content"]["args"] = array();
        $loads["content"]["loads"] = array();
        return $loads;
    }

    public function getTemplate() {
        return TEMPLATES_DIR . "/admin/main.tpl";
    }

}

?>