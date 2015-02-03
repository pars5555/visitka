<?php

require_once (CLASSES_PATH . "/framework/DBMSFactory.class.php");

/**
 * <p><b>AbstractMapper class</b> is a base class for all mapper classes.</p>
 * It contains the basic functionality and also pointer to DBMS.
 * <p>Mappers handles all our database logic. Using the mapper we connect to our
 * database and provide an abstraction layer.</p>
 *
 * @author  Naghashyan Solutions, e-mail: info@naghashyan.com
 * @version 1.0
 * @package framework
 */
abstract class AbstractMapper {

    protected $dbms;

    /**
     * Initializes DBMS pointer.
     */
    function __construct() {
        $this->dbms = DBMSFactory::getDBMS();
    }

    /**
     * The child class must implemet this method to return table name.
     *
     * @return table name the mapper works with.
     */
    public abstract function getTableName();

    /**
     * The child class must implemet this method to return primary key field name.
     *
     * @return
     */
    public abstract function getPKFieldName();

    /**
     * The child class must implement this method
     * to return an instance of corresponding DTO class.
     *
     * @return
     */
    public abstract function createDto();

    /**
     * Calls stripslashes method to unescape magic quotes.
     *
     * @param object $dto
     * @param object $dbData
     * @return
     */
    protected function initializeDto($dto, $dbData, $prefix = false, $groupId = false) {
        $mapArray = $dto->getMapArray();

        if ($dbData == null) {
            return;
        }


        if ($prefix != false) {

            $prefix = $this->getTableName();
            foreach ($dbData as $dbDataKey => $dbDataValue) {
                if (strpos($dbDataKey, $prefix) === 0) {
                    $dbData[substr($dbDataKey, strlen($prefix) + 1)] = $dbDataValue;
                }
                unset($dbData[$dbDataKey]);
            }
        }

        // Get keys for dbData.

        $dbDataKeys = array_keys($dbData);

        foreach ($dbDataKeys as $keyName) {
            // Create function based on key name.
            $functionName = self::getCorrespondingFunctionName($mapArray, $keyName);
            if (strlen($functionName) != 0) {
                // Call function and initialize item based on data.
                $data = $dbData[$keyName];
                $dto->$functionName($data);
            }
        }
    }

    public static function getFieldValue($dto, $fieldName) {
        $func = self::getCorrespondingFunctionName($dto->getMapArray(), $fieldName, "get");
        return $dto->$func();
    }

    public function getLastSelectFoundRows() {
        $sqlQuery = "SELECT FOUND_ROWS as `found_rows`";
        return intval($this->fetchField($sqlQuery, "found_rows"));
    }

    public function lockTableWrite() {
        $sqlQuery = sprintf("LOCK TABLE `%s` WRITE", $this->getTableName());
        $this->dbms->query($sqlQuery);
    }

    public function unlockTables() {
        $this->dbms->query('UNLOCK TABLES');
    }

    /**
     * Selects from table by field and returns corresponding DTO
     *
     * @param object $id
     * @return
     */
    public function selectByField($fieldName, $fieldValue) {

        if (is_int($fieldValue)) {
            $sqlQuery = sprintf("SELECT * FROM `%s` WHERE `%s` = %d ", $this->getTableName(), $fieldName, $fieldValue);
        } else {
            $sqlQuery = sprintf("SELECT * FROM `%s` WHERE `%s` = '%s' ", $this->getTableName(), $fieldName, $fieldValue);
        }
        return $this->fetchRows($sqlQuery);
    }

    /**
     * Updates tables text field's value by primary key
     *
     * @param object $id - the unique identifier of table
     * @param object $fieldName - the name of filed which must be updated
     * @param object $fieldValue - the new value of field
     * @param object $esc - if true the field value will be escaped
     * @return affacted rows count or -1 if something goes wrong
     */
    public function updateTextField($id, $fieldName, $fieldValue, $esc = true) {
        // Create query.
        if (is_int($fieldValue)) {
            $q = "UPDATE `%s` SET `%s` = '%s' WHERE `%s` = %d ";
        } else {
            $q = "UPDATE `%s` SET `%s` = '%s' WHERE `%s` = '%s' ";
        }
        $q = sprintf($q, $this->getTableName(), $fieldName, ($esc) ? $this->dbms->escape($fieldValue) : $fieldValue, $this->getPKFieldName(), $id);
        // Execute query.
        $res = $this->dbms->query($q);
        if ($res) {
            $result = $this->dbms->getAffectedRows();
            return $result;
        }
        return -1;
    }

