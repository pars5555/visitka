<?php

class NgsException extends Exception {
    
	public static $ERRORS = array(
		"LOGIN_REQUIRED" => array("code" => 90, "message" => "Please log in"),
        'WRONG_PARAMS_LOGIN' => array("code" => 101, "message" => "Wrong params sent to login"),
        'NOT_NUMERIC_FB_ID' => array("code" => 102, "message" => "Not numeric Facebook id"),
        'WRONG_FB_USERNAME' => array("code" => 103, "message" => "Wrong Facebook username"),
        'WRONG_FB_EMAIL' => array("code" => 104, "message" => "Wrong Facebook email"),
        'WRONG_FB_TOKEN' => array("code" => 105, "message" => "Wrong Facebook token"),
        'INVALID_FB_TOKEN_ID_COMB' => array("code" => 106, "message" => "Invalid Facebook token and id combination"),
        'INVALID_EMAIL' => array("code" => 107, "message" => "Invalid Email"),
        'SPECIFY_USERNAME' => array("code" => 108, "message" => "Please specify username"),
        'USER_EXIST' => array("code" => 109, "message" => "User already exist"),
        'WRONG_USER_ID_SUPPLIED' => array("code" => 110, "message" => "Wrong user id was supplied"),
        'INVALID_WITH_FB_ID' => array("code" => 111, "message" => "Invalid Facebook id"),
        'INVALID_WITH_EMAIL_USERNAME' => array("code" => 112, "message" => "Invalid Email or username"),
        'INVALID_FB_TOKEN' => array("code" => 113, "message" => "Invalid Facebook token"),
        'INVALID_FB_ID' => array("code" => 114, "message" => "Invalid Facebook id"),
        'INVALID_FB_EMAIL' => array("code" => 115, "message" => "Invalid Facebook email"),
        'INVALID_FB_USERNAME' => array("code" => 116, "message" => "Invalid Facebook username"),
        'INVALID_USERNAME' => array("code" => 117, "message" => "Invalid username"),
        'INVALID_OLDPASSWORD' => array("code" => 118, "message" => "Invalid old password"),
        'INVALID_NEWPASSWORD' => array("code" => 119, "message" => "Invalid new password"),
        'INVALID_USERNAME_PASSWORD' => array("code" => 120, "message" => "Invalid username or password"),
        'INVALID_GAME' => array("code" => 121, "message" => "Invalid game"),
        'INVALID_APP_ID' => array("code" => 122, "message" => "Invalid App ID"),
        'INVALID_APP_VERSION' => array("code" => 123, "message" => "Invalid app version"),
        'INVALID_DEVICE_ID' => array("code" => 124, "message" => "Invalid device id"),
        'INVALID_DEVICE_TOKEN' => array("code" => 125, "message" => "Invalid Device token"),
        'SHORT_PASSWORD' => array("code" => 126, "message" => "Password lenght should be more than 6 chars"),
        'SPECIFY_USERNAME_EMAIL' => array("code" => 127, "message" => "Please specify either username or email"),
        'INVALID_CHAT_ALERTS' => array("code" => 128, "message" => "Invalid ChatAlerts option"),
        'INVALID_ACCEPT_RANDOM' => array("code" => 129, "message" => "Invalid AcceptRandom option"),
        'WRONG_EMAIL_PASSWORD_SUPPLIED' => array("code" => 130, "message" => "Wrong email or password was supplied"),
        'INVALID_MOVEMENT' => array("code" => 131, "message" => "invalid movement, opponent's turn"),
        'INVALID_OPPONENT' => array("code" => 132, "message" => "Invalid opponent"),
		'INVALID_GAME_ID' => array("code" => 133, "message" => "Invalid Game id"),
        'GAME_NOT_EXIST' => array("code" => 134, "message" => "Game does not exist"),
        'IVALID_LETTERS_USED' => array("code" => 135, "message" => "Invalid letters used"),
        'INVALID_POINTS' => array("code" => 136, "message" => "Invalid points"),
        'INVALID_LETTERS_SENT' => array("code" => 137, "message" => "Invalid letters sent"),
        'INVALID_SESSION' => array("code" => 138, "message" => "Invalid user session"),
        'NO_DICTIONARY' => array("code" => 139, "message" => "No dictionary"),
        'NEW_OLD_PASSWORD_DIFFERENCE' => array("code" => 140, "message" => "New password should differ from the old one"),
        'WRONG_GAME_ID_SUPPLIED' => array("code" => 141, "message" => "Wrong game id was supplied"),
        'INVALID_LETTERS_COORDS' => array("code" => 142, "message" => "Invalid letters coordinates were supplied"),
        'FINISHED_GAME' => array("code" => 143, "message" => "Game is finished"),
        'INVALID_USER_ID' => array("code" => 144, "message" => "Invalid user id"),
        'INVALID_LETTERS' => array("code" => 145, "message" => "Invalid Letters"),
		'INVALID_MESSAGE' => array("code" => 146, "message" => "Invalid message"),
        'GAME_NOT_PLAYED_BY_USER' => array("code" => 147, "message" => "Game is not played by the user"),
        'CHAT_WAS_NOT_SENT' => array("code" => 148, "message" => "Chat message wasn't sent"),
        'INVALID_UPTO_ID' => array("code" => 149, "message" => "Invalid upto id"),
        'INVALID_ITEM_ID' => array("code" => 150, "message" => "Invalid item id"),
        'GAME_IS_DELETED' => array("code" => 151, "message" => "Game is deleted")
	);
	
    /**
     * Return a thing based on $message, $code parameters
     * @abstract  
     * @access
     * @param boolean $message, $code parameter 
     * @return
     */
    public function __construct($exception) {
        parent::__construct($exception['message'], $exception['code']);
    }

}

?>