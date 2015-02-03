<?php

/**
 * RedirectException class provide error message.
 * 
 * @author  Naghashyan Solution, e-mail: info@naghashyan.com
 * @version 1.0
 * @package framework.exceptions
 */
class RedirectException extends Exception {

    private $redirectTo;

    /**
     * Return a thing based on $redirectTo, $message parameters
     * @abstract  
     * @access
     * @param $redirectTo, $message
     * @return 
     */
    public function __construct($redirectTo, $message) {
        $this->redirectTo = $redirectTo;
        parent::__construct($message, 1);
    }

    public function getRedirectTo() {
        return $this->redirectTo;
    }

}

?>