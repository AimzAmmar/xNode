<?php
ini_set( "display_errors", "on" );
error_reporting( E_ALL );

require 'NODE.php';
define('CONFIG', ROOT.DS.APP_DIR.DS."Config");
define("CONTROLLER", ROOT.DS.APP_DIR.DS."Controller");
define("MODEL", ROOT.DS.APP_DIR.DS."Model");
define("VIEW", ROOT.DS.APP_DIR.DS."View");
define('HOST', $_SERVER['HTTP_HOST']);

NODE::load('NodeError');
NODE::load('config', CONFIG, false);
Config::loadConfig();


NODE::load('router',CONFIG, false);
NODE::load('NodeRequest');
NODE::load('NodeResponse');
NODE::load('bootstrap',CONFIG, false);
NODE::load('Controller', CONTROLLER, false);
NODE::load('Model', MODEL, false);
NODE::load('Dispatcher');