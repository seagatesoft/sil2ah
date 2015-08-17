<?php
if (!defined('ON_ROOT')) { include_once 'views/404.php'; die();}

class User extends Model {
  var $table = 'users';

  public function getUserPassword($username) {
    $row = $this->selectFirst('password', array(array('username', '=', $username)));

    if ($row) {
      return $row['password'];
    } else {
      return false;
    }
  }
}
