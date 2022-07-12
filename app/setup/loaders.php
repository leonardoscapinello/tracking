<?php

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

define("DIRNAME", dirname(__FILE__) . "/");

require_once(DIRNAME . "../functions/autoload.php");
require_once(DIRNAME . "../vendor/autoload.php");
require_once(DIRNAME . "../class/autoload.php");

use ScssPhp\ScssPhp\Compiler;

$env = new Env();
$less = new lessc();
$scss = new Compiler();
$static = new StaticFiles();
$fields = new Fields();
$alerts = new Alerts();
$url = new URL();
$session = new AccountsSession();
$modules = new Modules();
$account = new Accounts();
$text = new Text();
$domains = new Domains();
$numeric = new Numeric();

define("API_URL", $env->get("API_URL"));

SassCompiler::run(DIRNAME . "../../static/scss/", DIRNAME . "../../static/css/");
SassCompiler::run(DIRNAME . "../../static/scss/additional-styles/", DIRNAME . "../../static/css/additional-styles/");

//$less->checkedCompile(DIRNAME . "../../static/less/css.less", DIRNAME . "../../static/css/css.css");

