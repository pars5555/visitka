<?php

require_once(CLASSES_PATH . "/security/UserGroups.class.php");
require_once(CLASSES_PATH . "/security/users/AuthenticateUser.class.php");
require_once (CLASSES_PATH . "/managers/UsersManager.class.php");

/**
 * User class for free customers.
 *
 * @author Vahagn Sookiasian
 */
class CustomerUser extends AuthenticateUser {

    /**
     * Creates en instance of free customer user class and
     * initializes class members necessary for validation.
     *
     * @param object $adminId
     * @return
     */
    public function __construct($id) {
        parent::__construct($id);
        $this->setCookieParam("ut", UserGroups::$USER);
    }

    public function setUniqueId($uniqueId, $updateDb = true) {
        if ($updateDb) {
            $uniqueId = UsersManager::getInstance()->updateUserHash($this->getId());
        }
        $this->setCookieParam("uh", $uniqueId);
    }

    /**
     * Validates user credentials
     *
     * @return TRUE - if validation passed, and FALSE - otherwise
     */
    public function validate($uniqueId = false) {
        if (empty($uniqueId)) {
            $uniqueId = $this->getUniqueId();
        }
        $validatedUser = UsersManager::getInstance()->validate($this->getId(), $uniqueId);
        if (!isset($validatedUser)) {
            return false;
        }
        return true;
    }

}

?>