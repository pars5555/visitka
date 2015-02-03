<?php

/**
 * @author  Naghashyan Solution, e-mail: info@naghashyan.com
 * @version 1.0
 * Default constants 
 * 
 */
date_default_timezone_set('Asia/Yerevan');
mb_internal_encoding('UTF-8');

define('HTTP_PROTOCOL', isset($_SERVER["HTTPS"]) ? "https://" : "http://");

define("HTTP_HOST", $_SERVER["HTTP_HOST"] . "");
define('DOMAIN', get_domain(HTTP_HOST));

//---defining document root
define("DOCUMENT_ROOT", $_SERVER["DOCUMENT_ROOT"]);

//---defining HOMEPAGE ROOT root
$ngsRoot = DOCUMENT_ROOT;
define("NGS_ROOT", $ngsRoot);

//---defining smarty root
define("SMARTY_DIR", NGS_ROOT . "/classes/lib/smarty/");

//---defining config file path
define("CONF_PATH", NGS_ROOT . "/conf");

//---defining classes paths
define("CLASSES_PATH", NGS_ROOT . "/classes");

//---defining framework path
define("FRAMEWORK_PATH", CLASSES_PATH . "/framework");

//---defining smarty paths

define("TEMPLATES_DIR", NGS_ROOT . "/templates");
define("CACHE_DIR", TEMPLATES_DIR . "/cache");
define("COMPILE_DIR", TEMPLATES_DIR . "/compile");
define("CONFIG_DIR", TEMPLATES_DIR . "/config");

//---defining data dir path
define("DATA_DIR", NGS_ROOT . "/data");

//---defining temp dir path
define("TEMP_DIR", NGS_ROOT . "/tmp");


//---defining data bin path
define("BIN_DIR", NGS_ROOT . "/bin");

//---defining interface images dir
define("IMG_ROOT_DIR", NGS_ROOT . "/htdocs/img");
define("CSS_ROOT_DIR", NGS_ROOT . "/htdocs/css");

define("DBMS", CLASSES_PATH."/util/db/ImprovedDBMS.class.php");
define("CONFIG_INI", CONF_PATH . "/config.ini");

define("LOAD_MAPPER", CLASSES_PATH."/loads/LoadMapper.class.php");
define("SESSION_MANAGER", CLASSES_PATH."/managers/SessionManager.class.php");

//defining load and action directories
define("LOADS_DIR", "loads");
define("ACTIONS_DIR", "actions");



function get_domain($url) {
    if (substr($url, 0, 4) !== 'http') {
        $url = 'http://' . $url;
    }
    $pieces = parse_url($url);
    $domain = isset($pieces['host']) ? $pieces['host'] : '';

    if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
        return $regs['domain'];
    }
    return false;
}

?>