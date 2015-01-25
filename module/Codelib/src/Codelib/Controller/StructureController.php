<?php
namespace Codelib\Controller;

use \Light\Mvc\Controller\ControllerAbstract as ControllerAbstract;

class StructureController extends ControllerAbstract
{
    public function __construct()
    {
        $this->currentModule = 'Codelib';
        parent::__construct();

        $this->configPath = $this->modulePath . '/config/';
    }

    public function index()
    {

    }
}
