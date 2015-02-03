<?php

require_once(FRAMEWORK_PATH . "/AbstractSessionManager.class.php");
require_once(CLASSES_PATH . "/managers/LanguageManager.class.php");
require_once(CLASSES_PATH . "/managers/CmsSettingsManager.class.php");

/**
 * <p><b>AbstractRequest class</b> is a base class for all action classes.
 * The child of this class is <b>AbstractAction.class.php,AbstractLoad.class.php</b> files. </p>
 * 
 * @author  Naghashyan Solutions, e-mail: info@naghashyan.com
 * @version 1.0
 * @package framework
 */
abstract class AbstractRequest {

    protected $config;
    protected $customer;
    protected $args;
    protected $sessionManager;
    protected $loadMapper;
    protected $requestGroup;

    /**
     * Return a thing based on $sessionManager, $config, $loadMapper, $args parameters
     * @abstract  
     * @access
     * @param $sessionManager, $config, $loadMapper, $args
     * @return
     */
    public function initialize($sessionManager, $config, $loadMapper, $args) {
        $this->sessionManager = $sessionManager;
        $this->loadMapper = $loadMapper;
        $this->config = $config;
        $this->args = $args;
    }

    /**
     * Return a thing based on $requestGroup parameter
     * @abstract  
     * @access
     * @param $requestGroup 
     * @return
     */
    public function setRequestGroup($requestGroup) {
        $this->requestGroup = $requestGroup;
    }

    public function secure($var, $defaultValue = null) {
        if (isset($var)) {
            return trim(htmlspecialchars(strip_tags($var)));
        } else {
            return $defaultValue;
        }
    }

    /**
     * Return a thing based on $parameter
     * @abstract  
     * @access
     * @param $parameter 
     * @return
     */
    public function getRequestGroup() {
        return $this->requestGroup;
    }

    /**
     * Return a thing based on $dispatcher parameter
     * @abstract  
     * @access
     * @param $dispatcher 
     * @return object
     */
    public function setDispatcher($dispatcher) {
        $this->dispatcher = $dispatcher;
    }

   
    /**
     * @abstract  
     * @access
     * @param 
     * @return false
     */
    public function toCache() {
        return false;
    }

    /**
     * @abstract  
     * @access
     * @param  
     * @return
     */
    protected function cancel() {
        throw new NoAccessException("Load canceled request ");
    }

    /**
     * @abstract  
     * @access
     * @param  
     * @return false
     */
    public function onNoAccess() {
        return false;
    }

    /**
     * Return a thing based on $url, $isSecure parameters
     * @abstract  
     * @access
     * @param $url, $isSecure 
     * @return
     */
    protected function redirect($url, $isSecure = false) {
        $protocol = "http";
        if ($isSecure) {
            $protocol = "https";
        }
        header("location: " . $protocol . "://" . HTTP_HOST . "/$url");
        exit();
    }

    /**
     * Return a thing based on $config parameter
     * @abstract  
     * @access
     * @param $config 
     * @return
     */
    public static function notFoundHandler($config) {
        header("HTTP/1.0 404 Not Found");
    }

    /**
     * Return a thing based on $wrapperLoad parameter
     * @abstract  
     * @access
     * @param $wrapperLoad 
     * @return false
     */
    protected function getWrapperLoad() {
        return false;
    }

    protected function getUserLevel() {
        return $this->sessionManager->getUser()->getLevel();
    }

    protected function getUserId() {
        return $this->sessionManager->getUser()->getId();
    }

    protected function getUser() {
        return $this->sessionManager->getUser();
    }

    public function getCustomer() {
        if (!$this->customer) {
            if ($this->getUserLevel() != UserGroups::$GUEST) {
                $userId = $this->getUserId();
                if ($this->getUserLevel() == UserGroups::$COMPANY) {
                    $companyManager = CompanyManager::getInstance();
                    $this->customer = $companyManager->selectByPK($userId);
                } else if ($this->getUserLevel() == UserGroups::$SERVICE_COMPANY) {
                    $serviceCompanyManager = ServiceCompanyManager::getInstance();
                    $this->customer = $serviceCompanyManager->selectByPK($userId);
                } else if ($this->getUserLevel() == UserGroups::$ADMIN) {
                    $adminManager = AdminManager::getInstance();
                    $this->customer = $adminManager->selectByPK($userId);
                } else if ($this->getUserLevel() == UserGroups::$USER) {
                    $userManager = UserManager::getInstance();
                    $this->customer = $userManager->selectByPK($userId);
                }
            }
        }
        return $this->customer;
    }

    public function getCustomerLogin() {
        $customer = $this->getCustomer();
        if (isset($customer)) {
            return $customer->getEmail();
        }
    }

    public function getCmsVar($var) {
        return CmsSettingsManager::getInstance()->getValue($var);
    }

    public function getPhrase($phrase_id, $ul = null) {
        return LanguageManager::getInstance()->getPhrase($phrase_id, $ul);
    }

    public function getPhraseSpan($phrase_id, $ul = null) {
        return LanguageManager::getInstance()->getPhraseSpan($phrase_id, $ul);
    }

    public function getPhrases($phraseIds, $ul = null) {
        $ret = array();
        foreach ($phraseIds as $pid) {
            $ret[] = $this->getPhrase($pid);
        }
        return $ret;
    }

    public function setCookie($key, $value, $expire = 0) {
        $domain = "." . DOMAIN;
        setcookie($key, $value, $expire, "/", $domain);
    }

}

?>