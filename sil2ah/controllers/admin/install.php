<?php
if (!defined('ON_ROOT')) { include_once 'views/404.php'; die();}

require_once 'controllers/base.php';
require_once 'utils/bcrypt.php';

class InstallController extends BaseController {
  var $models = array(
    'setting' => 'Setting',
    'user' => 'User',
    'person' => 'Person',
  );

  public function beforeAction() {
    return true;
  }

  public function doGetAction() {
    if ($this->Setting->getSuperAdminUserName()) {
      if ($this->isUserLoggedIn()) {
        $this->sendMainPage();
      } else {
        $this->sendLoginPage();
      }
    } else {
      $this->setView('admin/install');
      $this->setSiteTitle($this->Setting->getSiteTitle() . ' - Install');
    }
  }

  public function doPostAction() {
    if ($this->Setting->getSuperAdminUserName()) {
      if ($this->isUserLoggedIn()) {
        $this->sendMainPage();
      } else {
        $this->sendLoginPage();
      }
    } else {
      try {
        $this->dbSource->startTransaction();
        $this->Setting->insertSiteTitle($_POST['siteTitle']);

        $bcrypt = new Bcrypt(15);
        $superuser = array(
          'username' => $_POST['username'],
          'password' => $bcrypt->hash($_POST['password'])
        );
        $this->User->insert($superuser);
        $this->Setting->insertSuperAdminUserName($superuser['username']);

        $rootAncestor = array(
          'uuid' => uniqid(),
          'name' => $_POST['rootAncestorName'],
          'gender' => $_POST['rootAncestorGender'],
          'person_type' => 'R',
        );
        $this->Person->insert($rootAncestor);
        $this->Setting->insertRootAncestorUuid($rootAncestor['uuid']);
        $this->dbSource->commit();

        $_SESSION['username'] = $superuser['username'];

        $this->setMessage('Instalasi berhasil.');
        $this->sendMainPage();
      } catch(Exception $e) {
        $this->dbSource->rollback();
        $this->setMessage('Instalasi gagal: ' . $e->getMessage());
      }
    }
  }
}
