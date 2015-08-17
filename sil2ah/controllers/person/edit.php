<?php
if (!defined('ON_ROOT')) { include_once 'views/404.php'; die();}

require_once 'controllers/base.php';

class EditController extends BaseController {
  var $models = array(
    'setting' => 'Setting',
    'person' => 'Person',
    'person_attributes' => 'PersonAttributes',
  );

  public function doGetAction() {
    $uuid = $_GET['uuid'];
    $this->setView('person/edit');
    $this->setViewArg('person', $this->Person->getPerson($uuid));
    $this->setViewArg('personAttributes', $this->PersonAttributes->getPersonAttributes($uuid));
  }

  public function doPostAction() {
    try {
      $this->dbSource->startTransaction();
      $person = array(
        'uuid' => $_POST['person']['uuid'],
        'name' => $_POST['person']['name']
      );
      $this->Person->updateRecord($person);

      $personAttributes = array(
        'person_uuid' => $person['uuid'],
        'address' => $_POST['person_attributes']['address'],
        'phone_number' => $_POST['person_attributes']['phone_number'],
        'bbm' => $_POST['person_attributes']['bbm'],
        'facebook' => $_POST['person_attributes']['facebook'],
        'occupation' => $_POST['person_attributes']['occupation']
      );
      $this->PersonAttributes->upsertRecord($personAttributes);

      $this->dbSource->commit();

      $this->setMessage('Data berhasil disunting.');
      $this->sendViewPersonPage($person['uuid']);
    } catch(Exception $e) {
      $this->dbSource->rollback();
      $this->setMessage('Penyimpanan data gagal: ' . $e->getMessage());
    }
  }
}
