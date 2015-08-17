<?php
if (!defined('ON_ROOT')) { include 'views/404.php'; die();}

$ROUTES = array(
  'admin' => array(
    'install' => array(
      'controller' =>'InstallController',
      'methods' => array('GET', 'POST'),
    ),
    'settings' => array(
      'controller' => 'SettingsController',
      'methods' => array('GET', 'POST'),
    ),
    'change_password' => array(
      'controller' => 'ChangePasswordController',
      'methods' => array('GET', 'POST'),
    ),
  ),
  'user' => array(
    'login' => array(
      'controller' => 'LoginController',
       'methods' => array('POST'),
    ),
    'logout' => array(
      'controller' => 'LogoutController',
      'methods' => array('GET'),
    ),
  ),
  'person' => array(
    'main' => array(
      'controller' => 'MainController',
      'methods' => array('GET'),
    ),
    'view' => array(
      'controller' => 'ViewController',
      'methods' => array('GET')
    ),
    'add_spouse' => array(
      'controller' => 'AddSpouseController',
      'methods' => array('GET', 'POST'),
    ),
    'add_child' => array(
      'controller' => 'AddChildController',
      'methods' => array('GET', 'POST')
    ),
    'delete' => array(
      'controller' => 'DeleteController',
      'methods' => array('GET')
    ),
    'edit' => array(
      'controller' => 'EditController',
      'methods' => array('GET', 'POST'),
    ),
  ),
);
