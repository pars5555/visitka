<?php

/**
 * ImprovedDBMS class uses MySQL Improved Extension to access DB.
 * This class provides full transaction support instead of DBMS class.
 * 
 * @author  Naghashyan Solutions, e-mail: info@naghashyan.com
 * @version 1.0
 */
class ImprovedDBMS {
	
	/**
	 * Object which represents the connection to a MySQL Server 
	 */
	private $link;
	
	/**
	 * Tries to connect to a MySQL Server 
	 */
	public function __construct() {
		$this->link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		mysqli_set_charset ($this->link,  "utf8" );
		if (!$this->link) {
		    printf("Connect failed: %s\n", mysqli_connect_error());
		    exit();
		}
	}

	
//////////////////////////// transaction support //////////////////////
	
	/**
	 *	Turns off auto-commiting database modifications
	 */
	public function startTransaction(){
		mysqli_autocommit($this->link, FALSE);
	}
	
	/**
	 * Commits the current transaction
	 */
	public function commitTransaction(){
		mysqli_commit($this->link);
		mysqli_autocommit($this->link, TRUE);
	}
	
	/**
	 * Rollback the current transaction
	 */
	public function rollbackTransaction(){
		mysqli_rollback($this->link);
		mysqli_autocommit($this->link, TRUE);
	}
	
/////////////////////////// common functions /////////////////////////
	
	/**
	 * Returns TRUE on success or FALSE on failure. 
	 * For SELECT, SHOW, DESCRIBE or EXPLAIN will return a result object. 
	 */
	public function query($q) {
		$result = mysqli_query($this->link, $q);
		return $result;
	}
	
	/**
	 * Frees the memory associated with the result object, 
	 * which was returnd by query() function. 
	 * 
	 * @param object $result - object returnd by query() function
	 * @return 
	 */
	public function freeResult($result){
		mysqli_free_result($result);
	}
	
	/**
	 * Returns the auto generated id used in the last query
	 * 
	 * @return The value of the AUTO_INCREMENT field that was updated by the previous query. 
	 * Returns zero if there was no previous query on the connection or 
	 * if the query did not update an AUTO_INCREMENT value. 
	 */
	public function getLastInsertedId() {
		return mysqli_insert_id($this->link);
	}
	
	/**
	 * Gets the number of affected rows in a previous MySQL operation
	 * An integer greater than zero indicates the number of rows affected or retrieved. 
	 * Zero indicates that no records where updated for an UPDATE statement, 
	 * no rows matched the WHERE clause in the query or that no query has yet been executed. 
	 * -1 indicates that the query returned an error. 
	 */
	public function getAffectedRows() {
		return mysqli_affected_rows($this->link);
	}
	

	private function Iamdead($q, $er) {
		$url="http://".getenv("SERVER_NAME").getenv("REQUEST_URI");
		echo $msg="<pre>
				MYSQL Error was encountered:\n
				$er\n\nWhile proccessing the query:\n=======\n$q\n========\n\n
				on the address: $url\n\n
				Please fix it
				</pre>";
		//mail(MASTER_EMAIL,"Error on DLP Site",$msg,"From: ".FROM_EMAIL);
		//Header("Location: /er.php");
		exit();
	}
	
	/**
	 * Fetch a result row as an associative array
	 */
	public function getResultArray($res) {
		$results = array();
		if($res) {
			while ($t = mysqli_fetch_assoc($res)) {
				$results[] = $t;
			}
			return $results;
		} else {
			die("Wrong resource");
		}
	}
	
	/**
	 * Gets the number of rows in a result 
	 */
	public function getResultCount($res){
		if ($res){
			return mysqli_num_rows($res);
		}
		return false;
	}
	
	/**
	 * Escapes special characters in a string for use in a SQL statement, 
	 * taking into account the current charset of the connection 
	 * 
	 * @return an escaped string. 
	 */
	public function escape($str, $trim = false) {
		if($trim){
			$str = trim($str);
		}
		return function_exists('mysqli_real_escape_string') ? mysqli_real_escape_string($this->link, $str) : $str;
	}
}

?>