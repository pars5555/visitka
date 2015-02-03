<?php

/**
 * <p><b>AbstractSessionManager class</b> is a base class for <b>SessionManager.class.php </b> file.
 * The child of this class is <b>AbstractAction.class.php,AbstractLoad.class.php</b> files. </p>
 * 
 * @author  Naghashyan Solutions, e-mail: info@naghashyan.com
 * @version 1.0
 * @package framework
 */
abstract class AbstractSessionManager {

    protected $args;
    protected $dispatcher;

    /**
     * @abstract  
     * @access
     * @param 
     * @return 
     */
    public function __construct() {
        
    }

    /**
     * Return a thing based on $args parameter
     * @abstract  
     * @access
     * @param $args 
     * @return 
     */
    public function setArgs(&$args) {
        $this->args = $args;
    }

    /**
     * Return a thing based on $dispatcher parameter
     * @abstract  
     * @access
     * @param $dispatcher 
     * @return
     */
    public function setDispatcher($dispatcher) {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @abstract  
     * @access
     * @param  
     * @return 
     */
    public abstract function getUser();

    /**
     * Return a thing based on $user
     * @abstract  
     * @access
     * @param $request, $user
     * @return 
     */
    public abstract function validateRequest($request, $user);

    /**
     * Return a thing based on $user, $remember, $useDomain parameters
     * @abstract  
     * @access
     * @param $user, $remember, $useDomain
     * @return 
     */
    public function setUser($user, $remember = false) {
        $sessionTimeout = $remember ? 2078842581 : null;

        $domain = "." . DOMAIN;
        $cookieParams = $user->getCookieParams();
        foreach ($cookieParams as $key => $value) {
            setcookie($key, $value, $sessionTimeout, "/", $domain);
        }
        $sessionParams = $user->getSessionParams();
        foreach ($sessionParams as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }

    /**
     * Update user hash code
     * Return a thing based on $user parameters
     * @abstract  
     * @access
     * @param $user
     * @return 
     */
    public function updateUserUniqueId($user) {
        $domain = "." . DOMAIN;
        $cookieParams = $user->getCookieParams();
        setcookie("uh", $cookieParams["uh"], null, "/", $domain);
    }

    /**
     * Return a thing based on $user, $useDomain parameters
     * @abstract  
     * @access
     * @param $user, $useDomain
     * @return integer|babyclass
     */
    public function removeUser(AuthenticateUser $user) {
        $sessionTimeout = time() - 42000;
        $domain = "." . DOMAIN;
        $cookieParams = $user->getCookieParams();
        foreach ($cookieParams as $key => $value) {
            setcookie($key, '', $sessionTimeout, "/", $domain);
        }


        $this->deleteSession();
    }

    /**
     * Return a thing based on $parameter
     * @abstract  
     * @access
     * @param $parameter 
     * @return integer|babyclass
     */
    // supprime la session en cours ...
    private function deleteSession() {
        $_SESSION = array();

        if (isset($_COOKIE[session_name()])) {
            @setcookie(session_name(), '', time() - 42000, '/');
        }
        session_destroy();
    }

}

?>