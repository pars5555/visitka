<?php

require_once (CLASSES_PATH . "/loads/admin/BaseAdminLoad.class.php");

/**
 *
 * @author Vahagn Sookiasian
 *
 */
class HomeLoad extends BaseAdminLoad {

    public function load() {        
    }

    public function getTemplate() {
        return TEMPLATES_DIR . "/admin/home.tpl";
    }

}

?>