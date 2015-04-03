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

    public function initialize($sessionManager, $loadMapper, $args) {
        parent::initialize($sessionManager, $loadMapper, $args);
        $lm = LanguageManager::getInstance();
        $this->addParam("lm", $lm);
        $userLevel = $this->getUserLevel();
        $customer = $this->getCustomer();
        $this->addParam('DOCUMENT_ROOT', DOCUMENT_ROOT);
        $this->addParam('customer', $customer);
        $this->addParam('userLevel', $userLevel);
        $this->addParam('userId', $this->getUserId());
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
            $message = $this->secure($_SESSION['error_message']);
            $this->addParam('error_message', $message);
            unset($_SESSION['error_message']);
        }
    }

    protected function initSucessMessages() {
        if (!empty($_SESSION['success_message'])) {
            $message = $this->secure($_SESSION['success_message']);
            $this->addParam('success_message', $message);
            unset($_SESSION['success_message']);
        }
    }

    public function isValidLoad($namespace, $load) {
        return true;
    }

}

?>