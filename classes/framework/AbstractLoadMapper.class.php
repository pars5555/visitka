<?php
/**
 * <p><b>AbstractLoadMapper class</b> is a base class for all mapper classes.</p>
 * 
 * @author  Naghashyan Solutions, e-mail: info@naghashyan.com
 * @version 1.0
 * @package framework
*/
abstract class  AbstractLoadMapper{

	private $params;	
	protected $smarty;
	
  /**
   * Return 
   * @abstract  
   * @access
 
   * @return 
  */
	public function __construct(){
		
	}
	
  /**
   * @abstract  
   * @access
   * @param
   * @return 
  */
	//public abstract function initUrlConfig();
	
  /**
   * Return a thing based on $parameter
   * @abstract  
   * @access
   * @param $parameter 
   * @return integer|babyclass
  */	
	public abstract function getCurrentLoads();
	
  /**
   * Return a thing based on $url, $matches $parameters
   * @abstract  
   * @access
   * @param $url, $matches
   * @return 
  */
	public abstract function getDynamicLoad($url, $matches);
	
  /**
   * Return a thing based on $exCode parameter
   * @abstract  
   * @access
   * @param $exCode 
   * @return $param
  */

	public abstract function notFoundHandler($exCode);

  /**
   * Return a thing based on $nm parameter
   * @abstract  
   * @access
   * @param $nm 
   * @return 
  */	
	public abstract function __get($nm);

  /**
   * Return a thing based on $name, $arguments  parameters
   * @abstract  
   * @access
   * @param $name, $arguments 
   * @return
  */
	public abstract function __call($name, $arguments);

  /**
   * Return a thing based on $parameter
   * @abstract  
   * @access
   * @param $params 
   * @return integer|babyclass
  */		
	public function setParams($params){
		$this->params = $params;
	}
}

?>