<?php

class Session {
  private static $instance;

  private function __construct() {
    if(!isset($_SESSION)) {
      session_start();
    }
  }

   // The start method
   public static function start() {
       if (!isset(self::$instance)) {
           $c = __CLASS__;
           self::$instance = new $c;
       }

       return self::$instance;
   }

  function GetID() {
    if($this->Get('userID')) {
      return $this->Get('userID');
    } else{
      return false;
    }
  }

  function SetID($id = '') {
    if(!empty($id)) {
      $this->Set($id, 'userID');
    } else {
      $this->Delete('userID');
    }
  }

  function Destroy() {
    unset($_SESSION);
    session_destroy();
  }

  public function Set($var, $name) {
    $_SESSION[$name] = $var;
  }

  public function Get($name) {
    if(isset($_SESSION[$name])) {
      return $_SESSION[$name];
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
    if(isset($_SESSION[$name]) && $id=="") {
      unset($_SESSION[$name]);
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

  public function Error($error) {
    $this->Set($error, 'error');
  }

  public function Confirm($confirm) {
    $this->Set($confirm, 'confirm');
  }

  public function Logout() {
      $this->SetID();
      $this->Destroy();
  }

  public function IsLoggedIn() {
    if($this->GetID()) {
      return true;
    } else {
      return false;
    }
  }

  public function IsAdmin() {
    if($this->Get('UserLevel')>1) {
      return true;
    } else {
      return false;
    }
  }

}

$session = Session::start();
?>
