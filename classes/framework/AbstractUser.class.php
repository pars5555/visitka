<?php
/**
 * <p><b>AbstractUser class</b> is a base class for authorized users.
 * The child classes is <b>GuestUser.class.php,AuthenticateUser.class.php</b>. </p>
 * 
 * @author  Naghashyan Solutions, e-mail: info@naghashyan.com
 * @version 1.0
 * @package framework
*/
abstract class AbstractUser{
	
	private $sessionParams = array();
	private $cookieParams = array();
	
  /**
   * @abstract  
   * @access
   * @param
   * @return 
  */
	public function __construct(){
		
	}
	
  /**
   * Return a thing based on $name, $value parameters
   * @abstract  
   * @access
   * @param $name, $value
   * @return 
  */	
	public function setSessionParam($name, $value){
		$this->sessionParams[$name] = $value;
	}
	
  /**
   * Return a thing based on $name parameter
   * @abstract  
   * @access
   * @param $name
   * @return sessionParams[$name]
  */	
	public function getSessionParam($name){
		return $this->sessionParams[$name];
	}	
	
  /**
   * @abstract  
   * @access
   * @param 
   * @return sessionParams
  */	
	public function getSessionParams(){
		return $this->sessionParams;
	}		
	
  /**
   * Return a thing based on $name, $value parameters
   * @abstract  
   * @access
   * @param $name, $value
   * @return 
  */
	public function setCookieParam($name, $value){
		$this->cookieParams[$name] = $value;
	}
	
  /**
   * Return a thing based on $name parameter
   * @abstract  
   * @access
   * @param $name
   * @return cookieParams[$name]
  */	
	public function getCookieParam($name){
		return $this->cookieParams[$name];
	}	
	
  /**
   * @abstract  
   * @access
   * @param  
   * @return cookieParams
  */	
	public function getCookieParams(){
		return $this->cookieParams;
	}	
	
  /**
   * @abstract  
   * @access
   * @param 
   * @return 
  */	
	public abstract function validate();

  /**
   * @abstract  
   * @access
   * @param  
   * @return 
  */		
	public abstract function getLevel();

}

?>