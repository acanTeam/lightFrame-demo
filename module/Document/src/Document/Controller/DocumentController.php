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
        $docsInfos = $this->documentTool->getDocsInfos();

        $pathInfo = trim($_SERVER['REQUEST_URI'], '/');
        $pathInfos = explode('/', $pathInfo);

        $appParam = array_shift($pathInfos);
        if ($appParam != 'document') {
            throw new \Exception("url path error, '{$appParam}' isn't a valid url path");
        }

        $info = $this->documentTool->getDocsInfo($pathInfos);
        $this->baseUrl = $this->application->domain . 'document/' . $info['docs'] . '/';
  
        $this->currentTitle = '';
        $data = array(
            'breadCrumb' => $this->_getBreadCrumb($info['structureInfos']),
            'navigation' => $this->_getNavigation($info['structureInfos']),
            'fileInfo' => $this->_getContent($info['markdownFile']),
            'application' => $this->application,
            'currentTitle' => $this->currentTitle,
        );
        $data = array_merge($data, $info);

        $this->application->layout('document', 'docs_layout', $data);
    }

    private function _getBreadCrumb($structureInfos)
    {
        $breadCrumb = '';
        $separator = ' <i class="glyphicon glyphicon-chevron-right"></i> ';

        $infos = $structureInfos;
        while (!empty($infos)) {
            $subInfos = false;
            foreach ($infos as $key => $info) {
                if (isset($info['isCurrent']) && $info['isCurrent']) {
                    $this->currentTitle = $info['title'];
                    $breadCrumb .= $separator . $info['title'];
                    $subInfos = isset($info['subElems']) ? $info['subElems'] : false;
                    break ;
                }
            }
            $infos = $subInfos;
        }
        $breadCrumb = trim($breadCrumb, $separator);

        return $breadCrumb;
    }

    private function _getContent($file)
    {
        $contentSource = file_get_contents($file);
        $uploadUrl = str_replace($this->docsPath, 'document/', dirname($file)) . '/';
        $contentSource = str_replace('#UPLOAD_URL#', $this->application->configCommon['uploadUrl'] . $uploadUrl, $contentSource);
        $parsedown = new \Document\Util\Parsedown();
        $content = $parsedown->text($contentSource);

        $fileInfo = array(
            'contentSource' => $contentSource, 
            'content' => $content,
            'status' => stat($file),
        );

        return $fileInfo;
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
                //$openClass = isset($info['isCurrent']) && $info['isCurrent'] ? ' class="open"' : '';
                $elem = "<a href='javascript: void(0);' class='folder'>{$info['title']}</a>";
                // "<a href='javascript:void(0);' class='aj-nav folder'>{$node->title}</a>";
                $subNavigation = $this->_getNavigation($info['subElems'], $currentUrl, $currentPathInfo . '/');

                $navigation .= "<li class='open'>{$elem}{$subNavigation}</li>";
            }
        }

        return $navigation . '</ul>';
    }    
}
