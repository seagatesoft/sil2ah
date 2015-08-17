<?php if (!defined('ON_ROOT')) { include_once 'views/404.php'; die();}?>
<?php include 'views/header.php';?>
<form method="post" action="index.php?module=admin&action=settings">
  <fieldset>
    <h3>Pengaturan</h3>
    <label for="siteTitle">Judul Situs</label>
    <input id="siteTitle" type="text" name="siteTitle" value="<?php echo $siteTitle;?>">
    <input id="saveButton" type="submit" value="Simpan">
  </fieldset>
</form>
<?php include 'views/footer.php';?>
