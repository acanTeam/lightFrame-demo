<?php
namespace Codelib\Controller;

use \Light\Mvc\Controller\ControllerAbstract as ControllerAbstract;
use \Light\Filesystem\Directory as Directory;
use \Light\Filesystem\FileInfo as FileInfo;

class FilesysController extends ControllerAbstract
{
    public function __construct()
    {
        $this->currentModule = 'Codelib';
        parent::__construct();

        $this->configPath = $this->modulePath . '/config/';
    }

    public function phumlshow()
    {
        $path = $this->application->configCommon['uploadPath'];
        $pngPath = $path . 'acanstudio/phuml/';

        $infos = Directory::getTree($pngPath);
        $files = $this->getFiles($infos);
        $paths = array_keys($files);
        sort($paths);

        $navbarInfos = $this->_getNavbar();
        $data = array(
            'navbarInfos' => $navbarInfos,
            'paths' => $paths,
            'files' => $files,
            'application' => $this->application
        );
        $this->application->layout('phuml', 'common/google_layout', $data);
    }

    public function phuml()
    {
        $commandContent = '';
        $umlConfigs = require $this->configPath . 'local.phuml.php';

        $shellContent = '';
        foreach ($umlConfigs['paths'] as $code => $directory) {
            $commandContent .= $this->_createPng($code, $directory, $umlConfigs);

        }

        foreach ($umlConfigs['subPaths'] as $code => $directory) {
            if (!is_dir($directory)) {
                echo $directory . '<br />';
                continue;
            }

            $subDirectorys = Directory::read($directory);
            foreach ($subDirectorys as $subDirectory) {
                $basename = basename($subDirectory);
                $commandContent .= $this->_createPng($code . '_' . $basename, $subDirectory, $umlConfigs);
            }
        }
        echo $commandContent;

        $commandFile = $this->modulePath . '/cache/commandFile.sh';
        file_put_contents($commandFile, $commandContent);
    }

    private function _createPng($code, $directory, $config)
    {
        if (!is_dir($directory)) {
            echo $directory . '<br />';
            return ;
        }

        $code = str_replace('_', '/', $code);
        $targetFile = str_replace($directory, $config['targetPath'] . '/' . $code, $directory) . '.png';
        $command = "{$config['phumlCommand']} -r {$directory} -graphviz -createAssociations false -Neato {$targetFile}";

        if (file_exists($targetFile) && filesize($targetFile) > 0) {
            return $command . "\n";
        }
        Directory::mkdir(dirname($targetFile));
        //exec($command, $output);

        return $command . "\n";
    }

    public function moveBom()
    {
        $dirName = 'E:/www/wangcan/ciProject/wwwroot/luxury/dbj/';
        $files = Directory::read($dirName, true);
        
        foreach ($files as $fileName) {
            if (!is_file($fileName) || !in_array(FileInfo::extension($fileName), array('php', 'html', 'htm', 'js', 'css'))) {
                continue;
            }

    		$fileData = FileInfo::read($fileName);
    		$fileType = mb_detect_encoding($fileData);//, array('UTF-8', 'GBD', 'LATIN1', 'BIG5'));
            echo $fileName . '==='; 
    		if ($fileType == 'UTF-8') {
                echo '++';
    			$fileData = file_get_contents($fileName);
    			if (preg_match('/^\xEF\xBB\xBF/', $fileData)) {
    
    				while (preg_match('/^\xEF\xBB\xBF/', $fileData)) {
                        echo '---------';
    					$fileData = substr($fileData, 3);
    				}
                    //file_put_contents($fileName, $fileData);
    			}
    		}
            echo '===<br />';
        }
    }

    protected function changeNames()
    {
        $path = 'E:/www/wangcan/lightFrame/library/Structure/Interface/';

        $files = Directory::read($path);
        foreach ($files as $file) {
            $baseName = basename($file);
            //$baseName = substr($baseName, 1, -4);
            $baseName = substr($baseName, 9);
            //$targetName = dirname($file) . $baseName . 'Interface.php';
            $targetName = dirname($file) . '/' . $baseName;
            rename($file, $targetName);
            echo $file . '<br />';
            echo $targetName . '<br />';
        }
    }
}
