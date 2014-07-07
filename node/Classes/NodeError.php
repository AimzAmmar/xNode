<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NodeError
 *
 * @author Ahmed
 */
class NodeError {
    //put your code here
    
    
    // DO USE DEBUG CONSTANT TO GET TO KNOW IF DETAILED ERROR REPORT IS REQUIRED OR NOT
    
    public static function debugger($errno,$errstr,$errfile = "",$errline = "",$errcontext = array()){
        echo "<br /> --------------------------------------- <br />";
        echo "This is the debugger<br /><br/>";
        echo " Error Num : ".$errno;
        echo "<br /> Error String : ".$errstr;
        echo "<br /> Error File : ".$errfile;
        echo "<br /> Error Line : ".$errline;
        echo "<br /> Error Context : ";
        print_r($errcontext);
        echo "<br />-----------------------------------------<br />";
       // die("cant go beyond this");
    }
    
    public static function errorHandler($errno,$errstr,$errfile,$errline,$errcontext){
        echo "<br /> --------------------------------------- <br />";
        echo "This is the debugger<br /><br/>";
        echo " Error Num : ".$errno;
        echo "<br /> Error String : ".$errstr;
        echo "<br /> Error File : ".$errfile;
        echo "<br /> Error Line : ".$errline;
        echo "<br /> Error Context : ";
        print_r($errcontext);
        echo "<br />-----------------------------------------<br />";
    }
    
    public static function fatalError()
    {
        $error = error_get_last();
       
        if ( $error["type"] == E_ERROR ){
            self::debugger($error['type'], $error['message'], $error['file'], $error['line']);
        }
    }
}

?>
