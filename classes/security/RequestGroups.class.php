<?php

/**
 * 
 * Using for exmaple:
 * $adminsRequest variable giving permission to  actions,loads file as a Administrator.
 * $guestRequest variable you is giving permission to the actions,loads as a Guest User.
 * 
 * The logic for this user is written in SessionManager.class.php file.
 * 
 * @author  Naghashyan Solutions, e-mail: info@naghashyan.com
 * @version 1.0
 * @package security
 */
class RequestGroups {

    public static $adminRequest = 0;
    public static $userRequest = 2; 
    public static $guestRequest = 14;
    public static $systemRequest = 16;

}

?>