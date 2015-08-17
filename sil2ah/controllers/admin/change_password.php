<?php
if (!defined('ON_ROOT')) { include_once 'views/404.php'; die();}

require_once 'controllers/base.php';
require_once 'utils/bcrypt.php';

class ChangePasswordController extends BaseController {
  var $models = array(
    'setting' => 'Setting',
    'user' => 'User',
    'person' => 'Person',
  );

  public function doGetAction() {
    $this->setView('admin/change_password');
  }

  public function doPostAction() {
    $bcrypt = new Bcrypt(15);
    $this->setView('admin/change_password');

    try {
      $username = $this->Setting->getSuperAdminUserName();

      if (
        $bcrypt->verify($_POST['old_password'], $this->User->getUserPassword($username)) &&
        $_POST['new_password'] === $_POST['new_password_verification']
      ) {
        $superuser = array(
          'username' => $username,
          'password' => $bcrypt->hash($_POST['new_password'])
        );
        $this->User->updateRecord($superuser);
        $this->setMessage('Password berhasil diganti.');
      } else {
        $this->setMessage('Password yang Anda masukkan tidak sama!');
      }
    }
    catch(Exception $e) {
      $this->setMessage('Password gagal diganti: ' . $e->getMessage());
    }
  }
}
