<?php if (!defined('ON_ROOT')) { include_once 'views/404.php'; die();}?>
<?php include 'views/header.php';?>
<form method="post" action="index.php?module=person&action=add_child">
  <fieldset>
    <h3>Tambah Data Anak</h3>
    <label>Ayah: <?php echo $father['name'];?></label>
    <label>Ibu: <?php echo $mother['name'];?></label>
    <label for="name">Nama</label>
    <input id="name" type="text" name="person[name]">
    <label for="gender">Jenis Kelamin</label>
    <select id="gender" name="person[gender]">
      <option value="M">Pria</option>
      <option value="F">Wanita</option>
    </select>
    <label for="sibling_index">Anak ke</label>
    <select id="sibling_index" name="person[sibling_index]">
      <?php for ($i=1; $i <= 10; $i++):?>
        <option value="<?php echo $i;?>"><?php echo $i;?></option>
      <?php endfor;?>
    </select>
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
    <input type="hidden" name="person[father]" value="<?php echo $father['uuid'];?>">
    <input type="hidden" name="person[mother]" value="<?php echo $mother['uuid'];?>">
    <input id="saveButton" type="submit" value="Simpan">
  </fieldset>
</form>
<?php include 'views/footer.php';?>
