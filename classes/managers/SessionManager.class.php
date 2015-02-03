<?php

require_once (CLASSES_PATH . "/framework/AbstractSessionManager.class.php");
require_once (CLASSES_PATH . "/security/RequestGroups.class.php");
require_once (CLASSES_PATH . "/framework/exceptions/RedirectException.class.php");

require_once (CLASSES_PATH . "/security/UserGroups.class.php");
require_once (CLASSES_PATH . "/security/users/GuestUser.class.php");
require_once (CLASSES_PATH . "/security/users/AuthenticateUser.class.php");
require_once (CLASSES_PATH . "/security/users/AdminUser.class.php");
require_once (CLASSES_PATH . "/security/users/CustomerUser.class.php");

/**
 *
 * @author  Naghashyan Solutions, e-mail: info@naghashyan.com
 * @version 1.0
 * @package framework
 *
 */
class SessionManager extends AbstractSessionManager {

    private $user = null;
    private $config;

    public function __construct($config) {
        session_set_cookie_params(3600000);
        session_start();
        $this->config = $config;
    }

    public function getUser() {
        if ($this->user != null) {
            return $this->user;
        }
        // for test
        $this->user = new GuestUser();
        try {
            if (isset($_COOKIE["ut"])) {
                if (isset($_COOKIE["uh"]) && isset($_COOKIE["ud"])) {
                    if ($_COOKIE["ut"] == UserGroups::$USER) {
                        $user = new CustomerUser($_COOKIE["ud"]);
                    } else if ($_COOKIE["ut"] == UserGroups::$ADMIN) {
                        $user = new AdminUser($_COOKIE["ud"]);
                    }
                }
            }
            if (isset($user) && $user->validate($_COOKIE["uh"])) {
                $this->user = $user;
            }
            if ($this->user && $this->user->getLevel() != UserGroups::$GUEST) {
                $hash = $_COOKIE["uh"];
                $this->user->setUniqueId($hash, false);
            }
        } catch (InvalidUserException $ex) {
            
        }
        return $this->user;
    }

    /**
     * Return a thing based on $request, $user parameters
     * @abstract
     * @access
     * @param $request, $user
     * @return true
     */
    public function validateRequest($request, $user) {
        if ($request->getRequestGroup() == RequestGroups::$guestRequest) {
            return true;
        }
        if ($request->getRequestGroup() == RequestGroups::$adminRequest && $user->getLevel() == UserGroups::$ADMIN) {
            return true;
        }
        if ($request->getRequestGroup() == RequestGroups::$userRequest && $user->getLevel() == UserGroups::$USER) {
            return true;
        }

        return false;
    }

}

?>