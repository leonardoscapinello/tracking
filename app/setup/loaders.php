<?php

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

define("DIRNAME", dirname(__FILE__) . "/");

require_once(DIRNAME . "../functions/autoload.php");
require_once(DIRNAME . "../vendor/autoload.php");
require_once(DIRNAME . "../class/autoload.php");


$env = new Env();
$less = new lessc();
$static = new StaticFiles();
$fields = new Fields();
$alerts = new Alerts();
$url = new URL();
$session = new AccountsSession();
$routes = new Routes();
$classroom = new ClassRooms();
$account = new Accounts();

define("API_URL", $env->get("API_URL"));

$less->checkedCompile(DIRNAME . "../../static/less/stylesheet.less", DIRNAME . "../../static/stylesheet/stylesheet.css");

