<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class NodeRequest{
    var $controller;
    var $action;
    var $params;
    var $data;
    var $method;
    var $post;
    var $get;
    var $queryString;
    var $host;
    var $requestURI;
    var $ajax;
    
    public function NodeRequest(){
        $this->get = array();
        $this->post = array();
        $this->requestURI = $_SERVER['REQUEST_URI'];
        $url = NodeRouter::getConnectedUrlParts($this->requestURI);
        $this->controller = isset($url['controller'])?$url['controller']:"";
        $this->action = isset($url['action'])?$url['action']:"";
        $this->params = isset($url['params'])?$url['params']:"";
        $this->get = isset($url['get'])?$url['get']:"";
        $this->method = isset($_SERVER['REQUEST_METHOD'])?$_SERVER['REQUEST_METHOD']:"";
        $this->queryString = isset($_SERVER['QUERY_STRING'])? $_SERVER['QUERY_STRING']:"";
        $this->host = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:"";
        if(isset($_POST)){
            foreach($_POST as $key => $val){
                $this->post[$key] = $val;
            }
        }
        if(isset($_REQUEST)){
            foreach($_REQUEST as $key => $val){
                $this->data[$key] = $val;
            }
        }
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            $this->ajax = true;
        } else {
            $this->ajax = false;
        }
    }
}
?>
