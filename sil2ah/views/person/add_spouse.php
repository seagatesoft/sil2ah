<?php if (!defined('ON_ROOT')) { include_once 'views/404.php'; die();}?>
<?php include 'views/header.php';?>
<?php $coupleType = $person['gender'] == 'M' ? 'Istri' : 'Suami';?>
<form method="post" action="index.php?module=person&action=add_spouse">
  <fieldset>
    <h3>Tambah Data <?php echo $coupleType;?> - <?php echo $person['name'];?></h3>
    <label for="name">Nama</label>
    <input id="name" type="text" name="person[name]">
    <label for="address">Alamat</label>
    <textarea id="address" name="person_attributes[address]"></textarea>
    <label for="phone_number">No. Telepon/Whatsapp</label>
    <input id="phone_number" type="text" name="person_attributes[phone_number]">
    <label for="bbm">PIN BBM</label>
    <input id="bbm" type="text" name="person_attributes[bbm]">
    <label for="facebook">Facebook</label>
    <input id="facebook" type="text" name="person_attributes[facebook]">
    <label for="occupation">Pekerjaan</label>
    <input id="occupation" type="text" name="person_attributes[occupation]">
    <input type="hidden" name="spouse[person_uuid]" value="<?php echo $person['uuid'];?>">
    <input id="saveButton" type="submit" value="Simpan">
  </fieldset>
</form>
<?php include 'views/footer.php';?>
