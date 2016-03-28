<?php
// Development settings
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', '1');

// Database settings
define('DB_HOST', 'localhost');
define('DB_DATABASE', 'database');
define('DB_USER', 'username');
define('DB_PASSWORD', '');

if(!defined(__BASEPATH__)) {
  define('__BASEPATH__', '');
}
if(!defined(__SITE_NAME)) {
    define('__SITE_NAME', 'office');
}

define('__ROUTING__', '/office/');
define('__REAL_PATH__', $_SERVER['HTTP_HOST'].__ROUTING__);

define('__UPLOAD_PATH', 'uploads/');

// Set default page (controller)
define('DEFAULT_PAGE', 'example');
define('PATH_404', 'includes/controllers/404.php');

date_default_timezone_set('UTC');

include(__BASEPATH__."includes/config/require.php");
?>
