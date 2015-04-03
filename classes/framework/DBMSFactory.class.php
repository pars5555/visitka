<?php

require_once(CLASSES_PATH . "/util/db/ImprovedDBMS.class.php");

/**
 * Returns an instance of DBMS class.
 */
class DBMSFactory {

  
    private static $dbmsInstance = null;

    /**
     * Not used. 
     */
    private function __construct() {
        
    }

    /**
     * Should be called before getDBMS() function to initialize 
       * 
         * @return 
     */
    public static function init() {
        
    }

    /**
     * Returns corresponding instance of DBMS class.
     * 
     * @return 
     */
    public static function getDBMS() {
        if (is_null(self::$dbmsInstance)) {
            self::$dbmsInstance = self::newClass(DBMS);
        }
        return self::$dbmsInstance;
    }

    private static function newClass($path) {
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