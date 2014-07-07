<?php
/**
 * cyrish.
 * User: Ahmed
 * Date: 12/23/13
 * Time: 12:01 AM
 * @property User $User
 */

class userController extends Controller
{

    public $access = array('admin', 'superAdmin');

    public function index()
    {
        $this->title = "Cyrish :: Admin";
    }

    public function users()
    {
        $this->title = "Cyrish :: Admin:Users Management";
    }

    public function account()
    {
        $this->title = "Cyrish :: Admin: My Account";
        $data = array("FirstName","LastName","Gender");
        $conditions = array("FirstName = ? ", array("waseem"));
        $this->viewData['query'] = $this->User->select($data, $conditions);
    }
}