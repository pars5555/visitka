<?php

require_once (CLASSES_PATH . "/framework/dal/mappers/AbstractMapper.class.php");
require_once (CLASSES_PATH . "/dal/dto/AdminDto.class.php");

/**
 *

 */
class AdminsMapper extends AbstractMapper {

    /**
     * @var table name in DB
     */
    private $tableName;

    /**
     * @var an instance of this class
     */
    private static $instance = null;

    /**
     * Initializes DBMS pointers and table name private class member.
     */
    function __construct() {
        // Initialize the dbms pointer.
        parent::__construct();

        // Initialize table name.
        $this->tableName = "admins";
    }

    /**
     * Returns an singleton instance of this class
     * @return
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new AdminsMapper();
        }
        return self::$instance;
    }

    /**
     * @see AbstractMapper::createDto()
     */
    public function createDto() {
        return new AdminDto();
    }

    /**
     * @see AbstractMapper::getPKFieldName()
     */
    public function getPKFieldName() {
        return "id";
    }

    /**
     * @see AbstractMapper::getTableName()
     */
    public function getTableName() {
        return $this->tableName;
    }

    private static $GET_USER_BY_USERNAME_AND_PASS = "SELECT * from `%s` WHERE `email` = '%s' AND BINARY `password` = '%s'";

    public function getByUsernamePassword($username, $password) {
        $sqlQuery = sprintf(self::$GET_USER_BY_USERNAME_AND_PASS, $this->getTableName(), $username, $password);
        $result = $this->fetchRows($sqlQuery);
        if (count($result) === 1) {
            return $result[0];
        }
        return null;
    }

    public static $VALIDATE_USER = "SELECT * FROM `%s` WHERE `id` = %d AND `hash` = '%s'";

    public function validate($id, $hash) {
        $sqlQuery = sprintf(self::$VALIDATE_USER, $this->getTableName(), $id, $hash);
        $result = $this->fetchRows($sqlQuery);
        if (count($result) === 1) {
            return $result[0];
        } else {
            return null;
        }
    }

}

?>