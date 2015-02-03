<?php

require_once(CLASSES_PATH . "/actions/BaseAction.class.php");

/**
 * General parent action for all AdminAction classes.
 *
 */
abstract class BaseAdminAction extends BaseAction {

    public function getRequestGroup() {
        return RequestGroups::$adminRequest;
    }
 public function onNoAccess() {
        $this->redirect('admin/login');
    }
}

?>