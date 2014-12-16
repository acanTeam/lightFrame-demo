<?php
namespace Application\Controller;

use \Light\Mvc\Controller\ControllerAbstract as ControllerAbstract;

class BootstrapController extends ControllerAbstract
{
    public function __construct()
    {
        parent::__construct();
    }

    public function demo()
    {
        $params = func_get_args();
        $currentDemo = isset($params[0]) ? $params[0] : '';
        $infos = $this->_getDemoInfos();
        $currentDemo = in_array($currentDemo, array_keys($infos)) ? 'demo/' . $currentDemo : 'default';

        $navbarInfos = $this->_getNavbar();
        $navbarContent = $this->_getNavbarContent($navbarInfos);
        $data = array(
            'method' => 'demo',
            'navbarContent' => $navbarContent,
            'currentDemo' => $currentDemo,
            'infos' => $infos,
            'application' => $this->application
        );

        $this->application->layout('bootstrap/' . $currentDemo, 'common/layout', $data);
        //$this->application->render('bootstrap/' . $currentDemo, $data);
    }

    public function example()
    {
    }

    protected function _getDemoInfos()
    {
        $demoInfos = array(
            'blog' => array(
                'name' => '',
                'author' => '',
                'description' => '',
            ), 
            'carousel' => array(
                'name' => '',
                'author' => '',
                'description' => '',
            ), 
            'cover' => array(
                'name' => '',
                'author' => '',
                'description' => '',
            ), 
            'dashboard' => array(
                'name' => '',
                'author' => '',
                'description' => '',
            ), 
            'grid' => array(
                'name' => '',
                'author' => '',
                'description' => '',
            ),
            'jumbotron' => array(
                'name' => '',
                'author' => '',
                'description' => '',
            ), 
            'jumbotron-narrow' => array(
                'name' => '',
                'author' => '',
                'description' => '',
            ), 
            'justified-nav' => array(
                'name' => '',
                'author' => '',
                'description' => '',
            ),
            'navbar' => array(
                'name' => '',
                'author' => '',
                'description' => '',
            ), 
            'navbar-fixed-top' => array(
                'name' => '',
                'author' => '',
                'description' => '',
            ), 
            'navbar-static-top' => array(
                'name' => '',
                'author' => '',
                'description' => '',
            ),
            'non-responsive' => array(
                'name' => '',
                'author' => '',
                'description' => '',
            ), 
            'offcanvas' => array(
                'name' => '',
                'author' => '',
                'description' => '',
            ), 
            'signin' => array(
                'name' => '',
                'author' => '',
                'description' => '',
            ), 
            'starter-template' => array(
                'name' => '',
                'author' => '',
                'description' => '',
            ),
            'sticky-footer' => array(
                'name' => '',
                'author' => '',
                'description' => '',
            ), 
            'sticky-footer-navbar' => array(
                'name' => '',
                'author' => '',
                'description' => '',
            ), 
            'theme' => array(
                'name' => '',
                'author' => '',
                'description' => '',
            ),
        );
        return $demoInfos;
    }

    protected function _getNavbar()
    {
        $navbarInfos = array(
            'index' => array('name' => 'Bootstrap', 'url' => 'demo'),
            'menus' => array(
                array(
                    'name' => 'Bootstrap官网', 
                    'url' => 'http://getbootstrap.com/'
                ),
                array(
                    'name' => 'Bootstrap正文网', 
                    'url' => 'http://www.bootcss.com/'
                ),
                array(
                    'name' => '官方DEMO', 
                    'url' => 'demo'
                ),
                array(
                    'name' => '经典案例', 
                    'url' => 'example'
                ),
                'multiple_5' => array(
                    'name' => 'github资源',
                    'menus' => array(
                        array('name' => 'bootstrap', 'url' => ''),
                        array('name' => 'bootcss', 'url' => ''),
                        array('name' => 'bootswatch', 'url' => ''),
                    )
                ),
            ),
        );
        return $navbarInfos;
    }
}
