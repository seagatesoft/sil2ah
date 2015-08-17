<?php
if (!defined('ON_ROOT')) { include_once 'views/404.php'; die();}

require_once 'controllers/base.php';

class AddSpouseController extends BaseController {
  var $models = array(
    'setting' => 'Setting',
    'person' => 'Person',
    'spouse' => 'Spouse',
    'person_attributes' => 'PersonAttributes',
  );

  public function doGetAction() {
    $uuid = $_GET['uuid'];
    $this->setView('person/add_spouse');
    $this->setViewArg('person', $this->Person->getPerson($uuid));
  }

  public function doPostAction() {
    try {
      $this->dbSource->startTransaction();
      $spouse = $this->Person->getPerson($_POST['spouse']['person_uuid']);

      $person = array(
        'uuid' => uniqid(),
        'name' => $_POST['person']['name'],
        'gender' => $spouse['gender'] == 'M' ? 'F' : 'M',
        'person_type' => 'S'
      );
      $this->Person->insert($person);

      $personAttributes = array(
        'person_uuid' => $person['uuid'],
        'address' => $_POST['person_attributes']['address'],
        'phone_number' => $_POST['person_attributes']['phone_number'],
        'bbm' => $_POST['person_attributes']['bbm'],
        'facebook' => $_POST['person_attributes']['facebook'],
        'occupation' => $_POST['person_attributes']['occupation']
      );
      $this->PersonAttributes->insert($personAttributes);

      $spouse = array(
        'person_uuid' => $spouse['uuid'],
        'spouse_uuid' => $person['uuid']
      );
      $this->Spouse->insert($spouse);

      $this->dbSource->commit();

      $this->setMessage('Data pasangan berhasil disimpan.');
      $this->sendViewPersonPage($person['uuid']);
    } catch(Exception $e) {
      $this->dbSource->rollback();
      $this->setMessage('Penyimpanan data gagal: ' . $e->getMessage());
    }
  }
}
