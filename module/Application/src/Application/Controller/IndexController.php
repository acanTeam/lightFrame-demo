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

    public function bootstrap()
    {
        $params = func_get_args();
        $currentTemplate = isset($params[0]) ? $params[0] : '';
        $templates = array(
            'blog', 'carousel', 'cover', 'dashboard', 'grid',
            'jumbotron', 'jumbotron-narrow', 'justified-nav',
            'nav', 'navbar', 'navbar-fixed-top', 'navbar-satic-top',
            'non-responsive', 'offcanvas', 'signin', 'starter-template',
            'sticky-footer', 'sticky-footer-navbar', 'theme', 'nav'
        );
        $currentTemplate = in_array($currentTemplate, $templates) ? $currentTemplate : 'default';
        $data = array(
            'currentTemplate' => $currentTemplate,
            'templates' => $templates,
            'application' => $this->application
        );

        $this->application->layout('bootstrap/' . $currentTemplate, 'common/layout', $data);
    }
}