    /**
     * Updates tables numeric field's value by primary key
     *
     * @param object $id - the unique identifier of table
     * @param object $fieldName - the name of filed which must be updated
     * @param object $fieldValue - the new value of field
     * @return affected rows count or -1 if something goes wrong
     */
    public function updateNumericField($id, $fieldName, $fieldValue) {
        // Create query.
        $q = sprintf("UPDATE `%s` SET `%s` = %d WHERE `%s` = %d ", $this->getTableName(), $fieldName, $fieldValue, $this->getPKFieldName(), $id);
        // Execute query.
        $res = $this->dbms->query($q);
        if ($res) {
            $result = $this->dbms->getAffectedRows();
            return $result;
        }
        return -1;
    }

    /**
     * Updates tables numeric and text field's value by primary key
     *
     * @param object $id - the unique identifier of table
     * @param array $fieldsArr - array that key is fieldName and value is fieldValue
     * @example $fieldsArr array("id"=>"1")
     * @return affected rows count or -1 if something goes wrong
     */
    public function updateFields($id, $fieldsArr) {
        // Create query.
        $q = sprintf("UPDATE `%s` SET  ", $this->getTableName(), $this->getPKFieldName(), $id);
        $arrCount = count($fieldsArr);
        $sep = ", ";
        $i = 1;
        foreach ($fieldsArr as $fieldName => $fieldValue) {
            if ($i == $arrCount) {
                $sep = "";
            }
            if (is_numeric($fieldValue)) {

                $q .= sprintf("`%s` = %d" . $sep, $fieldName, $fieldValue);
            } else {

                if ($fieldValue == "CURRENT_TIMESTAMP") {
                    $q .= sprintf("`%s` = %s" . $sep, $fieldName, $fieldValue);
                } else {
                    $q .= sprintf("`%s` = '%s'" . $sep, $fieldName, $fieldValue);
                }
            }
            $i++;
        }
        $q .= sprintf(" WHERE `%s` = %d ", $this->getPKFieldName(), $id);

        // Execute query.
        $res = $this->dbms->query($q);
        if ($res) {
            $result = $this->dbms->getAffectedRows();
            return $result;
        }
        return -1;
    }

    /**
     * Sets field value NULL.
     *
     * @param object $id
     * @param object $fieldName
     * @return
     */
    public function setNull($id, $fieldName) {
        // Create query.
        $sqlQuery = sprintf("UPDATE `%s` SET `%s` = NULL WHERE `%s` = %d ", $this->getTableName(), $fieldName, $this->getPKFieldName(), $id);
        return $this->executeUpdate($sqlQuery);
    }

    /**
     * Deletes the row by primary key
     *
     * @param object $id - the unique identifier of table
     * @return affacted rows count or -1 if something goes wrong
     */
    public function deleteByPK($id) {
        $q = sprintf("DELETE FROM `%s` WHERE `%s` = %d ", $this->getTableName(), $this->getPKFieldName(), $id);
        // Execute query.
        $res = $this->dbms->query($q);
        if ($res) {
            $result = $this->dbms->getAffectedRows();
            return $result;
        }
        return -1;
    }

    /**    
     *
     * @param object $id - the unique identifier of table
     * @return affacted rows count or -1 if something goes wrong
     */
    public function deleteByField($fieldName, $fieldValue) {
        if (is_int($fieldValue)) {
            $q = sprintf("DELETE FROM `%s` WHERE `%s` = %d ", $this->getTableName(), $fieldName, $fieldValue);
        } else {
            $q = sprintf("DELETE FROM `%s` WHERE `%s` = '%s' ", $this->getTableName(), $fieldName, $fieldValue);
        }
        // Execute query.
        $res = $this->dbms->query($q);
        if ($res) {
            $result = $this->dbms->getAffectedRows();
            return $result;
        }
        return -1;
    }

    /**
     * Deletes the row by custom key
     *
     * @param object $fieldName - the unique identifier of table
     * @return affacted rows count or -1 if something goes wrong
     */
    public function deleteByCustomKey($fieldName, $fieldValue) {
        $q = sprintf("DELETE FROM `%s` WHERE `%s` = %d ", $this->getTableName(), $fieldName, $fieldValue);
        // Execute query.
        $res = $this->dbms->query($q);
        if ($res) {
            $result = $this->dbms->getAffectedRows();
            return $result;
        }
        return -1;
    }

    /**
     * Executes Update/Delete queries.
     *
     * @param object $sqlQuery
     * @return affected rows count or -1 if something goes wrong
     */
    protected function executeUpdate($sqlQuery) {
        // Execute query.
        $res = $this->dbms->query($sqlQuery);
        if ($res) {
            $result = $this->dbms->getAffectedRows();
            return $result;
        }
        return -1;
    }

