<?php
namespace Document\Controller;

use \Light\Mvc\Controller\ControllerAbstract as ControllerAbstract;
use Document\Util\DocumentTool;

class DocumentController extends ControllerAbstract
{
    public function __construct()
    {
        $this->currentModule = 'Document';
        parent::__construct();

        $this->localConfig = require $this->modulePath . '/config/local.php';
        $this->docsPath = $this->localConfig['docsPath'];
        $this->documentTool = new DocumentTool($this->docsPath);
    }

    public function index()
    {
        $docsInfos = $this->documentTool->getDocsInfos();

        $navbarInfos = $this->_getNavbar();
        $navbarContent = $this->_getNavbarContent($navbarInfos);
        $data = array(
            'navbarContent' => $navbarContent,
            'infos' => $docsInfos,
            'application' => $this->application,
        );

        $this->application->layout('documentindex', 'common/layout', $data);
    }

    public function docs()
    {
        $pathInfo = trim($_SERVER['REQUEST_URI'], '/');
        $pathInfos = explode('/', $pathInfo);

        $appParam = array_shift($pathInfos);
        if ($appParam != 'document') {
            throw new \Exception("url path error, '{$appParam}' isn't a valid url path");
        }

        $info = $this->documentTool->getDocsInfo($pathInfos);
        $this->baseUrl = $this->application->domain . 'document/' . $info['docs'] . '/';
        
        $data['breadcrumb'] = '';//$this->_getBreadcrumb($structureInfos, $currentKey);
        $data['navigation'] = $this->_getNavigation($info['structureInfos']);
        $data['content'] = $this->_getContent($info['markdownFile']);//file, $options);

        $this->application->layout('document', 'docs_layout', array('data' => $data, 'application' => $this->application));
    }

    private function _getBreadcrumb($file, $options)
    {
        $parents = $options['isMulLanguage'] && !empty($file->parents) ? array_splice($file->parents, 1) : $file->parents; 
        if (empty($parents)) {
            return $file->title;
        }

        $title = '';
        $separator = ' <i class="glyphicon glyphicon-chevron-right"></i> ';
        foreach ($parents as $node) {
            $url = $options['base_page'] . $node->getUrl();
            $title .= "<a href='{$url}'>{$node->title}</a>{$separator}";
        }
        $title .= $file->title;

        return $title;
    }

    private function _getContent($file)
    {
        $contentSource = file_get_contents($file);
        $parsedown = new \Document\Util\Parsedown();
        $content = $parsedown->text($contentSource);

        return array('contentSource' => $contentSource, 'content' => $content);
    }

    private function _getNavigation($structureInfos, $currentUrl = '', $pathInfo = '')
    {
        $navigation = '<ul class="nav nav-list">';

        foreach ($structureInfos as $key => $info) {
            $currentPathInfo = $pathInfo . $key;
            $link = $this->baseUrl . $currentPathInfo;

            if (!isset($info['subElems'])) {
                $activeClass = isset($info['isCurrent']) && $info['isCurrent'] ? ' class="active"' : '';
                $navigation .= "<li {$activeClass}><a href='{$link}'>{$info['title']}</a></li>";
            } else {
                $openClass = ' class="open"';//isset($info['isCurrent']) && $info['isCurrent'] ? ' class="open"' : '';
                $elem = "<a href='{$link}' class='folder'>{$info['title']}</a>";
                // "<a href='javascript:void(0);' class='aj-nav folder'>{$node->title}</a>";
                $subNavigation = $this->_getNavigation($info['subElems'], $currentUrl, $currentPathInfo . '/');

                $navigation .= "<li {$openClass}>{$elem}{$subNavigation}</li>";
            }
        }

        return $navigation . '</ul>';
    }    
}
