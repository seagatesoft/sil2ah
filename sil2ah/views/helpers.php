<?php
if (!defined('ON_ROOT')) { include_once 'views/404.php'; die();}

function createPersonLink($person) {
  return "<a href=\"index.php?module=person&action=view&uuid={$person['uuid']}\">{$person['name']}</a>";
}

function displayFamilyTreeAsHtml($data, $uuid, $level) {
    echo "<li class=\"descendant\">\n";
    echo "<div id=\"person-{$uuid}\" class=\"person\">";
    echo createPersonLink($data[$uuid]);
    echo "</div>\n";
    foreach ($data[$uuid]['spouses'] as $spouseUuid => $spouse) {
        $coupleType = $data[$uuid]['gender'] == 'M' ? 'Istri' : 'Suami';
        echo "<div class=\"label\">{$coupleType}:</div>";
        echo "<div id=\"person-{$spouseUuid}\" class=\"person spouse\">";
        echo createPersonLink($spouse);
        echo "</div>\n";

        if (!empty($spouse['children'])) {
            echo "<ol id=\"ol-{$uuid}\" class=\"level-{$level}\">\n";
            foreach ($spouse['children'] as $childUuid => $child) {
                displayFamilyTreeAsHtml($spouse['children'], $childUuid, $level+1);
            }
            echo "</ol>\n";
        }
    }
    echo "</li>\n";
}

function displayPersonGender($genderCode) {
  return $genderCode == 'M' ? 'Pria' : 'Wanita';
}
