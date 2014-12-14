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
        $this->application->render('map');
    }

    public function bootstrap($template)
    {
        $params = func_get_args();
        $template = $params[0];

        $this->application->layout('bootstrap/' . $template, 'common/layout', array('application' => $this->application));
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
