<?php
class Language {

    private static $instance;
	private $file = "nl";
    public $language, $name;

    // The start method
    public static function start() {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }

        return self::$instance;
    }

    public function __construct() {}

	public static function getLanguage() {
		$path = "includes/languages/".$file.".php";
		if(file_exists($path)) {
			include($path);
		}
	}
}
?>
