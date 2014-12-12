<?php
namespace Application\Controller;

use \Light\Mvc\Controller\ControllerAbstract as ControllerAbstract;

class IndexController extends ControllerAbstract
{
    public function __construct()
    {
        parent::__construct();
        //echo 'init index';
    }

    public function map()
    {
        $this->application->render('map.html');
    }

    public function hello()
    {
        echo 'hello';
    }

    public function world()
    {
        echo 'world';
    }
}
