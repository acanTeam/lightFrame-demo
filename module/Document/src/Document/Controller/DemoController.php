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
        $this->docsTool = new DocsTool($this->docsPath);
    }

    public function index()
    {
        $currentDocs = 'demo';
        $statusInfo = $this->docsTool->initialize($currentDocs);

        $this->docsTool->initialize($currentDocs);
        $info = $this->docsTool->handleRequest($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], $_REQUEST);
        $file = $info['file'];
        $options = $info['options'];
        $tree = $info['tree'];

        $this->_getBaseInfo($file, $options);
        $data['breadcrumb'] = $this->_getBreadcrumb($file, $options);
        $data['navigation'] = $this->_getNavigation($options, $tree);
        $data['content'] = $this->_getContent($file, $options);

//print_r($this->docsTool);
        $this->application->layout('document', 'docs_layout', array('data' => $data, 'application' => $this->application));
        //$page->display();
    }

    private function _getBaseInfo(& $file, & $options)
    {
        $options['homePage'] = $file->title === 'index' ? $file->filename === '_index' : false;

        $isMulLanguage = isset($options['languages']) && !empty($options['languages']);
        if ($isMulLanguage && !empty($file->parents)) {
            reset($file->parents);
            $options['currentLanguage'] = current($file->parents);
        }

        if ($file->title === 'index') {
            $parentDirSize = $isMulLanguage ? 2 : 1;
            if (count($file->parents) >= $parentDirSize) {
                $parent = end($file->parents);
                $file->title = $parent->title;
            } else {
                $file->title = $options['title'];
            }
        }
        $options['isMulLanguage'] = $isMulLanguage;
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

    private function _getContent($file, $options)
    {
        $contentSource = file_get_contents($file->localPath);
        $parsedown = new \Document\Util\Parsedown();
        $content = $parsedown->text($contentSource);

        return array('contentSource' => $contentSource, 'content' => $content);
        
        $options = $this->options;
        if ($options['request'] === $options['index_key']) {
            if ($options['multilanguage']) {
                foreach ($options['languages'] as $key => $name) {
                    $entry_page[utf8_encode($name)] = utf8_encode($options['base_page'] . $options['entry_page'][$key]->get_url());
                }
            } else $entry_page['View Documentation'] = utf8_encode($options['base_page'] . $options['entry_page']->uri);
        } else if ($options['file_uri'] === 'index')
            $entry_page[utf8_encode($options['entry_page']->title)] = utf8_encode($options['base_page'].
                $options['entry_page']->get_url());
        $page['entry_page'] = (isset($entry_page)) ? $entry_page : null;

        $page['filename'] = $this->filename;
        $page['path'] = $this->path;
        $page['request'] = utf8_encode($options['request']);
        $page['modified_time'] = filemtime($this->path);
        $page['markdown'] = $this->content;
        $page['content'] = $Parsedown->text($this->content);
        $page['file_editor'] = $options['file_editor'];

        return static::$template->get_content($page, $options);
    }

    private function _getNavigation($options, $tree)//$tree, $path, $current_url, $base_page, $mode)
    {
        $navigation = '<ul class="nav nav-list">';
        $languagePath = !empty($options['currentLanguage']) ? $options['currentLanguage'] . '/' : '';

        foreach ($tree->value as $node) {
        	$path = $languagePath . $node->uri;
            $link = $options['base_page'] . $path;
            if ($options['mode'] === \Document\Util\DocsTool::STATIC_MODE) {
                $link .= '/index.html';
            }

            if ($node->type === \Document\Util\DirectoryEntry::FILE_TYPE) {
                if ($node->value === 'index') {
                    continue;
                }

                $activeClass = $options['request'] == $path ? ' class="active"' : '';
                $navigation .= "<li {$activeClass}><a href='{$link}'>{$node->title}</a></li>";
            } else {
                $openClass = strpos($options['request'], $link) === 0 ? ' class="open"' : '';
                $elem = $node->indexPage ? "<a href='{$link}' class='folder'>{$node->title}</a>" :
                    "<a href='javascript:void(0);' class='aj-nav folder'>{$node->title}</a>";
                $subNavigation = $this->_getNavigation($options, $node);

                $navigation .= "<li {$openClass}>{$elem}{$subNavigation}</li>";
            }
        }

        return $navigation . '</ul>';
    }
}
