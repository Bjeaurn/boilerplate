<?php
$routing =  $router->getRouting();
if($routing[1]) {
    $file = $routing[1];
}
// No route = authentication?

$token = apache_request_headers()["Authorization"];
if(!$token) {
    API::error("No authentication token", 401);
    die();
}

$user = Authentication::Verify($token);
if(!$user) {
    API::error("Invalid authentication token", 401);
    die();
}
$request = API::GetInput();


if($request->domainID) {
    if($user->domainID) {
        if($request->domainID!=$user->domainID) {
            error_log("User authenticated correctly, but is using mismatching domainID (UID: ".$user->getID().", domain: ".$request->domainID.")");
        }
    }

    $domain = Domain::FindByUserAndID($user->getID(), $request->domainID);
    if(!$domain) {
        API::error('Invalid domain. No access', 400);
        die();
    }
} elseif($user->domainID) {
    $domainID = $user->domainID;
    $domain = Domain::FindByUserAndID($user->getID(), $domainID);
    if(!$domain) {
        API::error('Invalid domain. No access', 400);
        die();
    }
}

try {
    require_once("api/".$file.".php");
} catch(Exception $e) {
    API::error("No valid route", 404);
}
die();
?>
