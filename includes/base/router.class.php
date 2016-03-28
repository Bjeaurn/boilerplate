<?php
class Router {

  private static $instance;
  private $routing;

  private function __construct() {
    $requestURI = explode('/', $_SERVER['REQUEST_URI']);
    $scriptName = explode('/', $_SERVER['SCRIPT_NAME']);

    for($i= 0;$i < sizeof($scriptName);$i++)
            {
          if ($requestURI[$i] == $scriptName[$i])
                  {
                    unset($requestURI[$i]);
                }
          }

    $command = array_values($requestURI);
    $this->routing = $command;
    $this->startRouting();
  }

  public function getRouting() {
    return $this->routing;
  }

  public function getController() {
    return $this->routing[0];
  }

  public function startRouting() {
    if(empty($this->routing[0])) {
      if(DEFAULT_PAGE) {
        $file = DEFAULT_PAGE;
      }
    } else {
      $file = $this->routing[0];
    }
    $data = new StdClass;
    $path = __BASEPATH__."includes/controllers/".$file.".php";
    try {
        $page = Page::start();
        if(file_exists($path)) {
            $autoloader = Autoloader::start();
            $session = Session::start();
            $cookies = Cookies::start();
            $router = $this;
            require_once($path);
          } else {
            throw new Exception('Controller not found');
          }
      } catch(Exception $e) {
          if(PATH_404) {
            if(file_exists(PATH_404)) {
              include(PATH_404);
            }
          }
        }
  }

  public static function Redirect($location = "") {
    if($location=="") { $location = $_SERVER['HTTP_REFERER']; } else {
      $pos = strpos($location, "http");
      if($pos===false) {
        $location = __ROUTING__.$location;
      }
    }
    header("Location: ". $location);
    die();
  }

  // The start method
  public static function start() {
     if (!isset(self::$instance)) {
         $c = __CLASS__;
         self::$instance = new $c;
     }
       return self::$instance;
  }
}
Router::start();

?>
