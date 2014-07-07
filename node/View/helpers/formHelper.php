<?php
/**
 * cyrish.
 * User: Ahmed
 * Date: 12/24/13
 * Time: 3:06 AM
 * 
 */
NODE::load('NodeHelper', 'View');

class formHelper extends NodeHelper{
    private $model;
    private $type;
    private $fieldPrefix;
    private $fieldWrapper;

    public function formHelper($view){
        parent::__construct($view);
    }

    public function getModel($modelName){
        if(NODE::load($modelName, MODEL, false)){
            return new $modelName();
        } else {
            echo "Invalid Model";
            exit();
        }
    }

    public function generateForm($model = "", $data = array(), $options = array()){
        $form = "<form ";
        if(!is_object($model)){
            if(isset($this->controller->model[$model])){
                $model = $this->controller->model[$model];
            } else {
                $model = $this->getModel($model);
            }
        }

        $form .= ">";
        return $form;
    }

    public function form($options = array()){
        $options['action'] = (isset($options['action']))?$options['action']:$this->controller->request->requestURI;
        $options['method'] = isset($options['method'])?$options['method']:"POST";
        $form = $this->makeMarkup('form', $options);
        return $form;
    }

    public function input($name, $options = array()){
        $markup = "";
        $type = isset($options['type'])?$options['type']:"text";
        $options['name'] = $name;
        switch($type){
            case "text":
            case "password":
            case "submit":
                $options['type'] = $type;
                $markup = $this->makeMarkup('input', $options);
                break;
        }
        return $markup;
    }
    /*
     * $data ['value'] => 'option'
     * <option value='value'>option</option>
     */
    public function select($name, $data = array(), $options = array()){

    }



    public function filterOptions($options = array()){

    }

    public function end(){
        return "</form>";
    }
    private function makeMarkup($tag = 'input', $attributes = array()){
        $html = "<";
        $html .= $tag;
        $html .= " ".$this->resolveAttributesToString($attributes);
        $html .= $this->tagEnd;
        return $html;
    }
}