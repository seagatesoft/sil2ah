<?php
if (!defined('ON_ROOT')) { include_once 'views/404.php'; die();}

require_once 'controllers/base.php';

class MainController extends BaseController {
  var $models = array(
    'setting' => 'Setting',
    'person' => 'Person',
  );

  public function doGetAction() {
    $this->sendMainPage();
  }
}
