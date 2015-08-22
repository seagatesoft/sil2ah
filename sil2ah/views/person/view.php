<?php if (!defined('ON_ROOT')) { include_once 'views/404.php'; die();}?>
<?php require_once 'views/helpers.php';?>
<?php include 'views/header.php';?>
<?php $coupleType = $person['gender'] == 'M' ? 'Istri' : 'Suami';?>
<table id="personData">
  <tbody>
    <tr><td class="fieldName">Jenis Kelamin</td><td class="fieldValue"><?php echo displayPersonGender($person['gender']);?></td></tr>
    <?php if ('S' != $person['person_type']):?>
    <tr>
      <td class="fieldName">Ayah</td>
      <td class="fieldValue"><?php echo createPersonLink($father);?></td>
    </tr>
    <tr>
      <td class="fieldName">Ibu</td>
      <td class="fieldValue"><?php echo createPersonLink($mother);?></td>
    </tr>
    <tr><td class="fieldName">Anak ke</td><td class="fieldValue"><?php echo $person['sibling_index'];?></td></tr>
    <?php endif;?>
    <tr><td class="fieldName">Alamat</td><td class="fieldValue"><?php echo $personAttributes['address'];?></td></tr>
    <tr><td class="fieldName">No. Telpon/Whatsapp</td><td class="fieldValue"><?php echo $personAttributes['phone_number'];?></td></tr>
    <tr><td class="fieldName">PIN BBM</td><td class="fieldValue"><?php echo $personAttributes['bbm'];?></td></tr>
    <tr>
      <td class="fieldName">Facebook</td>
      <td class="fieldValue">
        <a href="<?php echo $personAttributes['facebook'];?>">
          <?php echo $personAttributes['facebook'];?>
        </a>
      </td>
    </tr>
    <tr><td class="fieldName">Pekerjaan</td><td class="fieldValue"><?php echo $personAttributes['occupation'];?></td></tr>
  </tbody>
</table>

<?php if (!empty($familyTree[$person['uuid']]['spouses'])):?>
<?php $spouses = $familyTree[$person['uuid']]['spouses'];?>
<h2><?php echo $coupleType;?></h2>
<ul>
<?php foreach($spouses as $spouse):?>
<li class="spouse">
  <?php echo createPersonLink($spouse);?>
  <?php if (!empty($spouse['children'])):?>
  <table id="childrenData">
    <thead>
      <tr>
        <th>No.</th>
        <th>Nama</th>
        <th>Jenis Kelamin</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($spouse['children'] as $child):?>
      <tr>
        <td class="fieldValue"><?php echo $child['sibling_index'];?>.</td>
        <td class="fieldValue"><?php echo createPersonLink($child);?></td>
        <td class="fieldValue"><?php echo displayPersonGender($child['gender']);?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <?php endif;?>
  <?php if ($person['gender'] == 'M'):?>
    <div>
      <a href="index.php?module=person&action=add_child&father=<?php echo $person['uuid'];?>&mother=<?php echo $spouse['uuid'];?>">
        <button>Tambah Data Anak</button>
      </a>
    </div>
  <?php else:?>
    <div>
      <a href="index.php?module=person&action=add_child&father=<?php echo $spouse['uuid'];?>&mother=<?php echo $person['uuid'];?>">
        <button>Tambah Data Anak</button>
      </a>
    </div>
  <?php endif;?>
</li>
<?php endforeach;?>
<?php endif;?>
</ul>

<div id="buttonPanel">
  <a href="index.php?module=person&action=delete&uuid=<?php echo $person['uuid'];?>">
    <button>Hapus Data</button>
  </a>
  <a href="index.php?module=person&action=edit&uuid=<?php echo $person['uuid'];?>">
    <button>Sunting Data</button>
  </a>
  <?php if ($person['person_type'] != 'S'):?>
  <a href="index.php?module=person&action=add_spouse&uuid=<?php echo $person['uuid'];?>">
    <button>Tambah Data <?php echo $coupleType;?></button>
  </a>
  <?php endif;?>
</div>
<?php include 'views/footer.php';?>
