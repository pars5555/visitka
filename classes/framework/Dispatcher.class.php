<?php

require_once(FRAMEWORK_PATH . "/exceptions/ClientException.class.php");
require_once(FRAMEWORK_PATH . "/exceptions/RedirectException.class.php");
require_once(FRAMEWORK_PATH . "/exceptions/NoAccessException.class.php");
require_once(FRAMEWORK_PATH . "/DBMSFactory.class.php");

//this part should be refactored, please create a method that will require the given path and then will instantiate the corresponding instance
//after that it should return the object of the class, in case of any problems it should return a null.


if (defined("DB_FACTORY")) {
    require_once(DB_FACTORY);
}

if (defined("LOAD_MAPPER")) {
    require_once(LOAD_MAPPER);
}

if (defined("SESSION_MANAGER")) {
    require_once(SESSION_MANAGER);
}

/**
 * <p><b>Dispatcher class</b> is a base class for initilize configuration and database connection.</p>
 * <p>The main purpose of this this file is dispatching requests in the project.</p>
 * 
 * @author  Naghashyan Solutions, e-mail: info@naghashyan.com
 * @version 1.0
 * @package framework
 */
class Dispatcher {

  
    protected $toCache = false;
    public $loadsPackage;
    private $isAjax = false;

    /**
     * <p>In the  <b>_construct()</b> we are defining basic goals.</p>
     * <p><b>$package</b> is a folder name(for our custom case it is named "main") that we are create our class folder.</p>  
     * <p><b>$command</b> is a defauld load name,that should be loaded at the first time,when you run it in you web browser.</p>
     * 
     */
    public function __construct() {
        
        //initilize db connection		
        DBMSFactory::init();

        //initilize load mapper	
        if (defined("LOAD_MAPPER")) {
            $this->loadMapper = $this->newClass(LOAD_MAPPER);
        }

        if (defined("SESSION_MANAGER")) {
            $this->sessionManager = $this->newClass(SESSION_MANAGER);
        }

        $this->actionPackage = ACTIONS_DIR;
        $this->loadsPackage = LOADS_DIR;

        $command = "";
        $isAjax = false;
        $args = array();
        if (preg_match_all("/(\/([^\/]+))/", $_REQUEST["_url"], $matches)) {
            if ($matches[2][0] == "dynamic") {
                array_shift($matches[2]);

                $dynamicIndex = 9; //--/dynamic/
                $loadArr = $this->loadMapper->getDynamicLoad(substr($_REQUEST["_url"], $dynamicIndex), $matches[2]);

                if (is_array($loadArr)) {
                    $package = $loadArr["package"];
                    $command = $loadArr["command"];
                    $args = $loadArr["args"];                    
                } else {

                    $this->showNotFound(NoAccessException::$NOT_FOUND);
                    return false;
                }
            } else {
                $package = array_shift($matches[2]);
                $command = array_shift($matches[2]);
                $args = $matches[2];
                
                if (isset($args[count($args) - 1])) {
                    if (preg_match("/(.+?)\.ajax/", $args[count($args) - 1], $matches1)) {
                        $this->isAjax = true;
                        $args[count($args) - 1] = $matches1[1];
                    }
                }
                $package = str_replace("_", "/", $package);
            }
        }
        //--replacing separarators for getting real package's path
        //$this->sessionManager->setArgs($args);
        //	$this->sessionManager->setDispatcher($this);
        $this->dispatch($package, $command, $args);
    }

    /**
     * Return a thing based on $parameter
     * @abstract  
     * @access
     * @param $isAjax 
     * @return integer|babyclass
     */
    public function setIsAjax($isAjax) {
        $this->isAjax = $isAjax;
    }

    /**
     * Return a thing based on $parameter
     * @abstract  
     * @access
     * @param $parameter 
     * @return integer|babyclass
     */
    public function isAjax() {
        return $this->isAjax;
    }

