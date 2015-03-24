<?php
namespace Afterwork\Controller;

use \Light\Mvc\Controller\ControllerAbstract as ControllerAbstract;
use \Light\Filesystem\Directory as Directory;
use \Light\Filesystem\FileInfo as FileInfo;

class StockController extends ControllerAbstract
{
    public function __construct()
    {
        $this->currentModule = 'Afterwork';
        parent::__construct();

        $this->configPath = $this->modulePath . '/config/';
        $this->configInfos = require($this->configPath . 'local.php');
    }

    public function index()
    {
        //$this->_changeNames();
        $this->_createFiles();
    }

    public function map()
    {
        $infos = $this->_getConfigInfos();
        foreach ($infos as $key => $info) {
            $info['title'] = preg_replace('/^.+[\\\\\\/]/', '', $info['path']);
            $infos[$key] = $info;
        }

        $navbarInfos = $this->_getNavbar();
        $navbarContent = $this->_getNavbarContent($navbarInfos);
        $data = array(
            'navbarContent' => $navbarContent,
            'infos' => $infos,
            'application' => $this->application,
        );

        $this->application->layout('stock', 'common/layout', $data);
    }

    public function listinfo()
    {
        $params = func_get_args();
        $path = isset($params[0]) ? $params[0] : '';
echo $path;
        $currentInfo = $this->_getConfigInfos($path);
        if (empty($currentInfo)) {
            $this->application->pass();
        }

        $this->path = $this->_stringEncode($currentInfo['path'], 'GB2312');
        $fileInfos = Directory::read($this->path);
        foreach ($fileInfos as $key => $info) {
            if (is_dir($info)) {
                unset($fileInfos[$key]);
            } else {
                $subPath = str_replace('\\', '/', $info);
                $fileInfos[$key] = str_replace('//', '/', $subPath);
            }
        }

        $filePath = false;
        $title = isset($params[1]) ? $this->_stringEncode($params[1], 'GB2312') : false;
        if (!empty($title)) {
            $filePath = str_replace('\\', '/', $this->path . '/' . $title);
            $filePath = str_replace('//', '/', $filePath);
            $filePath = in_array($filePath, $fileInfos) ? $filePath : false;
        }
        $filePath = empty($filePath) ? $fileInfos[0] : $filePath;
        $this->baseUrl = $this->application->domain . 'stock/listinfo/' . $path . '/';
  
        $data = array(
            'breadCrumb' => '',
            'navigation' => $this->_getNavigation($fileInfos, $filePath),
            'fileInfo' => $this->_getContent($filePath),
            'application' => $this->application,
        );

        $this->application->layout('content', 'layout', $data);
    }

    private function _getContent($file)
    {
        $contentSource = file_get_contents($file);
        $uploadUrl = str_replace($this->path, 'stock/', dirname($file)) . '/';
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

    private function _getNavigation($infos, $currentFile)
    {
        $navigation = '<ul class="nav nav-list">';

        foreach ($infos as $key => $info) {
            $key = preg_replace('/^.+[\\\\\\/]/', '', $info);
            $key = $this->_stringEncode($key, 'UTF-8');
            $info = $this->_stringEncode($info, 'UTF-8');
            $link = $this->baseUrl . $key;

            $activeClass = isset($info) && $currentFile ? ' class="active"' : '';
            $navigation .= "<li {$activeClass}><a href='{$link}'>{$key}</a></li>";
        }

        return $navigation . '</ul>';
    }    

    protected function _getConfigInfos($path = false)
    {
        $myInfos = require($this->modulePath . '/config/mystock.php');
        $netInfos = require($this->modulePath . '/config/net767.php');
        $infos = array_merge($myInfos, $netInfos);
        foreach ($infos as $key => $info) {
            $info['path'] = $this->configInfos['basePath'] . $info['path'];
            $infos[$key] = $info;
        }

        if (empty($path)) {
            $result = $infos;
        } else {
            $result = isset($infos[$path]) ? $infos[$path] : false;
        }

        return $result;
    }

    public function _createFiles()
    {
        $configPath = $this->modulePath . '/config/';
        $dataPath = $this->modulePath . '/data/';

        $infos = require($configPath . 'net767.php');
        foreach ($infos as $key => $info) {
            $fileFull = $this->_stringEncode($dataPath . $key . '.txt', 'GBK');
            if (!file_exists($fileFull)) {
                continue;
            }

            $content = file_get_contents($fileFull);
            $pattern = '@<a .*href="(?P<url>.*)" .*>(?P<title>.*)</a>@Us';
            preg_match_all($pattern, $content, $result);
            $urls = $result['url'];
            $titles = $result['title'];
            $len = count($urls);
            $j = 1;
            for ($i = 0; $i < $len; $i++) {
                $title = ($i + 1) . '_' . str_replace(array(':', '?'), '', trim($titles[$i])) . '.md';
                $targetFile = $info['path'] . '/' . $title;
                $targetFile = $this->_stringEncode($targetFile, 'GBK');
                if (!file_exists($targetFile)) {
                    file_put_contents($targetFile, '');
                }
                if (filesize($targetFile) < 10) {
                    $j++;
                    echo "{$j}-<a href='http://www.net767.com{$urls[$i]}' target='_blank'>{$title}</a><br />";
                }
            }
        }
        
    }

    protected function _changeNames()
    {
        $path = 'E:\www\common\makeLiving\股票入门';
        $path = $this->_stringEncode($path, 'GBK');

        $files = Directory::read($path);
        foreach ($files as $file) {
            $targetName = str_replace('.txt', '.md', $file);
            rename($file, $targetName);
            echo $file . '<br />' . $targetName . '<br />';
        }
    }    
}
