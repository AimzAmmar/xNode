<?php

//$time_start = microtime(true);


if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

if (!defined('ROOT')) {
	define('ROOT', dirname(dirname(dirname(__FILE__))));
}
if (!defined('APP_DIR')) {
	define('APP_DIR', basename(dirname(dirname(__FILE__))));
}
if (!defined('NODEROOT_DIR')) {
	define('NODEROOT_DIR', basename(dirname(__FILE__)));
}
if (!defined('NODE_ROOT')) {
	define('NODE_ROOT', dirname(__FILE__) . DS);
}
if (!defined('NODE_CORE')) {
	if (function_exists('ini_set')) {
		ini_set('include_path', ROOT . DS . 'node' . PATH_SEPARATOR . ini_get('include_path'));
	}
    define("NODE_CORE", ROOT. DS .'node');
	if (!include ( NODE_CORE . DS .'Core'. DS. 'bootstrap.php')) {
		$failed = true;
	}
} else {
	if (!include (NODE_CORE . DS . 'Core' . DS . 'bootstrap.php')) {
		$failed = true;
	}
}
if (!empty($failed)) {
    trigger_error("NodePHP Not Found");
    exit;
}

define("CSS", BASE."css/");
define("FILES",BASE."files/");
define("IMG", BASE."images/");
define("JS", BASE."js/");
$dispatcher = new Dispatcher();

$dispatcher->dispatch();

/*
 *
 $time_end = microtime(true);

//dividing with 60 will give the execution time in minutes other wise seconds
$execution_time = ($time_end - $time_start);

//execution time of the script
echo '<b>Total Execution Time:</b> '.$execution_time.' Mins';
*/