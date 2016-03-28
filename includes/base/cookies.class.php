<?php

class Cookies {
  private static $instance;

  private function __construct() {
  }

   // The start method
   public static function start() {
       if (!isset(self::$instance)) {
           $c = __CLASS__;
           self::$instance = new $c;
       }

       return self::$instance;
   }

  public function Set($var, $name, $expire = "", $path = "/") {
    setcookie($name, $var, $expire, $path);
  }

  public function Get($name) {
    if(isset($_COOKIE[$name])) {
      return $_COOKIE[$name];
    } else {
      return false;
    }
  }

  public function Add($var, $name) {
    if($this->Get($name)) {
      $array = $this->Get($name);
      if(is_array($array)) {
        if(!in_array($var, $array)) {
          array_push($array, $var);
        }
      } elseif($array<>$var) {
        $array = array($array, $var);
      }
      $this->Set($array, $name);
    } else {
      $array = array($var);
      $this->set($array, $name);
    }
  }

  public function Delete($name, $id = "") {
    if(isset($_COOKIE[$name]) && $id=="") {
      unset($_COOKIE[$name]);
      $this->Set('', $name, time()-3600, '/');
      return true;
    } elseif($id!="") {
      $array = $this->Get($name);
      if(is_array($array)) {
        $narray = array();
        foreach($array as $arr) {
          if($arr!=$id) {
            $narray[] = $arr;
          }
        }
        $this->Set($narray, $name);
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

}
?>
