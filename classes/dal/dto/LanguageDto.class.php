<?php

require_once (CLASSES_PATH . "/framework/dal/dto/AbstractDto.class.php");

/**
 * AdminDto class is extended class from AbstractDto.
 *
 * @author	Vahagn Sookiasian
 */
class LanguageDto extends AbstractDto {

	// Map of DB value to Field value
	protected $mapArray = array("id" => "id", "phrase_en" => "phraseEn", "phrase_am" => "phraseAm", "phrase_ru" => "phraseRu");

	// constructs class instance
	public function __construct() {
		
	}

	// returns map array
	public function getMapArray() {
		return $this->mapArray;
	}

}

?>
