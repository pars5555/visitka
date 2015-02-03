<?php
require_once (CLASSES_PATH . "/framework/AbstractManager.class.php");
require_once (CLASSES_PATH . "/dal/mappers/AdminsMapper.class.php");

class AdminsManager extends AbstractManager  {

   

    /**
     * @var singleton instance of class
     */
    private static $instance = null;

    /**
     * Initializes DB mappers
     *
     * @param object $config
     * @param object $args
     * @return
     */
    function __construct() {
            $this -> mapper = AdminsMapper::getInstance();
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
                self::$instance = new AdminsManager();
        }
        return self::$instance;
    }
	
     public function getByLoginPassword($login, $password) {        
        return $this->mapper->getByUsernamePassword($login, $password);
    }
    
     public function updateAdminHash($uId) {
        $hash = $this->generateHash($uId);
        $adminDto = $this->mapper->createDto();
        $adminDto->setId($uId);
        $adminDto->setHash($hash);
        $this->mapper->updateByPK($adminDto);
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