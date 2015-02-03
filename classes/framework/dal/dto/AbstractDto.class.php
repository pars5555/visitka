<?php

/**
 * <p><b>AbstractDto class</b> is a base class for all dto classes.</p>
 * <p><b>Data transfer object (DTO)</b> is using to transfer data between software application subsystems.
 * DTOs are often used in conjunction with data access objects to retrieve data from a database.
 * </p>
 * <p>
 * <p>To creating data transfer object (DTO) that holds all data that is required for the remote call.</p>
 * It also template class for all DTO (data transfer object) class types and defines mandatory  members and functions to be implemented by child classes.
 * 
 * @author  Naghashyan Solutions, e-mail: info@naghashyan.com
 * @version 1.0
 * @package framework
 */
abstract class AbstractDto {

    /**
     * Does nothing 
     */
    public function __construct() {
        
    }

    /**
     * Returns an assoc array, which maps DB fields into DTO members. 
     * The member names should correspond to the following formats:
     * 		array(
     * 				"id" => "id",
     * 				"user_id" => "userId",
     * 				"UserName" => "userName",
     * 				"LAST_TIME" => "lastTime"
     * 			)
     * 
     * @return assoc array 
     */
    public abstract function getMapArray();

    /**
     * Overloads getter and setter methods. 
     * 
     * @param object $m - method name, should start either by "set" or by "get" prefix
     * @param object $a - for setter methods $a[0] will be used as new value for property
     * @return 
     */
    public function __call($m, $a) {
        // retrieving the method type (setter or getter)
        $type = substr($m, 0, 3);

        // retrieving the field name
        $fieldName = self::lowerFirstLetter(substr($m, 3));

        if ($type == 'set') {
            $this->$fieldName = $a[0];
        } else
        if ($type == 'get') {
            if (isset($this->$fieldName)) {
                return $this->$fieldName;
            }
            return null;
        }
    }

    /**
     * The first letter of input string changes to Lower case.
     * The transformation will be performed only ASCII alphabetical symbols, 
     * otherwise the same text will be returned without any changes. 
     * 
     * @param String $str - input string, that should be lowercased
     * @return - string
     */
    private static function lowerFirstLetter($str) {
        $first = substr($str, 0, 1);
        $asciiValue = ord($first);
        if ($asciiValue >= 65 && $asciiValue <= 90) {
            $asciiValue+=32;
            return chr($asciiValue) . substr($str, 1);
        }
        return $str;
    }
    
    
     public function toJSON($htmlSpecialChar = false) {

        return self::dtoToJSON($this, $htmlSpecialChar);
    }

    public static function dtoToJSON($dto, $htmlSpecialChar = false, $fieldsMap = array()) {
        $josn_text = "{";
        if (empty($fieldsMap)) {
            $mapArray = $dto->getMapArray();
        } else {
            $mapArray = $fieldsMap;
        }
        foreach ($mapArray as $key => $value) {
            $josn_text .= '"' . $key . '"' . ':' . '"' . ($htmlSpecialChar == true ? htmlspecialchars($dto->$value) : $dto->$value) . '"' . ',';
        }
        $len = strlen($josn_text);
        if ($len > 1) {
            $josn_text = substr($josn_text, 0, -1);
        }
        $josn_text .= "}";
        return $josn_text;
    }

    public static function dtosToJSON($dtos, $htmlSpecialChar = false) {

        if (empty($dtos)) {
            return "[]";
        }
        $ret = "[";
        foreach ($dtos as $dto) {
            $json = self::dtoToJSON($dto, $htmlSpecialChar);
            $ret .=$json . ',';
        }
        $ret = substr($ret, 0, -1) . ']';
        return $ret;
    }

    public function toArray() {
        return self::dtoToArray($this);
    }

    public static function dtosToArray($dtos, $fieldNames = array()) {

        $ret = array();
        foreach ($dtos as $dto) {
            $ret [] = self::dtoToArray($dto, $fieldNames);
        }
        return $ret;
    }

    public static function dtoToArray($dto, $fieldNames = array()) {
        $ret = array();
        if (empty($fieldNames)) {
            $mapArray = $dto->getMapArray();
        } else {
            $mapArray = $fieldNames;
        }
        foreach ($mapArray as $key => $value) {
            $ret [$key] = $dto->$value;
        }
        return $ret;
    }

}

?>