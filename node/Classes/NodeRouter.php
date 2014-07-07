<?php
class NodeRouter{

        /*
         * An array containing the app wide connections, the key is the URL to connect and the value is another                 associative array with Controller and action. another parameter of the array could be 'params' with
         * a string or array containing the parameters. example:
         *
         *  $connections['/'] = array('Controller' => 'home', 'action' => 'register',
         *          'parameters' => array('id' => '0',
         *                                  'status' => 'new'
         *                               )
         *          );
         *
         * OR
         *  $connections['/'] = array('Controller' => 'home', 'action' => 'register',
         *          'parameters' => '1/2/3/4'
         *          );
         */
        private static $connections = array();

        public static function route(){
            echo "calling in route";
        }

        public static function connect($url, $controller, $action = 'index', $options = array()){
                if($url == '/'){
                    $url = BASE;
                }
                self::$connections[$url] = array(
                    'controller' => $controller,
                    'action' => $action,
                    'options' => $options);
        }

        public static function getConnectedUrlParts($url){
            if($url == null || $url == ''){
                $url = $_SERVER['REQUEST_URI'];
            }
            $urlParts = self::parseURL($url);
            if(isset(self::$connections[$url])){
                $urlParts = array_merge($urlParts,self::$connections[$url]);
            }
            return $urlParts;
        }
        public static function parseURL($url = null){
            if($url == null || $url == ''){
                $url = $_SERVER['REQUEST_URI'];
            }
            $url_parts = array();
            $srequest =  $url;
            $srequest = substr($srequest, strlen(BASE));
            $request = explode("/", $srequest);
            if(isset($request[0])){
                $url_parts['controller'] = $request[0];
            }
            if(isset($request[1])){
                $url_parts['action'] = $request[1];
            }
            $params = array();
            if(isset($request[2])){
                for ($i = 2; $i < sizeof($request); $i++){
                    array_push($params, $request[$i]);
                }
            }
            $url_parts['params'] = $params;
            if(isset($_GET)){
                $get = array();
                foreach($_GET as $key => $val){
                    $get[$key] = $val;
                }
             $url_parts['get'] = $get;
            }
            return $url_parts;
        }
}
?>
