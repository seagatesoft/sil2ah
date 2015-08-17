<?php if (!defined('ON_ROOT')) { include_once 'views/404.php'; die();}?>
<?php include 'views/header.php';?>
<form method="post" action="index.php?module=admin&action=change_password">
  <fieldset>
    <h3>Ganti Password</h3>
    <label for="old_password">Password Lama</label>
    <input id="old_password" type="password" name="old_password">
    <label for="new_password">Password Baru</label>
    <input id="new_password" type="password" name="new_password">
    <label for="new_password_verification">Verifikasi Password Baru</label>
    <input id="new_password_verification" type="password" name="new_password_verification">
    <input id="installButton" type="submit" value="Ganti">
  </fieldset>
</form>
<?php include 'views/footer.php';?>
