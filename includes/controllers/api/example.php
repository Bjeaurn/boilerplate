<?php
if($_SERVER['REQUEST_METHOD']=="GET") {
    $example = new Example("API test");
    new API($example);
    die();
}

if($_SERVER['REQUEST_METHOD']=="POST") {
    new API($request);
    die();
}
?>
