<?php
if (!defined('ON_ROOT')) { include_once 'views/404.php'; die();}

require_once 'controllers/base.php';

class ViewController extends BaseController {
  var $models = array(
    'setting' => 'Setting',
    'person' => 'Person',
    'person_attributes' => 'PersonAttributes',
  );

  public function doGetAction() {
    $this->sendViewPersonPage($_GET['uuid']);
  }
}
