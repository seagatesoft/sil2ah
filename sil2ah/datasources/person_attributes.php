<?php
if (!defined('ON_ROOT')) { include_once 'views/404.php'; die();}

class PersonAttributes extends Model {
  var $table = 'person_attributes';

  public function getPersonAttributes($personUuid) {
    return $this->selectFirst(null, array(array('person_uuid', '=', $personUuid)));
  }

  public function upsertRecord($record) {
    if ($this->getPersonAttributes($record['person_uuid'])) {
      $this->updateRecord($record);
    } else {
      $this->insert($record);
    }
  }
}
