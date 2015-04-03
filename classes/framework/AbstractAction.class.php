<?php

require_once(CLASSES_PATH . "/framework/Dispatcher.class.php");
require_once(CLASSES_PATH . "/framework/AbstractRequest.class.php");
require_once(CLASSES_PATH . "/framework/AbstractSessionManager.class.php");

/**
 * <p><b>AbstractAction class</b> is a base class for all action classes, which extends from <b>AbstractRequest</b>. </p>
 * 
 * @author  Naghashyan Solutions, e-mail: info@naghashyan.com
 * @version 1.0
 * @package framework
 */
abstract class AbstractAction extends AbstractRequest {

    /**
     * Return a thing based on $sessionManager, $loadMapper, $args parameters
     * @abstract  
     * @access
     * @param $sessionManager,  $loadMapper, $args
     * @return
     */
    public function initialize($sessionManager, $loadMapper, $args) {
        parent::initialize($sessionManager, $loadMapper, $args);
    }

    /**
     * @abstract  
     * @access
     * @param  
     * @return object
     */
    public function load() {
        $this->service();
    }

    /**
     * <p>The files that are extended from AbstractAction should have <b>service()</b> function.  </p> 
     * <p>In this function developer should write the specefic code for the project(see NgsExample.Action.php.class files)</p>
     *
     * @abstract  
     * @access
     * @param  
     * @return object
     */
    public abstract function service();
}

?>