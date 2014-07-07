<?php
/**
 * cyrish.
 * User: Ahmed
 * Date: 12/25/13
 * Time: 5:50 AM
 * 
 */

class loginController extends Controller {
    public $name = 'login';

    public function index(){
        if(!empty($this->request->data['cyrishEmail']) && !empty($this->request->data['cyrishPassword'])){
            //$this->response->redirect('http://www.google.com');
            $this->viewData['message'] = "Got The Form";
            $this->layout = "blank";
            $this->view = "ajax/ajax";
        } else {
            $this->title = "Cyrish :: Login";
            $this->layout = "login";
        }
    }
} 