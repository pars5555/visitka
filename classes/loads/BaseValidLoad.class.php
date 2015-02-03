<?php

require_once (CLASSES_PATH . "/framework/AbstractLoad.class.php");
require_once (CLASSES_PATH . "/managers/LanguageManager.class.php");
require_once (CLASSES_PATH . "/managers/CmsSettingsManager.class.php");
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SendchatLoad
 *
 * @author Administrator
 */
abstract class BaseValidLoad extends AbstractLoad {

    public function initialize($sessionManager, $config, $loadMapper, $args) {
        parent::initialize($sessionManager, $config, $loadMapper, $args);
        $lm = LanguageManager::getInstance($this->config, $this->args);
        $this->addParam("lm", $lm);
        $allVarsArray = CmsSettingsManager::getInstance()->getAllVarsArray();
        $this->addParam("cms_vars", json_encode($allVarsArray));
        $userLevel = $this->getUserLevel();
        $this->addParam('userLevel', $userLevel);
        $this->addParam('userGroupsUser', UserGroups::$USER);
        $this->addParam('userGroupsGuest', UserGroups::$GUEST);
        $this->addParam('userGroupsAdmin', UserGroups::$ADMIN);
        $this->addParam("load_name", isset($_REQUEST['page'])?$_REQUEST['page']:"");
    }

    public function getDefaultLoads($args) {
        $loads = array();
        return $loads;
    }

    protected function initErrorMessages() {
        if (!empty($_SESSION['error_message'])) {
            $reror_message = $this->secure($_SESSION['error_message']);
            $this->addParam('error_message', $reror_message);
            unset($_SESSION['error_message']);
        }
    }

    protected function initSucessMessages() {
        if (!empty($_SESSION['success_message'])) {
            $reror_message = $this->secure($_SESSION['success_message']);
            $this->addParam('success_message', $reror_message);
            unset($_SESSION['success_message']);
        }
    }

    public function isValidLoad($namespace, $load) {
        return true;
    }

}

?>