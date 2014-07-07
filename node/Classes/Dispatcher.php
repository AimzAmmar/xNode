<?php

class Dispatcher {
    function dispatch(){
       $noderesponse = new NodeResponse();
       $nodeRequest = new NodeRequest();
       $controllerClass = $this->loadController($nodeRequest->controller);
       $controller = new $controllerClass($nodeRequest);
       $controller->performAction();
    }

    function loadController($controller){
        $controllerClass = $controller."Controller";
        if(!NODE::load($controllerClass, CONTROLLER, false)){
            //trigger error, controller file not found
            echo "missing controller!!!";
            exit();
        }
        return $controllerClass;
    }
}
