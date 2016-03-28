<?php
class Autoloader {

  private static $instance;

  // The start method
  public static function start() {
    if (!isset(self::$instance)) {
        $c = __CLASS__;
        self::$instance = new $c;
      }
    return self::$instance;
  }

  public function __construct() {
      spl_autoload_register(array($this, 'loader'));
  }

  private function loader($className) {
      $file = __BASEPATH__."includes/models/".strtolower($className).".php";
      try {
        if(file_exists($file)) {
          require_once($file);
        } else {
          throw new Exception('Class \''. $className .'\' not found in: '. $file .'<br />');
        }
      } catch(Exception $e) {
        echo $e->getMessage();
      }
  }
}

$autoloader = Autoloader::start();
?>
