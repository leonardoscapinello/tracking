<?php
require_once("../../../app/setup/loaders.php");
$request_body = file_get_contents('php://input');

$route = get_request("route", true, false);
$file = get_request("file", true, false);

$path = DIRNAME . "../../routes/api/v1/" . $route . "/" . $file . ".php";

if (not_empty_bool($route) && not_empty_bool($file) && file_exists($path)) {
    require $path;
}
