<?php
class Page {

  private static $instance;
  private $title, $view, $name, $data;

  // The start method
  public static function start() {
      if (!isset(self::$instance)) {
          $c = __CLASS__;
          self::$instance = new $c;
      }

      return self::$instance;
  }

  public function __construct() {}

  public function setTitle($title) {
    $this->title = (string) $title;
  }

  public function getTitle() {
    if($this->title) {
      return $this->title;
    } else {
      return false;
    }
  }

  public function setView($view) {
    $path = __BASEPATH__."includes/views/".strtolower($view).".php";
    if(file_exists($path)) {
      $this->view = $path;
    } else {
      return false;
    }
  }

  public function setData($data) {
    $this->data = $data;
  }

  public function getData() {
    return $this->data;
  }

  public function setName($name) {
      $this->name = $name;
  }

  public function getName() {
      return $this->name;
  }


  public function getView() {
    if($this->view) {
      //return $this->view;
      $data = $this->getData();
      include($this->view);
    } else {
      return false;
    }
  }
}

$page = Page::start();
?>
