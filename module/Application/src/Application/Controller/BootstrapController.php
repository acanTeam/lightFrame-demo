<?php
namespace Application\Controller;

use \Light\Mvc\Controller\ControllerAbstract as ControllerAbstract;

class BootstrapController extends ControllerAbstract
{
    public function __construct()
    {
        $this->currentModule = 'Application';
        parent::__construct();
    }

    public function demo()
    {
        $params = func_get_args();
        $demo = isset($params[0]) ? $params[0] : '';
        $infos = $this->_getDemoInfos();
        $currentDemo = in_array($demo, array_keys($infos)) ? 'demo/' . $demo : 'default';

        $navbarInfos = $this->_getNavbar();
        $navbarContent = $this->_getNavbarContent($navbarInfos);
        $data = array(
            'method' => 'demo',
            'navbarContent' => $navbarContent,
            'demo' => $demo,
            'currentDemo' => $currentDemo,
            'infos' => $infos,
            'application' => $this->application
        );

        $this->application->layout('bootstrap/' . $currentDemo, 'common/layout', $data);
    }

    public function plugin()
    {
        $navbarInfos = $this->_getNavbar();
        $data = array(
            'navbarInfos' => $navbarInfos,
            'application' => $this->application
        );

        $this->application->layout('bootstrap/plugin', 'common/google_layout', $data);
    }

    protected function _getDemoInfos()
    {
        $demoInfos = array(
            'blog' => array(
                'description' => '',
            ), 
            'carousel' => array(
                'description' => 'Customize the navbar and carousel, then add some new components.',
            ), 
            'cover' => array(
                'description' => 'A one-page template for building simple and beautiful home pages.',
            ), 
            'dashboard' => array(
                'description' => '',
            ), 
            'grid' => array(
                'description' => 'Multiple examples of grid layouts with all four tiers, nesting, and more.',
            ),
            'jumbotron' => array(
                'description' => '',
            ), 
            'jumbotron-narrow' => array(
                'description' => 'Build a more custom page by narrowing the default container and jumbotron.',
            ), 
            'justified-nav' => array(
                'description' => '',
            ),
            'navbar' => array(
                'description' => '',
            ), 
            'navbar-fixed-top' => array(
                'description' => '',
            ), 
            'navbar-static-top' => array(
                'description' => '',
            ),
            'non-responsive' => array(
                'description' => '',
            ), 
            'offcanvas' => array(
                'description' => '',
            ), 
            'signin' => array(
                'description' => '',
            ), 
            'starter-template' => array(
                'description' => 'Nothing but the basics: compiled CSS and JavaScript along with a container.',
            ),
            'sticky-footer' => array(
                'description' => '',
            ), 
            'sticky-footer-navbar' => array(
                'description' => '',
            ), 
            'theme' => array(
                'description' => '',
            ),
        );
        return $demoInfos;
    }

    protected function _getNavbar()
    {
        $navbarInfos = array(
            array(
                'name' => '官方DEMO', 
                'url' => $this->application->domain . 'bootstrap', 
            ),
            array(
                'name' => '前端资源', 
                'url' => $this->application->domain . 'bootstrap/plugin', 
            ),
            /*'multiple_5' => array(
                'name' => '相关外部资源',
                'menus' => array(
                    array('name' => 'Bootstrap官网', 'url' => 'http://getbootstrap.com/'),
                    array('name' => 'Bootstrap中文网', 'url' => 'http://www.bootcss.com/'),
                    array('name' => 'git-bootstrap', 'url' => ''),
                    array('name' => 'git-bootcss', 'url' => ''),
                    array('name' => 'git-bootswatch', 'url' => ''),
                )
            ),*/
        );
        return $navbarInfos;
    }
}
