<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ammar
 * Date: 8/9/13
 * Time: 2:31 AM
 * To change this template use File | Settings | File Templates.
 */
NODE::load('NodeHelper', 'View');

class htmlHelper extends NodeHelper{
        private $fetchArray = array();
        /*
         *  the name of the css file present inside the css folder in the noderoot folder.
         * it can be a subfolder with / in it, an extension can or can't be present in it.
         * the $inline variable serves as
         * if its false, the css will be placed where the fetch statement is used. the fetchcss() statement should be used inside the layout or if in the view file after the css() method.
         */
        function htmlHelper($view){
            $this->fetchArray['css'] = array();
            $this->fetchArray['script'] = array();
            $this->fetchArray['meta'] = "";
            parent::__construct($view);
        }

    public function anchor($title, $options = array()){
        $html = "<a ";
        if(isset($options['controller'])){
            $controller = $options['controller'];
            $action = "";
            $params = array();
            if(isset($options['action'])){
                $action = $options['action'];
                unset($options['action']);
            }
            unset($options['controller']);
            if(isset($options['params'])){
                $params = $options['params'];
                if(!is_array($params)){
                    $action .= "/".$params;
                    $params = "";
                }
                unset($options['params']);
            }
            $options['href'] = $this->url($controller, $action, $params);
        }
        $options['title'] = isset($options['title'])?$options['title']:$title;
        $html .= $this->resolveAttributesToString($options);
        $html .= " >".$title;
        $html .= "</a>";
        return $html;
    }


    public function url($controller, $action = "", $params = array()){
        $link = BASE;
        $link .= $controller;
        $link .= ($action=="")?"":"/".$action."/";
        $link .= (empty($params))?"":"/".implode("/",$params);
        return $link;
    }
   /**
     * Creates a link to a css file element.
     *
     * The default inline is false which means the css will be fetched by fetch method
     *
     * ### Usage:
     *
     * Create a link to a css file.
     *
     * `$html->css('style');`
     * `$html->css('style', false, array('rel' => 'Xnode'));`
    *  `echo $html->css('menu_style', true)`;
     *
     * Create an inline css link:
     *
     * `echo $html->css('style', true, array('lang' => 'eng', 'rel' => 'node'));`
     *
     * ### Options:
     *
     * - `cssFile` the css file to be linked, can be with or without extension .css.
     * - `inline` If set to true the css file will be included in place false will only let the fetch() function to link the css there.
     * - `options' can include any html markup attributes but special options will be parsed first like conditionals for IE.
     *
     * @param string $cssFile name of the css file
     * @param boolean $inline the variable which controls whether the css file 
     * will be linked in line of by the fetch statement. true means inline, false means by fetch and fedault is false.
     * @param array $options Array of HTML attributes.  See above for special options.
     * @return string link tag of the css file or none if $inline is true
     * @link
     */
        
        function css($cssFile, $inline = false, array $options = array()){
            if(!$inline){
                array_unshift($this->fetchArray['css'], array('file' => $cssFile, 'options' => $options));
            } else {
                return $this->makeLink($cssFile, "css", $options);
            }
        }

        function script($jsFile, $inline = false, array $options = array()){
            if(!$inline){
                array_unshift($this->fetchArray['script'], array( 'file' => $jsFile, 'options' => $options));
            } else {
                return $this->makeLink($jsFile, "script", $options);
            }
        }
         /**
     * fetches the markup being added by css() method before.
     *
     * The function resturns the string form of all the links added by css(), script() etc statement.
     *
     * ### Usage:
     *
     * `echo $html->fetch('css');`
     * `echo $html->fetch('meta');`
     * `echo $html->fetch('script');`
     * ### Options:
     * @param string $type Name of the element to fetch
     * @return string list of links to css files.
     * @link
     */
        function fetch($type){
            $links = "";
            if($type == "meta"){
                   return $this->fetchArray['meta'];
            }
           foreach($this->fetchArray[$type] as $key => $val){
                    $links .= $this->makeLink($val['file'],$type, $val['options']);
                }
            return $links;
        }
        private function makeLink($fileName, $type, $options = array()){
            switch($type){
                case "css":
                    $link = "";
                    if(isset($options['condition'])){
                        $condition = $options['condition'];
                        unset($options['condition']);
                        $link = "<!--[if ".$condition."]>";
                    }
                    $link .= "<link rel='stylesheet' type='text/css' href='";
                    $link .= CSS.$this->view->resolveFileExtension($fileName,".css");
                    $link .= "' ".$this->resolveAttributesToString($options).$this->tagEnd;
                    if(isset($condition)){
                        $link .= "<![endif]-->";
                    }
                break;

                case "script":
                    $link = "<script type='text/javascript' src='";
                    $link .= JS.$this->view->resolveFileExtension($fileName, ".js");
                    $link .= "' ".$this->resolveAttributesToString($options)." ></script> ";
                    break;
                case 'meta': //fetch meta information
                    break;
                default:
                    break;
            }
            return $link;
        }
      
      /**
     * Returns a doctype declaration
     *
     * The default option is html5
     *
     * ### Usage:
     *
     * Returns a doctype declaration
     *
     * prints a html4.0.1 strict doctype declaration
     * ` echo html->docType('html4.01 strict');`
     * 
     *  prints a html5 doctype declaration
     * ` echo html->docType();`
     *
     *
     * ### Options:
     *
     * - `docType` - the minified name of the doctype declaration 
     *
     * @param string $doctype minified name of the doctype
     * @param boolean $inline the variable which controls whether the css file 
     * @return string the full doctype declaration
     * 
     */
      
      
      public function docType($doctype = "html5"){
          switch($doctype){
              case "html5":
                  $doctype = "<!DOCTYPE html>";
                  $this->tagEnd = " >";
                  break;
              case "html4.01 strict":
                  $doctype = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
                  break;
              case "html4.01 transitional":
                  $doctype = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
                  break;
              case "html4.01 frameset":
                  $doctype = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">';
                  break;
              case "xhtml1.0 strict":
                  $doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
                  break;
              case "xhtml1.0 transitional":
                  $doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
                  break;
              case "xhtml1.0 frameset":
                  $doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">';
                  break;
              case "xhtml1.1":
                  $doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">';
                  break;
              default:
                  $doctype = "<!DOCTYPE html>";
                  break;
          }
          return $doctype."\n";
      }
      
      public function charset(){
          return "<meta charset='UTF-8'>";
      }
      // $options = array ("name" => "author", "content" => "xnode", http-equiv" => "refresh");
      //$options = string "icon"
      public function meta($options, $inline = false){
          $html = "";
          if(is_array($options)){
              $html = "<meta ";
              $html .= $this->resolveAttributesToString($options);
              $html .= $this->tagEnd;
          } else {
              if($options == "icon"){
                  $html .= "<link href=".IMG."favicon.ico"." type='image/x-icon' rel='icon'".$this->tagEnd;
                  $html .= "<link rel='shortcut icon' href='".IMG."favicon.ico' type='image/x-icon'".$this->tagEnd;
              }
          }
          if($inline){
          return $html;
          } else {
              $this->fetchArray['meta'] .= $html;
          }
      }

    public function img($path, $options = ""){
        $html = "<img ";
        $html .= "src = '".IMG.$path."' ".$this->resolveAttributesToString($options);
        $html .= $this->tagEnd;
        return $html;
    }
}