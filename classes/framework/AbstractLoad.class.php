<?php
require_once(CLASSES_PATH."/framework/exceptions/NoAccessException.class.php");
require_once(CLASSES_PATH."/framework/AbstractRequest.class.php");
require_once(CLASSES_PATH."/framework/templators/SmartyTemplator.class.php");

/**
 * <p><b>AbstractLoad class</b> is a base class for all load classes, which extends from <b>AbstractRequest</b>.</p>
 * 
 * @author  Naghashyan Solutions, e-mail: info@naghashyan.com
 * @version 1.0
 * @package framework
*/
abstract class AbstractLoad extends AbstractRequest{
	
	protected $smarty;

	protected $wrapperLoad = null;
	protected $nameSpace = null;
	
  /**
   * Return a thing based on $smarty, $sessionManager,  $loadMapper, $args parameters
   * @abstract  
   * @access
   * @param $smarty, $sessionManager, $loadMapper, $args
   * @return
  */
	public function initialize($sessionManager, $loadMapper, $args){
		parent::initialize($sessionManager, $loadMapper, $args);
		$this->params = array();
	}
	
  /**
   * Return a thing based on $loads parameter
   * @abstract  
   * @access
   * @param $loads 
   * @return 
  */
	public final function service($loads){
		
		$templateName = $this->getTemplate();
		$this->params["inc"] = array();
		$this->params["_cl"] = $this;
		$this->load();
		$defaultLoads = $this->getDefaultLoads($this->args);
		$defaultLoads = array_merge($defaultLoads, $loads);
		foreach($defaultLoads as $key => $value){
			$this->nest($key, $value);
		}
		
	}

  /**
	 * 
	 *
   * Return a thing based on $name, $value parameters
   * @abstract  
   * @access
   * @param $name, $value
   * @return
  */
	public final function addParam($name, $value){
		$this->params[$name] = $value;

	}

  /**
   * Return a thing based on $namespace, $loadArr, $isSecur parameters
   * @abstract  
   * @access
   * @param $namespace, $loadArr, $isSecur
   * @return Returns nested load
  */
	public function nest($namespace, $loadArr, $isSecur = true){
		
		$loadFileName = CLASSES_PATH."/".$loadArr["load"].".class.php";
		$loadName = substr($loadArr["load"], strrpos($loadArr["load"], "/")+1);
		if(file_exists($loadFileName) && $this->isValidLoad($namespace, $loadName)){
			require_once($loadFileName);
			$loadObj = new $loadName();
			if(isset($loadArr["args"])){
				$args = array_merge($this->args, $loadArr["args"]);
			}
			$loadObj->initialize($this->sessionManager, $this->loadMapper, $args);
			$loadObj->setDispatcher($this->dispatcher);
			$loadObj->setWrapperLoad($this, $namespace);
			$allowLoad = false;
			if($isSecur){
				if($this->sessionManager->validateRequest($loadObj, $this->sessionManager->getUser())){
					$allowLoad = true;
				}
			}
			if(!$isSecur || $allowLoad){
				$loadObj->nestedLoad($this);
				$loadObj->service($loadArr["loads"]);
				if(!isset($this->params["inc"][$namespace]["filename"])){
					$this->params["inc"][$namespace]["filename"] = $loadObj->getTemplate();
					$this->params["inc"][$namespace]["params"] = $loadObj->getParams();
				}
				
			}
			else{
				$loadObj->onNoAccess(NoAccessException::$NO_ACCESS);
			}
			return $loadObj;
		}
		else{
			throw new NoAccessException("User hasn't access to the load: ".$loadFileName);			
		}		
		/*
		string(7) "window1"
		array(3) {
		  ["load"]=>
		  string(21) "snippets/Snippet1Load"
		  ["args"]=>
		  array(1) {
		    ["name"]=>
		    string(4) "zzzz"
		  }
		  ["loads"]=>
		  array(0) {
		  }
		}*/
		
	}

