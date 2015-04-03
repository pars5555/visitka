<?php

require_once (CLASSES_PATH . "/framework/AbstractLoad.class.php");
require_once (CLASSES_PATH . "/framework/templators/AbstractTemplator.class.php");

/**
 * <p><b>AbstractRequest class</b> is a base class for all action classes.
 * The child of this class is <b>AbstractAction.class.php,AbstractLoad.class.php</b> files. </p>
 *
 * @author  Naghashyan Solutions, e-mail: info@naghashyan.com
 * @version 1.0
 * @package framework
 */
class JsonTemplator extends AbstractTemplator{

	protected $smarty;

	public function __construct(AbstractLoad $load, AbstractLoadMapper $loadMapper) {
		parent::__construct($load, $loadMapper);
	}

	public function displayResult() {
		$params = $this -> load -> getParams();
		$json = $this->createJSON($params);
		
		echo($json);
	}
	
	private function createJSON($arr) {
		unset($arr["_cl"]);
        $ret = "[{";
        if (is_array($arr)) {
            $delim = "";
            if (isset($arr["inc"])) {
                foreach ($arr["inc"] as $key => $value) {
                    $ret .= $delim;
                    $innerObj = $this->createJSON($value);
                    $ret .= $key . ":" . $innerObj;
                    $delim = ",";
                }
                unset($arr["inc"]);
            }

            foreach ($arr as $key => $value) {
                $ret .= $delim;
				if(is_string($value)){
					$value = "'".$value."'";
				}
                $ret .= $key . ":" . $value;
                $delim = ",";
            }
        }
        $ret .= "}]";
        return $ret;
    }

}
?>