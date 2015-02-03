<?php

require_once (CLASSES_PATH . "/actions/user/BaseUserAction.class.php");

/**
 * @author Levon Naghashyan
 */
class LogoutAction extends BaseUserAction {

    public function service() {
        $user = $this->getUser();
        $this->sessionManager->removeUser($user, true);
        $this->redirect();
        exit;
    }
}

?>