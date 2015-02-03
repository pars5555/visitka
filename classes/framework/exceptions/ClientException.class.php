<?php

/**
 * ClientException class provides error message.
 * 
 * @author  Naghashyan Solution, e-mail: info@naghashyan.com
 * @version 1.0
 * @package framework.exceptions
 */
class ClientException extends Exception {

    private $errorParams;

    /**
     * @abstract  
     * @access
     * @param 
     * @return 
     */
    public function __construct() {
        $errorParams = array();
        $argv = func_get_args();
        switch (func_num_args()) {
            default:
            case 1:
                self::__construct1($argv[0]);
                break;
            case 3:
                self::__construct2($argv[0], $argv[1], $argv[2]);
                break;
        }
    }

    /**
     * Return a thing based on $message parameter
     * @abstract  
     * @access
     * @param boolean $message 
     * @return
     */
    public function __construct1($message) {
        parent::__construct($message, 1);
        $autoCounter = -1;
        $this->addErrorParam($autoCounter, $autoCounter, $message);
    }

    /**
     * Return a thing based on $id, $code, $message parameters
     * @abstract  
     * @access
     * @param $id, $code, $message
     * @return
     */
    public function __construct2($id, $code, $message) {
        parent::__construct($message, $code);
        $this->addErrorParam($id, $code, $message);
    }

    /**
     * Return a thing based on $id, $code, $message parameters
     * @abstract  
     * @access
     * @param $id, $code, $message
     * @return
     */
    public function addErrorParam($id, $code, $message) {
        $this->errorParams[$id] = array(code => $code, message => $message);
    }

    /**
     * @abstract  
     * @access
     * @param
     * @return errorParams
     */
    public function getErrorParams() {
        return $this->errorParams;
    }

}

?>