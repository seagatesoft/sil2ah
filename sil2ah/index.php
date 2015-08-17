<?php
define("ON_ROOT", true);
require_once "core-settings.php";
require_once "route-settings.php";

session_start();

if (
  array_key_exists('module', $_GET) &&
  array_key_exists('action', $_GET) &&
  array_key_exists($_GET['module'], $ROUTES) &&
  array_key_exists($_GET['action'], $ROUTES[$_GET['module']])
) {
  $module = $_GET['module'];
  $action = $_GET['action'];
  $controller_setting = $ROUTES[$module][$action];

  if (in_array($_SERVER['REQUEST_METHOD'], $controller_setting['methods'])) {
    require_once "controllers/$module/$action.php";
    $controller = new $controller_setting['controller']();

    switch ($_SERVER['REQUEST_METHOD']) {
      case 'GET':
        $controller->handleGet();
        break;
      case 'POST':
        $controller->handlePost();
        break;
    }
  } else {
    include 'views/405.php';
  }
} else if ('GET' == $_SERVER['REQUEST_METHOD'] && empty($_GET)) {
  require_once "controllers/admin/install.php";
  $controller = new InstallController();
  $controller->handleGet();
} else {
  include 'views/404.php';
}
