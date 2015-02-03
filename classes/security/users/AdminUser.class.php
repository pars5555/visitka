<?php

require_once(CLASSES_PATH . "/security/UserGroups.class.php");
require_once(CLASSES_PATH . "/security/users/AuthenticateUser.class.php");
require_once(CLASSES_PATH . "/managers/AdminsManager.class.php");

/**
 * User class for system administrators.
 * 
 * @author  Naghashyan Solution, e-mail: info@naghashyan.com
 * @version 1.0
 * @package security.users
 */
class AdminUser extends AuthenticateUser {

    /**
     * Creates en instance of admin user class and
     * initializes class members necessary for validation. 
     * 
     * @param object $adminId
     * @return 
     */
    public function __construct($id) {
        parent::__construct($id);
        $this->setCookieParam("ut", UserGroups::$ADMIN);
    }

    public function setUniqueId($uniqueId, $updateDb = true) {
        if ($updateDb) {
            $uniqueId = AdminsManager::getInstance()->updateAdminHash($this->getId());
        }
        $this->setCookieParam("uh", $uniqueId);
    }

    /**
     * Validates user credentials 
     * 
     * @return TRUE - if validation passed, and FALSE - otherwise
     */
    public function validate($uniqueId = false) {
        if (!$uniqueId) {
            $uniqueId = $this->getUniqueId();
        }
        return AdminsManager::getInstance()->validate($this->getId(), $uniqueId);
    }

}

?>