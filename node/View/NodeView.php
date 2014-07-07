<?php
/**
 * @property NodeHelper
 * @property htmlHelper $html The core Node html Helper
 * @property formHelper $form;
 *
 */
class NodeView
{
    public $name;

    public $layout;

    public $viewData;

    public $title;

    public $controller;

    public $helper = array();

    // check psuedo constructor

    function NodeView($viewName, $viewLayout, $viewData, $layoutTitle, $controller)
    {
        $this->layout = $viewLayout;
        $this->name = $viewName;
        $this->viewData = $viewData;
        $this->controller = $controller;
        $this->title = $layoutTitle;
    }

    public function __get($varName)
    {
        if (!isset($this->helper[$varName])) {
            $className = $varName . "Helper";
            if (NODE::load($className, 'View/helpers')) {
                $this->__set($varName, new $className($this));
            } else {
                //missing helper error!
                trigger_error("Helper " . $varName . " Not Present!");
            }
        }
        return $this->helper[$varName];
    }

    public function __set($varName, $value)
    {
        $this->helper[$varName] = $value;
    }


    public function render()
    {
        extract($this->viewData);
        $viewPath = $this->getViewPath();
        $layoutPath = $this->getLayoutPath();
        ob_start();
        include_once($viewPath);
        $content = ob_get_contents();
        ob_end_clean();
        include_once($layoutPath);
    }

    public function getLayoutPath()
    {
        $layout = $this->layout;
        if (!isset($layout) || $layout == "") {
            $layout = "default";
        }
        $layoutPath = VIEW . DS . "layouts" . DS . $this->resolveFileExtension($layout);
        if (!file_exists($layoutPath)) {
            // through missing layout error
            echo "layout not found";
            exit();
        } else {
            return $layoutPath;
        }
    }

    public function getViewPath()
    {
        $view = $this->name;
        if (!isset($this->name) || $this->name == "") {
            $view = $this->controller->request->action;
        }
        $viewPath = VIEW . DS . "views" . DS;
        $parr = explode("/", $view);
        if ($parr[0] != $view) {
            for ($i = 0; $i < sizeof($parr) - 1; $i++) {
                $viewPath .= $parr[$i] . DS;
            }
            $viewPath .= $this->resolveFileExtension($parr[sizeof($parr) - 1]);
        } else {
            $viewPath .= $this->controller->name . DS . $this->resolveFileExtension($view);
        }
        if (!file_exists($viewPath)) {
            // through missing view error
            echo "view not found";
            exit();
        } else {
            return $viewPath;
        }
    }


    public function element($element, $verbose = false)
    {
        $elementPath = VIEW.DS."elements".DS.$this->resolveFileExtension($element);
        if(!file_exists($elementPath)){
                if($verbose){
                    //through missing element error
                    echo "Element not found";
                    exit();
                } else {
                    echo "";
                }
        } else {
            ob_start();
            include_once($elementPath);
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
    }

    /*
     * The function checks if the file is being given by the extension or not, if the login entered
     * the extension the filename is returned as it is otherwise it will append the extension.
     * the function is mainly used internally for the view for loading css,js,ntp files.
     */
    public function resolveFileExtension($file, $ext = ".ntp")
    {
        $extsize = -(strlen($ext));
        if (substr($file, $extsize) == $ext) {
            return $file;
        } else {
            return $file . $ext;
        }
    }
}
