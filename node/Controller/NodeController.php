<?php
NODE::load('NodeView','View');
/**
 * @property NodeRequest $request The Node Request object passed by dispatcher
 */
class NodeController
{
    var $viewData = array();

    var $layout = "";

    var $view = "";

    var $name;
    
    var $title = '';

    protected $viewObj;

    public $request;

    protected $response;

    protected $autoRender = true;

    public $model = array();

    function NodeController(&$nodeRequest = null){
        if(empty($this->name)){
            $className = get_called_class();
            $idx = strpos($className,'Controller');
            if($idx !== false){
             $className = substr($className, 0, $idx);
            }
            $this->name = $className;
        }
        if(!empty($this->model)){

        }
        $this->request = $nodeRequest;
        $this->response = new NodeResponse();
    }

    /*
     *  __get magic function basically used to load models, the model name should be same as of the model class & filname except
     *  in camelcase format.  like
     *  model User => login
     *  model UserLogin => userLogin
     *  model userLogin => userLogin
     *
     *  typically its useful to make your model in camelcase classname and file name and the same variable will be used to
     * access this model with $ sign obviosuly
     */
    public function __get($varName)
    {
        if (!isset($this->model[$varName])) {
            $className = ucfirst($varName);
            if(NODE::load($className, MODEL, false)){
                $this->__set($varName, new $varName());
            } else {
                //missing model error!
                echo "model not present";
                exit();
            }
        }
        return $this->model[$varName];
    }

    public function __set($varName, $value)
    {
        $this->model[$varName] = $value;
    }

    // function that would be implicitly called by the Controller to render the View
    // this function checks if a view is present, checks the default view and calls
    //render events.
    private function renderView(){
        $this->beforeRender();
        $this->viewObj = new NodeView($this->view, $this->layout, $this->viewData, $this->title, $this);
        $this->viewObj->render();
        $this->afterRender();
    }

    public function render(){
        $this->afterFilter();
        $this->beforeRender();
        $this->viewObj = new NodeView($this->view, $this->layout, $this->viewData, $this->title, $this);
        $this->viewObj->render();
        $this->afterRender();
    }
    //private function to perform the action; it checks if a method is present in place of the 
    //action and also calls to filter functions
    
    public function performAction(){
        $this->beforeFilter();
        $action = $this->setAction();
        call_user_func_array(array($this, $action), $this->request->params);
        $this->afterFilter();
        if($this->autoRender){
            $this->renderView();
        }
    }
    
    private function setAction(){
        $action = $this->request->action;
        if(!isset($action) || $action == ""){
            $action = "index";
        }
        if(!method_exists($this, $action)){
            //through error, custom error missing action
            echo "wrong method!!!";
            exit();
        }
        $this->request->action = $action;
        return $action;
    }
    //before render hook for the View, the array of data $this->viewData, layout and View is avialable here
    //and even the View can be changed here.
    public function beforeRender(){
    }
    //after render function may help in setting or unsetting cert ain variables for instance clearing the viewstate and or sessions. flash sessions are cleared here.
    public function afterRender(){
    }
    //a controller function to be called before each action of that controller is going to run.
    public function beforeFilter(){

    }
    //a controller function to be called after each action of the controller has done execution
    public function afterFilter(){

    }
}
