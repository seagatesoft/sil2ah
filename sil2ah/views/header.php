<?php if (!defined('ON_ROOT')) { include_once 'views/404.php'; die();}?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $siteTitle;?></title>
    <meta charset="utf-8">
    <link type="text/css" rel="stylesheet" href="assets/css/main.css">
    <link rel="icon" href="assets/images/favicon.ico">
</head>
<body>
<?php if ($isUserLoggedIn):?>
<nav id="mainMenu">
  <ul>
    <li><a href="index.php?module=person&action=main">Pohon Silsilah</a></li>
    <li><a href="index.php?module=admin&action=settings">Pengaturan</a></li>
    <li><a href="index.php?module=admin&action=change_password">Ganti Password</a></li>
    <li><a href="index.php?module=user&action=logout">Keluar</a></li>
  </ul>
</nav>
<?php endif;?>
<h1><?php echo $siteTitle;?></h1>
<div id="messageArea"><?php if (isset($message)) { echo $message;}?></div>
