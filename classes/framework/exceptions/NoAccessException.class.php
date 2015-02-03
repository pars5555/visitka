<?php

/**
 * NoAccessException class provide error message.
 * 
 * @author  Naghashyan Solution, e-mail: info@naghashyan.com
 * @version 1.0
 * @package framework.exceptions
 */
class NoAccessException extends Exception {

    public static $NOT_FOUND = 0;
    public static $NO_ACCESS = 1;
    public static $INVALID_DOMAIN = 2;

    /**
     * Return a thing based on $message, $code parameters
     * @abstract  
     * @access
     * @param boolean $message, $code parameter 
     * @return
     */
    public function __construct($message, $code = 0) {
        parent::__construct($message, $code);
    }

}

?>