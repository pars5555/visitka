<?php

require_once(CLASSES_PATH . "/framework/AbstractLoad.class.php");

/**
 * <p><b>AbstractRequest class</b> is a base class for all action classes.
 * The child of this class is <b>AbstractAction.class.php,AbstractLoad.class.php</b> files. </p>
 * 
 * @author  Naghashyan Solutions, e-mail: info@naghashyan.com
 * @version 1.0
 * @package framework
 */
abstract class AbstractTemplator {
	protected $config;
	protected $loadMapper;
    protected $load;
	
	public function __construct(AbstractLoad $load, AbstractLoadMapper $loadMapper, $config) {
		$this->load = $load;
		$this->loadMapper = $loadMapper;
		$this->config = $config;
	}

	public abstract function displayResult();
    
}

?>