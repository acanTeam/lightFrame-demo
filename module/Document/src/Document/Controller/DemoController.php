<?php
namespace Document\Controller;

use \Light\Mvc\Controller\ControllerAbstract as ControllerAbstract;
use Document\Util\DocsTool as DocsTool;

class DemoController extends ControllerAbstract
{
    public function __construct()
    {
        parent::__construct();

        $this->docsPath = 'E:\tmp\docs';
    }

    public function index()
    {
        $docsTool = new DocsTool(array('basePath' => $this->docsPath));
        $docsTool->initialize();
        $page = $docsTool->handle_request($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], $_REQUEST);
        $page->display();

    }
  }
