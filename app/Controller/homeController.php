<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ahmed
 * Date: 29/07/13
 * Time: 04:17
 * To change this template use File | Settings | File Templates.
 * @property Model $Test;
 */
class homeController extends Controller
{
    function index($id=1, $ids=2){
        echo "in Controller index <br />";
        $this->title = "Xnode";
        echo "Id : ".$id." Ids :".$ids;
        echo $this->Test->check();

    }

    function check(){
        //$this->viewData['name'] = 'ahmed';
        $this->view = "user/index";
        $this->layout = 'default';
        $this->title = "Test Pages";
    }

}
