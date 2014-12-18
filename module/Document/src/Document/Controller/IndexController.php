<?php
namespace Document\Controller;

use \Light\Mvc\Controller\ControllerAbstract as ControllerAbstract;

class IndexController extends ControllerAbstract
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->application->render('map');
        $this->application->layout('index', 'common/layout');
    }
}
