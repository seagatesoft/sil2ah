<?php if (!defined('ON_ROOT')) { include_once 'views/404.php'; die();}?>
<?php include 'views/header.php';?>
<form method="post" action="index.php?module=user&action=login">
  <fieldset id="loginFields">
    <label for="username">User Name</label>
    <input id="username" type="text" name="username">
    <label for="password">Password</label>
    <input id="password" type="password" name="password">
    <input id="loginButton" type="submit" value="Login">
  </fieldset>
</form>
<?php include 'views/footer.php';?>
