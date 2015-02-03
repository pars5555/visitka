<?php

require_once(CLASSES_PATH . "/actions/BaseAction.class.php");

/**
 * General parent action for all AdminAction classes.
 *
 */
abstract class BaseUserAction extends BaseAction {

    public function getRequestGroup() {
        return RequestGroups::$userRequest;
    }

    public function onNoAccess() {
        $this->redirect('login');
    }

}

?>