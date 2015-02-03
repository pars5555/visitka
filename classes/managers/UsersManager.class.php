<?php
require_once (CLASSES_PATH . "/framework/AbstractManager.class.php");
require_once (CLASSES_PATH . "/dal/mappers/UsersMapper.class.php");

class UsersManager extends AbstractManager  {

    /**
     * @var singleton instance of class
     */
    private static $instance = null;

    /**
     * Initializes DB mappers
     *
    
     * @return
     */
    function __construct() {
            $this -> mapper = UsersMapper::getInstance();           
    }

    /**
     * Returns an singleton instance of this class
     *
     * @param object $config
     * @param object $args
     * @return
     */
    public static function getInstance() {

        if (self::$instance == null) {
                self::$instance = new UsersManager();
        }
        return self::$instance;
    }
	
    public function getByLoginPassword($login, $password) {        
        return $this->mapper->getByUsernamePassword($login, $password);
    }
    
    public function updateUserHash($uId) {
        $hash = $this->generateHash($uId);
        $userDto = $this->mapper->createDto();
        $userDto->setId($uId);
        $userDto->setHash($hash);
        $this->mapper->updateByPK($userDto);
        return $hash;
    }
    
     public function generateHash($id) {
        return md5($id * time() * 19);
    }
     public function validate($id, $hash) {
        return $this->mapper->validate($id, $hash);
    }
    
}

?>