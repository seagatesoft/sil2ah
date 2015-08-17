<?php
if (!defined('ON_ROOT')) { include_once 'views/404.php'; die();}

require_once 'controllers/base.php';

class SettingsController extends BaseController {
  var $models = array(
    'setting' => 'Setting',
    'person' => 'Person',
  );

  public function doGetAction() {
    $this->setView('admin/settings');
  }

  public function doPostAction() {
    $this->Setting->updateSiteTitle($_POST['siteTitle']);
    $this->setView('admin/settings');
    $this->setMessage('Pengaturan berhasil disimpan.');
  }
}
