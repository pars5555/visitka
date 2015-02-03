<?php

require_once(CLASSES_PATH . "/framework/AbstractAction.class.php");
require_once(CLASSES_PATH . "/managers/UsersManager.class.php");
require_once(CLASSES_PATH . "/managers/SessionManager.class.php");

/**
 * @author Karen Manukyan
 */
class LoginAction extends AbstractAction {

    public function service() {

        $username = $this->secure($_REQUEST['username']);
        $password = $this->secure($_REQUEST['password']);
        
        $usersManager = UsersManager::getInstance();
        $userDto = $usersManager->getByLoginPassword($username, $password);        
        if ($userDto) {
            $user = new CustomerUser($userDto->getId());  
            $user->setUniqueId($userDto->getHash());
            $this->sessionManager->setUser($user, true, true);
        } else {
            $_SESSION['error_message'] = 'Wrong Login/Password!';
            $this->redirect('login');
        }
        $this->redirect('');
    }

    public function getRequestGroup() {
        return RequestGroups::$guestRequest;
    }

}

?>