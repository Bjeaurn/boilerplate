<?php
header("HTTP/1.0 404 Not Found");
$page->setTitle('404: Page not found');
$page->setView('errors/404');
?>
