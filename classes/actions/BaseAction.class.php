<?php

require_once (CLASSES_PATH . "/framework/AbstractAction.class.php");
require_once (CLASSES_PATH . "/exceptions/NgsException.class.php");

/**
 * @author Vahagn Sookiasian
 */
abstract class BaseAction extends AbstractAction {

    public function getUser() {
        return $this->sessionManager->getUser();
    }

    public function onNoAccess() {
        $this->redirect('/login');
    }
    
    public function getRequestGroup() {
        return RequestGroups::$guestRequest;
    }

}

?>