    /**
     * Inserts new row in table.
     *
     * @param object $fieldsArray - the field names, which must be inserted
     * @param object $valuesArr - field values
     * @param object $esc [optional] - shows if the textual values must be escaped before setting to DB
     * @return autogenerated id or -1 if something goes wrong
     */
    public function insertValues($fieldsArray, $valuesArr, $esc = true) {

        //validating input params
        if ($fieldsArray == null || $valuesArr == null ||
                !is_array($fieldsArray) || !is_array($valuesArr) || count($fieldsArray) == 0 || count($fieldsArray) != count($valuesArr) || !is_bool($esc)) {
            throw new Exception("The input params don't meet criterias.", ErrorCodes::$DB_INVALID_PARAM);
        }

        //creating query
        $q = sprintf("INSERT INTO `%s` SET ", $this->getTableName());
        for ($i = 0; $i < count($fieldsArray); $i++) {
            if (is_numeric($valuesArr[$i])) {
                $q .= sprintf(" `%s` = %d,", $fieldsArray[$i], $valuesArr[$i]);
            } else {
                $q .= sprintf(" `%s` = '%s',", $fieldsArray[$i], ($esc) ? $this->dbms->escape($valuesArr[$i]) : $valuesArr[$i]);
            }
        }
        $q = substr($q, 0, -1);

        // Execute query.
        $res = $this->dbms->query($q);
        if ($res) {
            // Get last affected id.
            $result = $this->dbms->getLastInsertedId();
            // Return result
            return $result;
        }
        return -1;
    }

    /**
     * Inserts dto into table.
     *
     * @param object $dto
     * @param object $esc [optional] - shows if the textual values must be escaped before setting to DB
     * @return autogenerated id or -1 if something goes wrong
     */
    public function insertDtos($dtos, $esc = true) {
        //TODO insert items by 1 query
        $id = 0;
        foreach ($dtos as $key => $dto) {
            $id = $this->insertDto($dto, $esc);
        }
        return $id;
    }

    /**
     * Inserts dto into table.
     *
     * @param object $dto
     * @param object $esc [optional] - shows if the textual values must be escaped before setting to DB
     * @return autogenerated id or -1 if something goes wrong
     */
    public function insertDto($dto, $esc = true) {

        //validating input params
        if ($dto == null) {
            throw new Exception("The input param can not be NULL.");
        }

        $dto_fields = array_values($dto->getMapArray());
        $db_fields = array_keys($dto->getMapArray());

        //creating query
        $q = sprintf("INSERT INTO `%s` SET ", $this->getTableName());

        for ($i = 0; $i < count($dto_fields); $i++) {
            $functionName = "get" . ucfirst($dto_fields[$i]);
            $val = $dto->$functionName();
            if (isset($val)) {
                if (is_int($val)) {
                    $q .= sprintf(" `%s` = %d,", $db_fields[$i], $val);
                } else {
                    if ($val == "CURRENT_TIMESTAMP") {

                        $q .= sprintf(" `%s` = %s,", $db_fields[$i], ($esc) ? $this->dbms->escape($val) : $val);
                    } else {

                        $q .= sprintf(" `%s` = '%s',", $db_fields[$i], ($esc) ? $this->dbms->escape($val) : $val);
                    }
                }
            }
        }
        $q = substr($q, 0, -1);
        // Execute query.
        $res = $this->dbms->query($q);
        if ($res) {
            // Get inserted id.
            $result = $this->dbms->getLastInsertedId();
            return $result;
        }
        return -1;
    }

    /**
     * Updates table fields by primary key.
     * DTO must contain primary key value.
     *
     * @param object $dto
     * @param object $esc [optional] shows if the textual values must be escaped before setting to DB
     * @return affected rows count or -1 if something goes wrong
     */
    public function updateByPK($dto, $esc = true) {

        //validating input params
        if ($dto == null) {
            throw new Exception("The input param can not be NULL.");
        }

        $getPKFunc = self::getCorrespondingFunctionName($dto->getMapArray(), $this->getPKFieldName(), "get");
        $pk = $dto->$getPKFunc();
        if (!isset($pk)) {
            throw new Exception("The primary key is not set.");
        }

        $dto_fields = array_values($dto->getMapArray());
        $db_fields = array_keys($dto->getMapArray());

        //creating query
        $q = sprintf("UPDATE `%s` SET ", $this->getTableName());

        for ($i = 0; $i < count($dto_fields); $i++) {
            $functionName = "get" . ucfirst($dto_fields[$i]);
            $val = $dto->$functionName();
            if (isset($val)) {
                if (is_int($val)) {
                    $q .= sprintf(" `%s` = %d,", $db_fields[$i], $val);
                } else {
                    $q .= sprintf(" `%s` = '%s',", $db_fields[$i], ($esc) ? $this->dbms->escape($val) : $val);
                }
            }
        }
        $q = substr($q, 0, -1);
        $q .= sprintf(" WHERE `%s` = %d ", $this->getPKFieldName(), $dto->$getPKFunc());
        return $this->executeUpdate($q);
    }

