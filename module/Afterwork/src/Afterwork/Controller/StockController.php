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
    }

    public function index()
    {
        //$this->_changeNames();
        $this->_createFiles();
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
