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
        $pngPath = $path . 'phuml/';

        $infos = Directory::getTree($pngPath);

        $files = $this->getFiles($infos);

        $this->application->layout('phuml', 'common/layout', array('infos' => $infos, 'files' => $files, 'currentPath' => '', 'application' => $this->application));
    }

    private function getFiles($infos, $parentCode = '')
    {
        static $files = array();
        
        foreach ($infos as $code => $info) {
            if ($code == '_files') {
                $files[$parentCode . '_' . $code] = $info;
            } else {
                $this->getFiles($info, $code);
            }
        }
        return $files;
    }

    public function phuml()
    {
        $umlConfigs = require $this->configPath . 'local.phuml.php';

        $shellContent = '';
        foreach ($umlConfigs['paths'] as $code => $directory) {
            $this->_createPng($code, $directory, $umlConfigs);

        }

        foreach ($umlConfigs['subPaths'] as $code => $directory) {
            if (!is_dir($directory)) {
                echo $directory . '<br />';
                continue;
            }

            $subDirectorys = Directory::read($directory);
            foreach ($subDirectorys as $subDirectory) {
                $basename = basename($subDirectory);
                $this->_createPng($code . '_' . $basename, $subDirectory, $umlConfigs);
            }
        }
    }

    private function _createPng($code, $directory, $config)
    {
        if (!is_dir($directory)) {
            echo $directory . '<br />';
            return ;
        }

        $code = str_replace('_', '/', $code);
        $targetFile = str_replace($directory, $config['targetPath'] . '/' . $code, $directory) . '.png';
        if (file_exists($targetFile) && filesize($targetFile) > 0) {
            return ;
        }
        Directory::mkdir(dirname($targetFile));
        $command = "{$config['phumlCommand']} -r {$directory} -graphviz -createAssociations false -Neato {$targetFile}";
        echo $command . '<br />';
        exec($command, $output);
    }

    public function removeBom()
    {
        $files = Dirs::read($dirName, true);
        
        foreach ($files as $fileName) {
        	if (is_file($fileName) && in_array(Files::extension($fileName), array('php', 'html', 'htm'))) {
        		$fileData = Files::read($fileName);
        		$fileType = mb_detect_encoding($fileData);//, array('UTF-8', 'GBD', 'LATIN1', 'BIG5'));
        		if ($fileType == 'UTF-8') {
        			$fileData = file_get_contents($fileName);
        			if (preg_match('/^\xEF\xBB\xBF/', $fileData)) {
        
        				while (preg_match('/^\xEF\xBB\xBF/', $fileData)) {
        					$fileData = substr($fileData, 3);
        				}
        				//echo $fileName . '<br />';
        				//Files::delete($fileName);
        				//copy('E:\kidsDepart\target.php', $fileName);
        				if (Files::save($fileName, $fileData)) {
        					echo $fileName . '--' . $fileType . ' convert successed';
        					echo '<br />';
        				}
        			}
        		}
        
        
        		continue;
        		if ($fileType != 'UTF-8' && empty(strpos($fileName, '/config/'))) {
        			echo $fileName . '--' . $fileType . '<br />';
        		}
        		continue;
        		if ($fileType == 'ASCII') {
        			$fileData = mb_convert_encoding($fileData, 'utf-8', $fileType);
        			Files::delete($fileName);
        			copy('E:\kidsDepart\target.php', $fileName);
        			if (Files::save($fileName, $fileData)) {
        				echo $fileName . '--' . $fileType . ' convert successed';
        				echo '<br />';
        			}
        			//break;
        		}
        	}
        }
    }
}