  /**
   * Return a thing based on $namespace, $template parameters
   * @abstract  
   * @access
   * @param $namespace, $template
   * @return
  */
	public function includeTemplate($namespace, $template){

		$this->params["inc"][$namespace]["filename"] = $template;

	}

  /**
   * @abstract  
   * @access
   * @param  
   * @return $params
  */
	public function getParams(){
		return $this->params;
	}
	
	 /**
   * @abstract  
   * @access
   * @param  $pname name
   * @return $param
  */
	public function getParam($pname){
		return $this->params[$pname];
	}

  /**
   * @abstract  
   * @access
   * @param 
   * @return false
  */
	public function toCache(){
		return false;
	}
	
  /**
   * Return a thing based on $args parameter
   * @abstract  
   * @access
   * @param $args 
   * @return array()
  */
	public function getDefaultLoads($args){
		return array();
	}
	
  /**
   * Return a thing based on $namespace, $load parameters
   * @abstract  
   * @access
   * @param $namespace, $load
   * @return false
  */
	public function isValidLoad($namespace, $load){
		return false;
	}
	
  /**
   * @abstract  
   * @access
   * @param
   * @return null
  */
   
	public function getTemplate(){
		return null;
	}

  /**
   * Return a thing based on $itemsCount, $limit, $params parameters
   * @abstract  
   * @access
   * @param $itemsCount, $limit, $params
   * @return
  */
   
	protected function initPagging($itemsCount = 0, $limit = 20, $params = array()){
		$this->addParam("paggingCount", $this->paggingCount);
		$pageCount = ceil($itemsCount/$limit);
		
		$offset = 1;
		if($_REQUEST["offset"] && is_numeric($_REQUEST["offset"])){
			$offset = $_REQUEST["offset"];
		}

		if(!isset($_REQUEST["_navigator"]) || $_REQUEST["_navigator"] !== "true"){
			$this->addParam('showPagging', true);
		}
		$this->addParam('itemsCount', $itemsCount);
		$this->addParam('pageCount', $pageCount);
		$this->addParam('limit', $limit);
		$this->addParam('offset', $offset);
		$this->addParam('params', $params);
	}

  /**
   * @abstract  
   * @access
   * @param  
   * @return $pagging
  */
   
	protected function getPagging(){
		$offset = 1;
		if($_REQUEST["offset"] && is_numeric($_REQUEST["offset"])){
			$offset = $_REQUEST["offset"];
		}
		
		$elemOffset = ($offset - 1)*$_REQUEST["limit"];
		$pagging = array("paggingCount" => $this->paggingCount, 'offset' => $offset, 'elemOffset' => $elemOffset, 'limit' => $_REQUEST["limit"], 'params' => $_REQUEST["params"]);
		return $pagging;
	}

  /**
   * @abstract  
   * @access
   * @param 
   * @return 
  */
   
	public abstract function load();
	
  /**
   * Return a thing based on $ownerLoad parameter
   * @abstract  
   * @access
   * @param $ownerLoad 
   * @return 
  */
   
	protected function nestedLoad($ownerLoad){
		
	}
	
  /**
   * Return a thing based on $wrapperLoad, $nameSpace parameters
   * @abstract  
   * @access
   * @param $wrapperLoad, $nameSpace 
   * @return 
  */
   
	protected function setWrapperLoad($wrapperLoad, $nameSpace){
		$this->wrapperLoad = $wrapperLoad;
		$this->nameSpace = $nameSpace;
	}
	
  /**
   * @abstract  
   * @access
   * @param 
   * @return $wrapperLoad
  */
   
	protected function getWrapperLoad(){
		return $this->wrapperLoad;
	}
	
  /**
   * @abstract  
   * @access
   * @param  
   * @return $nameSpace
  */
   
	protected function getNameSpace(){
		return $this->nameSpace;
	}
	
	public function getTemplator(){
		return new SmartyTemplator($this, $this->loadMapper);
	}
        
        protected function getJsonInput(){
            $entityBody = file_get_contents('php://input');
            return json_decode($entityBody, true); 
	}

      
	
}
?>