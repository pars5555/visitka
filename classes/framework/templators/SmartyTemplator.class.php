<?php

require_once (CLASSES_PATH . "/framework/AbstractLoad.class.php");
require_once (CLASSES_PATH . "/framework/templators/NgsSmarty.class.php");
require_once (CLASSES_PATH . "/framework/templators/AbstractTemplator.class.php");

/**
 * <p><b>AbstractRequest class</b> is a base class for all action classes.
 * The child of this class is <b>AbstractAction.class.php,AbstractLoad.class.php</b> files. </p>
 *
 * @author  Naghashyan Solutions, e-mail: info@naghashyan.com
 * @version 1.0
 * @package framework
 */
class SmartyTemplator extends AbstractTemplator{

	protected $smarty;

	public function __construct(AbstractLoad $load, AbstractLoadMapper $loadMapper) {
		parent::__construct($load, $loadMapper);
		$this -> smarty = new NgsSmarty(VERSION);
	}

	public function displayResult() {
		$params = $this -> load -> getParams();
		$templateName = $this -> load -> getTemplate();
		if ($templateName != null) {
			$this -> smarty -> assign("ns", $params);
			$this -> smarty -> assign("pm", $this -> loadMapper);
			$this -> smarty -> display($templateName);
		}
	}

}
?>