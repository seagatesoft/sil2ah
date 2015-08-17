<?php
if (!defined('ON_ROOT')) { include_once 'views/404.php'; die();}

require_once 'controllers/base.php';

class DeleteController extends BaseController {
  var $models = array(
    'setting' => 'Setting',
    'person' => 'Person',
    'spouse' => 'Spouse',
    'person_attributes' => 'PersonAttributes',
  );

  public function doGetAction() {
    $personUuid = $_GET['uuid'];
    $person = $this->Person->getPerson($personUuid);
    $deleteAllowed = true;
    $errorMessage = 'Tidak bisa menghapus, hapus dulu data anak/pasangannya.';

    if ('R' === $person['person_type']) {
        $deleteAllowed = false;
        $errorMessage = 'Tidak bisa menghapus akar pohon silsilah!';
    } else {
        $spouses = $this->Person->getSpouses($person['uuid'], $person['person_type']);
        if ($spouses) {
          if (in_array($person['person_type'], array('R', 'D'))) {
            $deleteAllowed = false;
          } else {
            foreach ($spouses as $spouse) {
              $father = $person['gender'] == 'M' ? $person['uuid'] : $spouse['uuid'];
              $mother = $person['gender'] == 'F' ? $person['uuid'] : $spouse['uuid'];
              if ($this->Person->getChildren($father, $mother)) {
                $deleteAllowed = false;
                break;
              }
            }
          }
        }
      }

    if (!$deleteAllowed) {
      $this->setMessage($errorMessage);
      $this->sendViewPersonPage($person['uuid']);
      return;
    }

    try {
      $this->PersonAttributes->deleteRecord(array('person_uuid' => $personUuid));
      $this->Spouse->delete(
        array(
          'OR' => array(
            array('person_uuid', '=', $personUuid),
            array('spouse_uuid', '=', $personUuid)
          )
        )
      );
      $this->Person->deleteRecord(array('uuid' => $personUuid));

      $this->setMessage('Data berhasil dihapus.');
      $this->sendMainPage();
    } catch(Exception $e) {
      $this->setMessage('Gagal menghapus data: ' . $e->getMessage());
    }
  }
}