    /**
     * Executes the query and returns an array of corresponding DTOs
     *
     * @param object $sqlQuery
     * @return
     */
    protected function fetchRows($sqlQuery) {
        // Execute query.

        $res = $this->dbms->query($sqlQuery);

        $resultArr = array();
        if ($res && $this->dbms->getResultCount($res) > 0) {
            $results = $this->dbms->getResultArray($res);
            foreach ($results as $result) {
                $dto = $this->createDto();
                $this->initializeDto($dto, $result);

                $resultArr[] = $dto;
            }
        }
        return $resultArr;
    }

    /**
     * Returns table's field value, which was returnd by query.
     * If query matches more than one rows, the first field will be returnd.
     *
     * @param object $sqlQuery - select query
     * @param object $fieldName - the field name, which was returnd by query
     * @return fieldValue or NULL, if the query doesn't return such field
     */
    protected function fetchField($sqlQuery, $fieldName) {
        // Execute query.
        $res = $this->dbms->query($sqlQuery);

        if ($res && $this->dbms->getResultCount($res) > 0) {
            $results = $this->dbms->getResultArray($res);
            $fieldValue = $results[0][$fieldName];
            if (isset($fieldValue)) {
                return $fieldValue;
            }
        }
        return NULL;
    }

    /**
     * Selects all entries from table
     * @return
     */
    public function selectAll() {
        $sqlQuery = sprintf("SELECT * FROM `%s`", $this->getTableName());

        return $this->fetchRows($sqlQuery);
    }

    /**
     * Selects from table by primary key and returns corresponding DTO
     *
     * @param object $id
     * @return
     */
    public function selectByPK($id) {
        if (is_numeric($id)) {
            $sqlQuery = sprintf("SELECT * FROM `%s` WHERE `%s` = %d ", $this->getTableName(), $this->getPKFieldName(), $id);
        } else {
            $sqlQuery = sprintf("SELECT * FROM `%s` WHERE `%s` = '%s' ", $this->getTableName(), $this->getPKFieldName(), $id);
        }
        $resultArr = $this->fetchRows($sqlQuery);

        if (isset($resultArr[0])) {
            return $resultArr[0];
        }
        return null;
    }

    /**
     * Selects from table by primary key and returns corresponding DTO
     *
     * @param object $id
     * @return
     */
    public function selectByPKs($idsArray) {
        if (is_array($idsArray)) {
            $idsArray = implode(',', $idsArray);
        }
        $sqlQuery = sprintf("SELECT * FROM `%s` WHERE `%s` in (%s) ", $this->getTableName(), $this->getPKFieldName(), $idsArray);
        $resultArr = $this->fetchRows($sqlQuery);
        return $resultArr;
    }

    /**
     * Sets the given field value to mysql CURRENT_TIMESTAMP()
     *
     * @param object $id - primary key value
     * @param object $fieldName - mysql field name
     * @return @see executeUpdate
     */
    public function setCurrentTimestamp($id, $fieldName) {
        $sqlQuery = sprintf("UPDATE `%s` SET `%s` = CURRENT_TIMESTAMP() WHERE `%s` = %d ", $this->getTableName(), $fieldName, $this->getPKFieldName(), $id);
        return $this->executeUpdate($sqlQuery);
    }

    private static function getCorrespondingFunctionName($mapArray, $itemName, $prefix = "set") {
        // Get keys.
        $mapKeys = array_keys($mapArray);
        // Read map items and create correseponding functions.
        foreach ($mapKeys as $itemnameFromMap) {
            //echo("$itemnameFromMap -- $itemName </br>");
            if ($itemnameFromMap == $itemName) {
                // Get value for this item.
                $valueOfMap = $mapArray[$itemnameFromMap];
                // Make first letter uppercase, and add "set".
                $functionName = $prefix . "" . ucfirst($valueOfMap);
                return $functionName;
            }
        }
    }

    protected function getAllFieldsAsQueryString($prefix = false) {
        $dto = $this->createDto();
        $dtoMapArray = $dto->getMapArray();

        $queryString = '';

        if ($prefix == false) {
            foreach ($dtoMapArray as $dbFieldName => $dtoFieldName) {
                $string = sprintf("`%s`.`%s` as `%s`,", $this->getTableName(), $dbFieldName, $dbFieldName);
                $queryString = $queryString . $string;
            }
        } else {
            foreach ($dtoMapArray as $dbFieldName => $dtoFieldName) {
                $string = sprintf("`%s`.`%s` as `%s_%s`,", $this->getTableName(), $dbFieldName, $this->getTableName(), $dbFieldName);
                $queryString = $queryString . $string;
            }
        }

        $queryString = substr($queryString, 0, -1);
        $q = sprintf("SELECT %s FROM `%s`", $queryString, $this->getTableName());

        return $queryString;
    }

}

?>