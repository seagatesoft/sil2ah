<?php if (!defined('ON_ROOT')) { include_once 'views/404.php'; die();}?>
<?php include 'views/header.php';?>
<form method="post" action="index.php?module=admin&action=install">
  <fieldset>
    <h3>Pengaturan Situs</h3>
    <label for="siteTitle">Judul Situs</label>
    <input id="siteTitle" type="text" name="siteTitle">
    <h3>Pengaturan Super Admin</h3>
    <label for="username">User Name</label>
    <input id="username" type="text" name="username">
    <label for="password">Password</label>
    <input id="password" type="password" name="password">
    <h3>Akar Pohon Silsilah</h3>
    <label for="rootAncestorName">Nama</label>
    <input id="rootAncestorName" type="text" name="rootAncestorName">
    <label for="rootAncestorGender">Jenis Kelamin</label>
    <select id="rootAncestorGender" name="rootAncestorGender">
      <option value="M">Pria</option>
      <option value="F">Wanita</option>
    </select>
    <input id="installButton" type="submit" value="Install">
  </fieldset>
</form>
<?php include 'views/footer.php';?>
