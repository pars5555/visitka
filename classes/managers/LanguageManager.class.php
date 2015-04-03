<?php

require_once (FRAMEWORK_PATH . "/AbstractManager.class.php");
require_once (CLASSES_PATH . "/dal/mappers/LanguageMapper.class.php");

/**
 * UserManager class is responsible for creating,
 *
 * @author Vahagn Sookiasian
 * @package managers
 * @version 1.0
 */
class LanguageManager extends AbstractManager {

   
    private $allPhrases;
    private $allPhrasesDtosMappedById;
    private $allPhrasesDtosMappedByPhraseEn;

  
    /**
     * @var singleton instance of class
     */
    private static $instance = null;

    /**
     * Initializes DB mappers
   
     */
    function __construct() {
        $this->mapper = LanguageMapper::getInstance();


        $this->initAllPhrases();
    }

    /**
     * Returns an singleton instance of this class
    
     * @return
     */
    public static function getInstance() {

        if (self::$instance == null) {
            self::$instance = new LanguageManager();
        }
        return self::$instance;
    }

    public function getPhrase($phraseFormula, $lang_code = null, $transform = 0) {

        $lc = 'en';
        if (!empty($lang_code) && ($lang_code == 'en' || $lang_code == 'am' || $lang_code == 'ru')) {
            $lc = $lang_code;
        } elseif (!empty($_COOKIE['ul']) && ($_COOKIE['ul'] == 'en' || $_COOKIE['ul'] == 'am' || $_COOKIE['ul'] == 'ru')) {
            $lc = $_COOKIE['ul'];
        } else {
            $lc = 'en';
        }
        $ret = $phraseFormula;
        if (strpos($phraseFormula, '`') !== false) {
            $pid = $this->getPhraseFormulaFirstPhraseId($ret);
            while ($pid > 0) {
                $ret = str_replace("`" . $pid . "`", $this->allPhrases[$pid][$lc], $ret);
                $pid = $this->getPhraseFormulaFirstPhraseId($ret);
            }
        } else {
            if (array_key_exists($phraseFormula, $this->allPhrases)) {
                $ret = $this->allPhrases[$phraseFormula][$lc];
            } else {
                $ret = null;
            }
        }
        switch ($transform) {
            case 1:
                return isset($ret)?mb_strtolower($ret):null;
            case 2:
                return isset($ret)?mb_strtoupper($ret):null;
            case 3:
                return isset($ret)?$this->mb_ucfirst($ret):null;
            default:
                return $ret;
        }
    }

    private function getPhraseFormulaFirstPhraseId($phraseFormula) {
        $firstPos = strpos($phraseFormula, '`');
        $secondPos = strpos($phraseFormula, '`', $firstPos + 1);
        if ($firstPos !== false && $secondPos !== false && $secondPos > $firstPos) {
            return intval(substr($phraseFormula, $firstPos + 1, $secondPos - $firstPos - 1));
        }
        return 0;
    }

    public static function mb_ucfirst($str, $encoding = "UTF-8", $lower_str_end = false) {
        $first_letter = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding);
        $str_end = "";
        if ($lower_str_end) {
            $str_end = mb_strtolower(mb_substr($str, 1, mb_strlen($str, $encoding), $encoding), $encoding);
        } else {
            $str_end = mb_substr($str, 1, mb_strlen($str, $encoding), $encoding);
        }
        $str = $first_letter . $str_end;
        return $str;
    }

    public function getPhraseSpan($id, $lang_code = null) {
        return "<span class='replaceable_lang_element' phrase_id='" . $id . "'>" . $this->getPhrase($id, $lang_code) . "</span>";
    }

    public function getAllPhrases() {
        return $this->allPhrases;
    }

    public function getAllPhrasesDtosMappedById() {
        return $this->allPhrasesDtosMappedById;
    }

    private function initAllPhrases() {

        $this->allPhrases = array();
        $this->allPhrasesDtosMappedById = array();
        $this->allPhrasesDtosMappedByPhraseEn = array();
        $allp = $this->mapper->selectAll();
        foreach ($allp as $key => $pdto) {
            $this->allPhrases[$pdto->getId()]['en'] = $pdto->getPhraseEn();
            $this->allPhrases[$pdto->getId()]['am'] = (strlen($pdto->getPhraseAm()) > 0 ? $pdto->getPhraseAm() : $pdto->getPhraseEn());
            $this->allPhrases[$pdto->getId()]['ru'] = (strlen($pdto->getPhraseRu()) > 0 ? $pdto->getPhraseRu() : $pdto->getPhraseEn());
            $this->allPhrasesDtosMappedByPhraseEn[$pdto->getPhraseEn()] = $pdto;
            $this->allPhrasesDtosMappedById[$pdto->getId()] = $pdto->toArray();
        }
    }

    public function updatePhrase($id, $phrase) {
        if (!empty($_COOKIE['ul']) && ($_COOKIE['ul'] == 'en' || $_COOKIE['ul'] == 'am' || $_COOKIE['ul'] == 'ru')) {
            $lc = $_COOKIE['ul'];
            $columnName = "phrase_" . $lc;
            $this->mapper->updateTextField($id, $columnName, $phrase);
        }
    }

    public function getPhraseIdByPhraseEn($phraseEn) {
        if (array_key_exists($phraseEn, $this->allPhrasesDtosMappedByPhraseEn)) {
            $dto = $this->allPhrasesDtosMappedByPhraseEn[$phraseEn];
            return $dto->getId();
        }
        if (array_key_exists(ucfirst($phraseEn), $this->allPhrasesDtosMappedByPhraseEn)) {
            $dto = $this->allPhrasesDtosMappedByPhraseEn[ucfirst($phraseEn)];
            return $dto->getId();
        }
        return null;
    }

}

?>