    /**
     * <p>In the <b>dispatch()</b> function you are controlling the request.</p>
     * <p>Actions are requested via a special URL patterns "http://host/dyn/actionpackage_actionInnerPackage/do_action_name".</p>
     * <p>For example, for requesting NgsExampleAction.class.php the "http://host/do_ngs_example" URL should be used.</p>
     * <p><b>"do_"</b> should be cuted and left "ngs_example".After using ucfirst() function we get "Ngs_example".</p>
     * <p>Using preg_replace() function we get "NgsExample".</p>
     * 
     * Return a thing based on $package, $command, $args parameters
     * @abstract  
     * @access
     * @param $package, $command, $args
     * @return
     */
    public function dispatch($package, $command, &$args) {
        if (isset($_REQUEST["show_not_found"])) {
            $this->loadMapper->notFoundHandler(0);
            return;
        }
        
        $this->args = &$args;
        if ($command == "") {
            $command = "default";
        }
        $isCommand = false;
        if (strripos($command, "do_") === 0) {
            $isCommand = true;
            $command = substr($command, 3);
        }
        $command = ucfirst($command);

        function callbackhandler($matches) {
            return strtoupper(ltrim($matches[0], "_"));
        }

        $command = preg_replace_callback("/_(\w)/", "callbackhandler", $command);
        try {
            if ($command) {
                if ($isCommand) {
                    $this->doAction($package, $command);
                } else {
                    $this->loadPage($package, $command);
                }
            }
        } catch (ClientException $ex) {
            $errorArr = $ex->getErrorParams();
            $ret = "[{";
            if (is_array($errorArr)) {
                $delim = "";

                foreach ($errorArr as $key => $value) {
                    $ret .= $delim;
                    $ret .= "'" . $key . "': {code: '" . $value["code"] . "', message: '" . $value["message"] . "'}";
                    $delim = ",";
                }
            }
            $ret .= "}]";

            header("HTTP/1.0 403 Forbidden");
            echo($ret);
            exit();
        } catch (RedirectException $ex) {
            $this->redirect($ex->getRedirectTo());
        } catch (NoAccessException $ex) {
            $this->showNotFound($ex->getCode());
        } catch (Exception $ex) {
            $this->showNotFound(NoAccessException::$NOT_FOUND);
        }
    }

    /**
     * <p><b>loadPage()</b>function handling load files.</p>
     * <p>In this case $loadName is defining for example "NgsExample" concatenate with "Load" word and we get "NgsExampleLoad".</p>
     *
     * Return a thing based on $package, $command, $args parameters
     * @abstract  
     * @access
     * @param $package, $command, $args
     * @return
     */
    public function loadPage($package, $command, $args = false) {

        $loadName = $command . "Load";
        $actionFileName = CLASSES_PATH . "/" . $this->loadsPackage . "/" . $package . "/" . $loadName . ".class.php";
        try {
            if (file_exists($actionFileName)) {
                require_once($actionFileName);
                $loadObj = new $loadName();

                if (isset($args) && !empty($args) && is_array($args)) {
                    $this->args = array_merge($this->args, $args);
                }
                //$this->sessionManager->unsetArgs($this->args);
                $loadObj->initialize($this->sessionManager, $this->loadMapper, $this->args);
                $loadObj->setDispatcher($this);

                if ($this->validateRequest($loadObj)) {
                    $this->toCache = $loadObj->toCache();
                    if (!$this->toCache) {
                        $this->dontCache();
                    }
                    $loads = $this->loadMapper->getCurrentLoads();
                    $loadObj->service($loads); //passing arguments

                    if (!$this->toCache) {
                        $this->dontCache();
                    }

                    $templator = $loadObj->getTemplator();
                    $templator->displayResult();
                    return;
                }

                if ($loadObj->onNoAccess(NoAccessException::$NO_ACCESS)) {
                    return;
                }
            }
        } catch (NoAccessException $ex) {
            if ($loadObj->onNoAccess($ex->getCode())) {
                return;
            }
        }


        $this->showNotFound(NoAccessException::$NOT_FOUND);
    }

