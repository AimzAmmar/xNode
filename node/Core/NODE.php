<?php
class NODE{

    private static $classMap = array (
            'Config' => 'Core',
            'NodeFilter' => 'Library/DataValidation',
            'NodeSanitizer' => 'Library/DataValidation',
            'NodeValidator' => 'Library/DataValidation',
            'NodeDatabase' => 'Model'
    );

    public static function load($class, $namespace = "Classes", $core=true){
        $path = NODE_CORE.DS;
        if($core == false){
            $path = "";
        } else {
            if(isset(self::$classMap[$class])){
                $namespace = self::$classMap[$class];
            }
        }
        $filepath = $path.$namespace.DS.$class.".php";
        if(file_exists($filepath)){
            $file = require_once($filepath);
            return $file;
        } else {
            return false;
        }
    }

}
?>