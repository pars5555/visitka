<?php

require_once (CLASSES_PATH . "/loads/main/BaseGuestLoad.class.php");

/**
 *
 * @author Vahagn Sookiasian
 *
 */
class LoginLoad extends BaseGuestLoad {

    public function load() {   
         $this->initErrorMessages();
    }

    public function getTemplate() {
        return TEMPLATES_DIR . "/admin/login.tpl";
    }

}

?>