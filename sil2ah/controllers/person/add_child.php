<?php
if (!defined('ON_ROOT')) { include_once 'views/404.php'; die();}

require_once 'controllers/base.php';

class AddChildController extends BaseController {
  var $models = array(
    'setting' => 'Setting',
    'person' => 'Person',
    'person_attributes' => 'PersonAttributes',
  );

  public function doGetAction() {
    $fatherUuid = $_GET['father'];
    $motherUuid = $_GET['mother'];
    $this->setView('person/add_child');
    $this->setViewArg('father', $this->Person->getPerson($fatherUuid));
    $this->setViewArg('mother', $this->Person->getPerson($motherUuid));
  }

  public function doPostAction() {
    try {
      $this->dbSource->startTransaction();

      $person = array(
        'uuid' => uniqid(),
        'name' => $_POST['person']['name'],
        'gender' => $_POST['person']['gender'],
        'father' => $_POST['person']['father'],
        'mother' => $_POST['person']['mother'],
        'sibling_index' => $_POST['person']['sibling_index'],
        'person_type' => 'D'
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

      $this->dbSource->commit();

      $this->setMessage('Data anak berhasil disimpan.');
      $this->sendViewPersonPage($person['uuid']);
    } catch(Exception $e) {
      $this->dbSource->rollback();
      $this->setMessage('Penyimpanan data gagal: ' . $e->getMessage());
    }
  }
}
