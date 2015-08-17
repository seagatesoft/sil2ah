<?php if (!defined('ON_ROOT')) { include_once 'views/404.php'; die();}?>
<?php include 'views/header.php';?>
<form method="post" action="index.php?module=person&action=edit">
  <fieldset>
    <h3>Sunting Data</h3>
    <label for="name">Nama</label>
    <input id="name" type="text" name="person[name]" value="<?php echo $person['name'];?>">
    <label for="address">Alamat</label>
    <textarea id="address" name="person_attributes[address]">
      <?php echo $personAttributes['address'];?>
    </textarea>
    <label for="phone_number">No. Telepon/Whatsapp</label>
    <input id="phone_number" type="text" name="person_attributes[phone_number]" value="<?php echo $personAttributes['phone_number'];?>">
    <label for="bbm">PIN BBM</label>
    <input id="bbm" type="text" name="person_attributes[bbm]" value="<?php echo $personAttributes['bbm'];?>">
    <label for="facebook">Facebook</label>
    <input id="facebook" type="text" name="person_attributes[facebook]" value="<?php echo $personAttributes['facebook'];?>">
    <label for="occupation">Pekerjaan</label>
    <input id="occupation" type="text" name="person_attributes[occupation]" value="<?php echo $personAttributes['occupation'];?>">
    <input type="hidden" name="person[uuid]" value="<?php echo $person['uuid'];?>">
    <input id="saveButton" type="submit" value="Simpan">
  </fieldset>
</form>
<?php include 'views/footer.php';?>
