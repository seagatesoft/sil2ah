<?php
if (!defined('ON_ROOT')) { include_once 'views/404.php'; die();}

class Person extends Model {
  var $table = 'persons';

  public function getPerson($uuid) {
    return $this->selectFirst(null, array(array('uuid', '=', $uuid)));
  }

  public function getSpouses($personUuid, $personType) {
    $quotedUuid = $this->dbSource->quote($personUuid);

    if ($personType == 'S') {
      return $this->selectAllRows(
        'persons.*',
        array('persons', 'spouses'),
        array(
          'AND' => array(
            'persons.uuid=spouses.person_uuid',
            array("spouses.spouse_uuid", '=', $quotedUuid)
          )
        )
      );
    } else {
      return $this->selectAllRows(
        array('persons.*'),
        array('persons', 'spouses'),
        array(
          'AND' => array(
            'persons.uuid=spouses.spouse_uuid',
            array("spouses.person_uuid", '=', $quotedUuid)
          )
        )
      );
    }
  }

  public function getChildren($fatherUuid, $motherUuid) {
    return $this->selectAll(
      null,
      array(
        'AND' => array(
          array('father', '=', $fatherUuid),
          array('mother', '=', $motherUuid)
        )
      ),
      null,
      null,
      'sibling_index ASC'
    );
  }

  public function getCoreFamily($root) {
    $familyTree = array();
    $familyTree[$root['uuid']] = $root;
    $familyTree[$root['uuid']]['spouses'] = array();

    foreach ($this->getSpouses($root['uuid'], $root['person_type']) as $spouse) {
      $familyTree[$root['uuid']]['spouses'][$spouse['uuid']] = $spouse;
      $familyTree[$root['uuid']]['spouses'][$spouse['uuid']]['children'] = array();

      $father = $root['gender'] == 'M' ? $root['uuid'] : $spouse['uuid'];
      $mother = $root['gender'] == 'F' ? $root['uuid'] : $spouse['uuid'];

      foreach ($this->getChildren($father, $mother) as $child) {
        $familyTree[$root['uuid']]['spouses'][$spouse['uuid']]['children'][] = $child;
      }
    }

    return $familyTree;
  }

  public function getFamilyTree(&$familyTree, $root) {
    $familyTree[$root['uuid']] = $root;
    $familyTree[$root['uuid']]['spouses'] = array();

    foreach ($this->getSpouses($root['uuid'], $root['person_type']) as $spouse) {
      $familyTree[$root['uuid']]['spouses'][$spouse['uuid']] = $spouse;
      $familyTree[$root['uuid']]['spouses'][$spouse['uuid']]['children'] = array();

      $father = $root['gender'] == 'M' ? $root['uuid'] : $spouse['uuid'];
      $mother = $root['gender'] == 'F' ? $root['uuid'] : $spouse['uuid'];

      foreach ($this->getChildren($father, $mother) as $child) {
        $this->getFamilyTree($familyTree[$root['uuid']]['spouses'][$spouse['uuid']]['children'], $child);
      }
    }
  }

  public function getFullFamilyTree($rootUuid) {
    $rootAncestor = $this->getPerson($rootUuid);
    $familyTree = array();
    $this->getFamilyTree($familyTree, $rootAncestor, 0);

    return $familyTree;
  }
}
