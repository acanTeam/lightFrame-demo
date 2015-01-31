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
            for ($i = 0; $i < $len; $i++) {
                $title = ($i + 1) . '_' . str_replace(array(':', '?'), '', trim($titles[$i])) . '.md';
                $targetFile = $info['path'] . '/' . $title;
                $targetFile = $this->_stringEncode($targetFile, 'GBK');
                if (!file_exists($targetFile)) {
                    file_put_contents($targetFile, '');
                }
                if (filesize($targetFile) < 10) {
                    echo "<a href='http://www.net767.com{$urls[$i]}' target='_blank'>{$title}</a><br />";
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

    /**
     * Get pointing encode of string
     *
     * @param string $string
     * @param string $wantEncode 
     * @return string
     */
    private function _stringEncode($string, $wantEncode = 'UTF-8')
    {
        $encodes = array('ASCII', 'UTF-8', 'GB2312', 'GBK', 'BIG5');
        $encode = mb_detect_encoding($string, $encodes);
        if ($encode != $wantEncode) {
            $string = iconv($encode, $wantEncode, $string);
        }

        return $string;
    }
}
