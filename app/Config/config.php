<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/*
 * uncomment and set the value of the given variable to enforce the debug value otherwise
 * value will be assumed true for localhost// and will be false for other hosts for instance
 * your live server.
 */
//define('DEBUG', true);

//FILE SYSTEM SETTINGS END HERE

// DATABASE SETTINGS

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PWD', 'node');
define('DB_NAME', 'cyrish');
define('DB_Table_PREFIX', '');

//DATABASE SETTINGS END HERE

//WEBSITE SETTINGS
//define('BASE', '/cyrish/'); //base path to your xnode installation, should have slashes
define('SITE_NAME', 'Project Xnode');
//define('SITE_URL', 'http://localhost/cyrish/'); //with trailing backslash

//WEBSITE SETTINGS END HERE

//CORE SETTINGS //


//CORE SETTINGS END HERE

/*
 * loads the system config file which will use your settings to determine system wise login
 * settings.
 */
NODE::load('Config', 'Core');
?>
