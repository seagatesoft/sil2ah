<?php
if (!defined('ON_ROOT')) { include_once 'views/404.php'; die();}

require_once 'controllers/base.php';
require_once 'utils/bcrypt.php';

class LoginController extends BaseController {
  var $models = array(
    'setting' => 'Setting',
    'user' => 'User',
    'person' => 'Person'
  );

  public function beforePostAction() {
    return !$this->isUserLoggedIn();
  }

  public function onBeforePostActionFalse() {
    $this->sendMainPage();
  }

  public function doPostAction() {
    $username = $_POST['username'];
    $bcrypt = new Bcrypt(15);

    if ($bcrypt->verify($_POST['password'], $this->User->getUserPassword($username))) {
      $_SESSION['username'] = $username;
      $this->sendMainPage();
    } else {
      $this->setMessage('Data login Anda salah!');
      $this->sendLoginPage();
    }
  }
}
