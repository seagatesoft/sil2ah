<?php
if (!defined('ON_ROOT')) { include_once 'views/404.php'; die();}

class Renderer {
  var $view = 'default';
  var $args = array();

  public function setView($view) {
    $this->view = $view;
  }

  public function setArg($key, $value) {
    $this->args[$key] = $value;
  }

  public function render() {
    foreach ($this->args as $key => $value) {
      $$key = $value;
    }

    include_once "views/{$this->view}.php";
  }
}
