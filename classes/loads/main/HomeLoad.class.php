<?php

require_once (CLASSES_PATH . "/loads/main/BaseGuestLoad.class.php");

/**
 *
 * @author Vahagn Sookiasian
 *
 */
class HomeLoad extends BaseGuestLoad{
    
    public function load() {
        $this->addParam('startHour', 9);
        $this->addParam('endHour', 18);
    }

    public function getTemplate() {
        return TEMPLATES_DIR . "/main/home.tpl";
    }

}

?>