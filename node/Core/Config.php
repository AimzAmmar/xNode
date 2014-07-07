<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Config {

    private static $settings = array();

    public static function loadConfig(){
        self::setBase();
        self::setDebug();
    }

    public static function setBase(){
        if(!defined("BASE")){
            $base = $_SERVER['PHP_SELF'];
            $base = substr($base,0, strlen($base)-9); //cast our index.php from the string
            $rootdir = "/".APP_DIR."/".NODEROOT_DIR;
            $ebase = explode($rootdir, $base);
            if($ebase !== $base){
                $base = implode("",$ebase);
            }
            define("BASE",$base);
        }
    }
    public static function write($key, $value){
           self::$settings[$key] = $value;
    }

    // the read will return the key value if the key exists or 0 if it doesnt,
    //so if you are expecting a key to return the value false use === operator
    //or if you are expecting a key to return 0 as value, please use the Config::check function before
    // so you get sure that the key exists and the value is 0
    public static function read($key){
           if(self::check($key)){
               return self::$settings[$key];
           } else {
               return 0;
           }
    }
    public static function check($key){
         return array_key_exists($key, self::$settings);
    }

    private static function setDebug(){
        if(!defined("DEBUG")){
            if(HOST == "localhost"){
                define('DEBUG', true);
            } else {
                define('DEBUG', false);
            }
        }
        if(DEBUG == true){
            set_error_handler(array('NodeError', "debugger"), E_ALL | E_ERROR);
        } else {
            set_error_handler(array('NodeError', "errorHandler"), E_ALL | E_ERROR);
        }
        
        register_shutdown_function(array('NodeError', "fatalError"));
    }
}
?>
