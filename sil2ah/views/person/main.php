<?php if (!defined('ON_ROOT')) { include_once 'views/404.php'; die();}?>
<?php require_once 'views/helpers.php';?>
<?php include 'views/header.php';?>
<ol class="level-0">
<?php displayFamilyTreeAsHtml($familyTree, $rootUuid, 1);?>
</ol>
<?php include 'views/footer.php';?>
