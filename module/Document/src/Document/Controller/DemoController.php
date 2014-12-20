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
        $docsTool = new DocsTool($this->docsPath);
        $docsTool->initialize();
        $data = $docsTool->handleRequest($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], $_REQUEST);


        $this->application->layout('document', 'docs_layout', array('page' => $page, 'application' => $this->application));
        //$page->display();
    }

    private function _getNavigation($tree, $path, $current_url, $base_page, $mode)
    {
        $nav = '<ul class="nav nav-list">';
        $nav .= $this->_buildNavigation($tree, $path, $current_url, $base_page, $mode);
        $nav .= '</ul>';
        return $nav;
    }

    private function _buildNavigation($tree, $path, $current_url, $base_page, $mode) {
        $nav = '';
        foreach ($tree->value as $node) {
        	$url = $node->uri;
            $link = ($path === '') ? $url : $path . '/' . $url;

            if ($node->type === \Document\Util\DirectoryEntry::FILE_TYPE) {
                if ($node->value === 'index') {
                    continue;
                }

                $activeClass = $currentUrl == $link ? ' class="active"' : '';
                $nav .= "<li {$activeClass}><a href='{$basePage}{$link}'>{$node->title}</a></li>";
            } else {
                $nav .= '<li';
                $openClass = strpos($current_url, $link) === 0 ? ' class="open"' : '';
                $nav .= ">";
                if ($mode === \Document\Util\DocsTool::STATIC_MODE) {
                    $link .= '/index.html';
                }
                if ($node->indexPage) {
                    $nav .= '<a href="' . $base_page . $link . '" class="folder">' .
                    $node->title . '</a>';
                } else {
                    $nav .= '<a href="#" class="aj-nav folder">' . $node->title . '</a>';
                }
                $nav .= '<ul class="nav nav-list">';
                $new_path = ($path === '') ? $url : $path . '/' . $url;
                $nav .= $this->_buildNavigation($node, $new_path, $current_url, $base_page, $mode);
                $nav .= '</ul></li>';
            }
        }

        return $nav;
    }
    
    private function get_breadcrumb_title($page, $basePage)
    {
        $title = '';
        $breadcrumbTrail = $page['breadcrumb_trail'];
        $separator = ' <i class="glyphicon glyphicon-chevron-right"></i> ';
        foreach ($breadcrumbTrail as $key => $value) {
            $title .= '<a href="' . $basePage . $value . '">' . $key . '</a>' . $separator;
        }
        if ($page['filename'] === 'index' || $page['filename'] === '_index') {
            if ($page['title'] != '') $title = substr($title, 0, -1 * strlen($separator));
        } else {
            $title .= '<a href="' . $base_page . $page['request'] . '">' . $page['title'] . '</a>';
        }

        return $title;
    }
}