    /**
     * <p><b>doAction()</b>function handling action files.</p>
     * <p>In this case $actionName is defining for example "NgsExample" concatenate with "Action" word and we get "NgsExampleAction".</p>
     * 
     * Return a thing based on $package, $action parameters
     * @abstract  
     * @access
     * @param $package, $action
     * @return
     */
    private function doAction($package, $action) {
        $actionName = $action . "Action";
        $actionFileName = CLASSES_PATH . "/" . $this->actionPackage . "/" . $package . "/" . $actionName . ".class.php";
        try {
            if (file_exists($actionFileName)) {
                require_once($actionFileName);
                $actionObj = new $actionName();
                $actionObj->initialize($this->sessionManager, $this->loadMapper, $this->args);
                $actionObj->setDispatcher($this);
                if ($this->validateRequest($actionObj)) {
                    $this->toCache = $actionObj->toCache();
                    if (!$this->toCache) {
                        $this->dontCache();
                    }
                    $actionObj->service();
                    return;
                }

                if ($actionObj->onNoAccess(NoAccessException::$NO_ACCESS)) {
                    return;
                }
            }
        } catch (NoAccessException $ex) {
            if ($actionObj->onNoAccess($ex->getCode())) {
                return;
            }
        }

        $this->showNotFound(NoAccessException::$NOT_FOUND);
    }

    /**
     * <p>In the <b>validateRequest($request)</b> function we are defining users role for the page.</p>
     * <p>See full description in the <b>SessionManager.class.php</b> file</p>
     *
     * Return a thing based on $request parameters
     * @abstract  
     * @access
     * @param $request
     * @return false
     */
    private function validateRequest($request) {
        if ($request->getRequestGroup() == RequestGroups::$guestRequest) {
            return true;
        }
        $user = $this->sessionManager->getUser();
        if ($user->validate()) {
            if ($this->sessionManager->validateRequest($request, $user)) {
            return true;
            }
        }
        return false;
    }

    /**
     * Return a thing based on $dispatcherIndex, $params parameters
     * @abstract  
     * @access
     * @param $dispatcherIndex, $params
     * @return
     */
    private function displayResult($dispatcherIndex, $params) {
        
    }

    /**
     * <p>The <b>redirect()</b> function using for redirect in some cases to the another page.</p>
     *
     * Return a thing based on $url, $isSecure parameters
     * @abstract  
     * @access
     * @param $url, $isSecure 
     * @return
     */
    public function redirect($url, $isSecure = false) {
        $protocol = "http";
        if ($isSecure) {
            $protocol = "https";
        }

        header("location: " . $protocol . "://" . $_SERVER[HTTP_HOST] . "/$url");
    }

    /**
     * <p>The <b>showNotFound()</b> function using for get <i>"404 Not Found"<i> page.</p> 
     *
     * Return a thing based on $code parameter
     * @abstract  
     * @access
     * @param $code 
     * @return
     */
    protected function showNotFound($code = 0) {
        if (!$this->loadMapper->notFoundHandler($code)) {
            header("HTTP/1.0 404 Not Found");
        }
        exit();
    }

    /**
     * Return a thing based on $parameter
     * @abstract  
     * @access
     * @param $parameter 
     * @return
     */
    protected function dontCache() {
        Header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        Header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
        Header("Pragma: no-cache"); // HTTP/1.0
        Header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
    }

    private function newClass($path) {
        require_once($path);
        $args = func_get_args();
        array_shift($args);
        $className = substr($path, strrpos($path, "/") + 1);
        $className = substr($className, 0, strpos($className, "."));
        $reflectionClass = new ReflectionClass($className);
        $obj = $reflectionClass->newInstanceArgs($args);

        return $obj;
    }

}

?>