<?php
namespace Application\Controller;

use \Light\Mvc\Controller\ControllerAbstract as ControllerAbstract;

class IndexController extends ControllerAbstract
{
    public function __construct()
    {
        $this->currentModule = 'Application';
        parent::__construct();
    }

    public function index()
    {
        $navbarInfos = $this->_getNavbar();
        $data = array(
            'navbarInfos' => $navbarInfos,
            'application' => $this->application,
            'infos' => $this->_getInfos(),
        );
        
        $this->application->layout('index', 'index_layout', $data);
    }

    public function form()
    {
        $this->application->layout('form', 'common/layout', array('application' => $this->application));
    }

    public function map()
    {
        $this->application->render('map');
    }

    private function _getInfos()
    {
        $docsElems = array(
            'structure' => array(
                'name' => '数据结构和算法',
                'url' => '',
                'thumb' => 'http://www.bootcss.com/p/google-bootstrap/assets/img/example-sites/kippt.png',
            ),
            'structure1' => array(
                'name' => '数据结构和算法',
                'url' => '',
                'thumb' => 'http://www.bootcss.com/p/google-bootstrap/assets/img/example-sites/kippt.png',
            ),
            'structure2' => array(
                'name' => '数据结构和算法',
                'url' => '',
                'thumb' => 'http://www.bootcss.com/p/google-bootstrap/assets/img/example-sites/kippt.png',
            ),
            'structure3' => array(
                'name' => '数据结构和算法',
                'url' => '',
                'thumb' => 'http://www.bootcss.com/p/google-bootstrap/assets/img/example-sites/kippt.png',
            ),
        );

        $infos = array(
            'docs' => array(
                'title' => '文档系统',
                'url' => 'docs',
                'description' => '基于markdown轻量级标记语言，把工作和学习过程中的知识体系结构以文档的形式表现出来。',
                'elems' => $docsElems
            ),
            'bootstrap' => array(
                'title' => 'bootstrap',
                'url' => 'bootstrap',
                'description' => '基于boostrap和bootstrap中文网，搜集整理bootstrap能为我所用的相关资源',
                'elems' => $docsElems
            ),
            'lightFrame' => array(
                'title' => 'lightFrame框架',
                'url' => 'lightFrame',
                'description' => '自主研发的基于PHP5.3以上版本的PHP轻量级框架',
                'elems' => $docsElems
            ),
        );

        return $infos;
    }
}
