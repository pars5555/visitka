<?php

/**
 * <p><b>AbstractManager class</b> is a base class for all manager classes.</p>
 * 
 * @author  Naghashyan Solutions, e-mail: info@naghashyan.com
 * @version 1.0
 * @package framework
 */
abstract class AbstractManager {

    /**
     * @abstract  
     * @access
     * @param 
     * @return
     */
    public function __construct() {
        
    }

    /**
     * Return a thing based on $dataObject, $paramsArray parameters
     * @abstract  
     * @access
     * @param $dataObject, $paramsArray
     * @return true
     */
    public function validateMustBeParameters($dataObject, $paramsArray) {
        foreach ($paramsArray as $param) {
            $functionName = "get" . ucfirst($param);
            $paramValue = $dataObject->$functionName();
            if ($paramValue == null || $paramValue == "") {
                throw new Exception("The parameter " . $param . " is missing.");
            }
        }
        return true;
    }

    public function secure($var) {
        if (isset($var)) {
            return trim(htmlspecialchars(strip_tags($var)));
        } else {
            return null;
        }
    }

    public function getFieldKeyByFieldName($fname) {
        $mapper = $this->getMapper();
        $dto = $mapper->createDto();
        $mapArray = $dto->getMapArray();
        return array_search($fname, $mapArray);
    }

    public function getCmsVar($var) {

        return CmsSettingsManager::getInstance()->getValue($var);
    }

    public function getPhrase($phrase_id, $ul = null) {
        return LanguageManager::getInstance(null, null)->getPhrase($phrase_id, $ul);
    }

    public function getPhraseSpan($phrase_id, $ul = null) {
        return LanguageManager::getInstance(null, null)->getPhraseSpan($phrase_id, $ul);
    }

    public function getPhrases($phraseIds, $ul = null) {
        $ret = array();
        foreach ($phraseIds as $pid) {
            $ret[] = $this->getPhrase($pid, $ul);
        }
        return $ret;
    }

    public function getMapper() {
        return $this->mapper;
    }

    public function getLastSelectFoundRows() {
        return $this->mapper->getLastSelectFoundRows();
    }

    public function lockTableWrite() {
        $this->mapper->lockTableWrite();
    }

    public function unlockTables() {
        $this->mapper->unlockTables();
    }

    public function selectByField($fieldName, $fieldValue) {
        return $this->mapper->selectByField($fieldName, $fieldValue);
    }

    public function selectByFields($fieldNamesValuesMap) {
        return $this->mapper->selectByFields($fieldNamesValuesMap);
    }

    public function deleteByField($fieldName, $fieldValue) {
        return $this->mapper->deleteByField($fieldName, $fieldValue);
    }

    private $selectByPkCache = null;
    public function selectByPK($pk, $cache = false) {
        if (!isset($this->selectByPkCache) || !$cache) {
            $this->selectByPkCache = $this->mapper->selectByPK($pk);
        }
        return $this->selectByPkCache;
    }

    public function selectByPKs($pks) {
        return $this->mapper->selectByPKs($pks);
    }

    public function createDto() {
        return $this->mapper->createDto();
    }

    public function insertDto($dto) {
        return $this->mapper->insertDto($dto);
    }

    public function updateByPk($dto, $esc = true) {
        return $this->mapper->updateByPk($dto, $esc);
    }

    public function updateTextField($id, $fieldName, $fieldValue, $esc = true) {
        return $this->mapper->updateTextField($id, $fieldName, $fieldValue, $esc);
    }

    public function updateNumericField($id, $fieldName, $fieldValue) {
        return $this->mapper->updateNumericField($id, $fieldName, $fieldValue);
    }

    public function deleteByPK($id) {
        return $this->mapper->deleteByPK($id);
    }

    public function selectAll() {
        return $this->mapper->selectAll();
    }

}

?>