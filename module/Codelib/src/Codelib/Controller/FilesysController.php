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

    public function phuml()
    {
        $umlConfigs = require $this->configPath . 'local.phuml.php';
        print_r($umlConfigs);

        $dirName = 'E:\www\github\zf2-source\library\Zend';
        foreach ($umlConfigs['classPaths'] as $directory) {
            $files = Directory::read($directory);
            print_r($files);
        
            $shellContent = '';
            $htmlContent = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head><p>';
            $i = 0;
            foreach ($files as $fileName) {
            	$suffixStr = ($i % 5) == 4 ? '</p><br />' : '-----';
            	$fileBase = basename($fileName);
            	$shellContent .= "/opt/soft/php/bin/php /var/htmlwww/phuml/src/app/phuml -r /var/htmlwww/zf2/library/Zend/{$fileBase} -graphviz -createAssociations false -Neato /var/htmlwww/phuml/html/{$fileBase}.png\n";
            	$htmlContent .= "<a href='http://42.96.170.56/phuml/html/{$fileBase}.png' target='_blank'>{$fileBase}</a>{$suffixStr}";
            	$i++;
            }
            $htmlContent = rtrim($htmlContent, '-') . '</p>';
            echo $shellContent;
            echo $htmlContent;
            file_put_contents('shell.txt', $shellContent);
            file_put_contents('html.txt', $htmlContent);
        }
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
