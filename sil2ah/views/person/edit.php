<?php if (!defined('ON_ROOT')) { include_once 'views/404.php'; die();}?>
<?php include 'views/header.php';?>
<form method="post" action="index.php?module=person&action=edit">
  <fieldset>
    <h3>Sunting Data</h3>
    <label for="name">Nama</label>
    <input id="name" type="text" name="person[name]" value="<?php echo $person['name'];?>">
    <?php if ('S' != $person['person_type']):?>
    <label for="gender">Jenis Kelamin</label>
    <select id="gender" name="person[gender]">
      <option value="M" <?php if('M' == $person['gender']) { echo 'selected';};?>>Pria</option>
      <option value="F" <?php if('F' == $person['gender']) { echo 'selected';};?>>Wanita</option>
    </select>
    <label for="sibling_index">Anak ke</label>
    <select id="sibling_index" name="person[sibling_index]">
      <?php for ($i=1; $i <= 10; $i++):?>
        <?php if($i == $person['sibling_index']):?>
          <option value="<?php echo $i;?>" selected=""><?php echo $i;?></option>
        <?php else:?>
          <option value="<?php echo $i;?>"><?php echo $i;?></option>
        <?php endif;?>
      <?php endfor;?>
    </select>
    <?php endif;?>
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
