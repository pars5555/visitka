<?php

require_once(CLASSES_PATH . "/security/UserGroups.class.php");
require_once(CLASSES_PATH . "/framework/AbstractUser.class.php");

/**
 * User object for non authorized users.
 * 
 * @author  Naghashyan Solutions, e-mail: info@naghashyan.com
 * @version 1.0
 * @package security.users
 */
class GuestUser extends AbstractUser {

    /**
     * Constructs GUEST user object 
     */
    public function __construct() {
        $this->setCookieParam("ut", UserGroups::$GUEST);
    }

    /**
     * Returns user's level
     * @abstract  
     * @access
     * @param 
     * @return int 
     */
    public function getLevel() {
        return $this->getCookieParam("ut");
    }

    /**
     * There is no validation needed for GUEST users
     * 
     * @abstract  
     * @access
     * @param 
     * @return true 
     */
    public function validate() {
        return true;
    }
    
     public function getId() {
        return null;
    }

}

?>