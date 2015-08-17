<?php
if (!defined('ON_ROOT')) { include_once 'views/404.php'; die();}

include_once 'datasources/database.php';
include_once 'views/base.php';

class BaseController {
  var $dbSource = null;
  var $models = array(
    'setting' => 'Setting',
    'person' => 'Person',
  );
  var $renderer = null;

  public function __construct() {
    $this->dbSource = new DbSource();

    foreach ($this->models as $fileName => $className) {
      include_once "datasources/$fileName.php";
      $this->$className = new $className($this->dbSource);
    }

    $this->renderer = new Renderer();
  }

  public function beforeAction() {
    return $this->isUserLoggedIn();
  }

  public function onBeforeActionFalse() {
    $this->sendLoginPage();
    $this->renderer->render();
  }

  public function afterAction() {
    if (!array_key_exists('siteTitle', $this->renderer->args)) {
      $this->setSiteTitle($this->Setting->getSiteTitle());
    }

    if (!array_key_exists('isUserLoggedIn', $this->renderer->args)) {
      $this->setViewArg('isUserLoggedIn', $this->isUserLoggedIn());
    }

    $this->renderer->render();
  }

  public function beforeGetAction() {
    return $this->beforeAction();
  }

  public function onBeforeGetActionFalse() {
    $this->onBeforeActionFalse();
  }

  public function doGetAction() {

  }

  public function afterGetAction() {
    $this->afterAction();
  }

  public function beforePostAction() {
    return $this->beforeAction();
  }

  public function doPostAction() {

  }

  public function onBeforePostActionFalse() {
    $this->onBeforeActionFalse();
  }

  public function afterPostAction() {
    $this->afterAction();
  }

  public function handleGet() {
    if ($this->beforeGetAction()) {
      $this->doGetAction();
      $this->afterGetAction();
    } else {
      $this->onBeforeGetActionFalse();
    }
  }

  public function handlePost() {
    if ($this->beforePostAction()) {
      $this->doPostAction();
      $this->afterPostAction();
    } else {
      $this->onBeforePostActionFalse();
    }
  }

  public function isUserLoggedIn() {
    return array_key_exists('username', $_SESSION);
  }

  public function setView($view) {
    $this->renderer->setView($view);
  }

  public function setViewArg($key, $value) {
    $this->renderer->setArg($key, $value);
  }

  public function setSiteTitle($siteTitle) {
    $this->setViewArg('siteTitle', $siteTitle);
  }

  public function setMessage($message) {
    $this->setViewArg('message', $message);
  }

  function sendLoginPage() {
    $this->setView('user/login');
    $this->setSiteTitle($this->Setting->getSiteTitle() . ' - Login');
    $this->setViewArg('isUserLoggedIn', false);
  }

  function sendMainPage() {
    $this->setView('person/main');
    $this->setSiteTitle($this->Setting->getSiteTitle() . ' - Pohon Silsilah');
    $rootUuid = $this->Setting->getRootAncestorUuid();
    $this->setViewArg('rootUuid', $rootUuid);
    $this->setViewArg('familyTree', $this->Person->getFullFamilyTree($rootUuid));
  }

  public function sendViewPersonPage($personUuid) {
    $person = $this->Person->getPerson($personUuid);

    $this->setViewArg('person', $person);
    $this->setViewArg('father', $this->Person->getPerson($person['father']));
    $this->setViewArg('mother', $this->Person->getPerson($person['mother']));
    $this->setViewArg('personAttributes', $this->PersonAttributes->getPersonAttributes($personUuid));
    $this->setViewArg('familyTree', $this->Person->getCoreFamily($person));
    $this->setView('person/view');
    $this->setSiteTitle($this->Setting->getSiteTitle() . ' - ' . $person['name']);
  }
}
