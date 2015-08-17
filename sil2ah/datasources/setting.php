<?php
if (!defined('ON_ROOT')) { include_once 'views/404.php'; die();}

class Setting extends Model {
  var $table = 'settings';

  public function getSettingValue($settingName) {
    $row = $this->selectFirst('value', array(array('name', '=', $settingName)));

    if ($row) {
      return $row['value'];
    } else {
      return false;
    }
  }

  public function getSuperAdminUserName() {
    return $this->getSettingValue('SUPER_ADMIN');
  }

  public function getRootAncestorUuid() {
    return $this->getSettingValue('ROOT_ANCESTOR');
  }

  public function getSiteTitle() {
    return $this->getSettingValue('SITE_TITLE');
  }

  public function insertSuperAdminUserName($username) {
    return $this->insert(array('name' => 'SUPER_ADMIN', 'value' => $username));
  }

  public function insertRootAncestorUuid($uuid) {
    return $this->insert(array('name' => 'ROOT_ANCESTOR', 'value' => $uuid));
  }

  public function insertSiteTitle($siteTitle) {
    return $this->insert(array('name' => 'SITE_TITLE', 'value' => $siteTitle));
  }

  public function updateSiteTitle($siteTitle) {
    return $this->update(array('value' => $siteTitle), array(array('name', '=', 'SITE_TITLE')));
  }
}
