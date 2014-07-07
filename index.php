<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor. abc
 */
define("ROOT", dirname(__FILE__));
define("DS", DIRECTORY_SEPARATOR);

define("APP_DIR", "app");
define('NODEROOT_DIR', 'noderoot');
define('NODE_CORE_DIR', 'node');
define('NODE_CORE', ROOT . DS . NODE_CORE_DIR);
define("NODE_ROOT", ROOT . DS . APP_DIR . DS . NODEROOT_DIR . DS);

require NODE_ROOT."index.php";

?>
