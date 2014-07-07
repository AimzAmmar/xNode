<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ahmed
 * Date: 01/08/13
 * Time: 10:41
 * To change this template use File | Settings | File Templates.
 * @property NodeView $view;
 * @property NodeController $controller;
 */
class NodeHelper
{
    public $view;
    protected $controller;
    protected $tagEnd = " />";
    public function NodeHelper($view){
        $this->view = $view;
        $this->controller = $this->view->controller;
    }

    protected function resolveAttributesToString($attributes){
        $attr = "";
        if(is_array($attributes)){
            foreach($attributes as $key => $val){
                $attr .= $key."='".$val."' ";
            }
        }
        return $attr;
    }
}
