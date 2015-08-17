<?php
if (!defined('ON_ROOT')) { include_once 'views/404.php'; die();}

require_once 'controllers/base.php';

class LogoutController extends BaseController {
  var $models = array(
    'setting' => 'Setting',
    'user' => 'User'
  );

  public function doGetAction() {
    session_destroy();
    $this->sendLoginPage();
  }
}
