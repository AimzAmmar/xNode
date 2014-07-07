<?php
NODE::load('NodeController','Controller');
/**
 The name of the Controller should have appended 'Controller' in it, however
 * you can have it without 'Controller' appended to it but it will make the class differentiation
 * easy. The name variable is optional.
 */
class Controller extends NodeController
{
    public $name; //do set the name of your Controller if PHP <= 5

    function beforeFilter(){

    }
}
