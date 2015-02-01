<?php
namespace Afterwork\Controller;

use \Light\Mvc\Controller\ControllerAbstract as ControllerAbstract;

class DownController extends ControllerAbstract
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        echo 'index';
    }

    public function getStyleFile()
    {
        $params = func_get_args();
        $demo = isset($params[0]) ? $params[0] : '';
        $file = $_GET['file'];
        $isDown = isset($_GET['isdown']);
        $downInfos = require './downinfo/' . $file . '.php';
        $content = '';
        foreach ($downInfos['files'] as $cssFile) {
        	$content .= file_get_contents($cssFile);
        }
	    //$content = htmlspecialchars_decode(file_get_contents($file));
        
        $patterns = array(
            '@src=.*"(?P<url>.*)".*@Us',
            "@src=.*'(?P<url>.*)'.*@Us",
            '@<link.*type="text/css".*href="(?P<css>.*)".*>@Us',
            "@url\(.*'(?P<images>.*)'.*\)@Us",
            '@url\(.*"(?P<images>.*)".*\)@Us',
            '@url\((?P<images>.*)\)@Us',
            "@<link.*href='(?P<css>.*\.css)'.*>@Us",
        );
        $data = array();
        foreach ($patterns as $pattern) {
            preg_match_all($pattern, $content, $url);
            $data = array_merge($data, $url['url'];
        }
        
        $data = array_unique($data);
        $allnum = count($data);
        
        $i = 1;
        foreach ($data as $file) {
            $file = trim(str_replace(array('"', "'", '..'), '', $file));
        	$url = strpos($file, 'http:') !== false ? $file : $downInfos['urlpre'] . $file;
        	$basefile = basename($file);
        	$basefile = strpos($basefile, '?') !== false ? substr($basefile, 0, strpos($basefile, '?')) : $basefile;
        	$localFile = $downInfos['localpre'] . $basefile;
        
        	if ($isDown) {
        		$this->downFile($url, $localFile);
        		echo $i . '---down file from:' . $url . '===to===' . $localFile . '<br />';
        	} else {
        		$string = (file_exists($localFile)) ? 'yyyyyyyyyyyyyyy ' : 'nnnnnnnnnnnnnn ';
        		echo $i . '---' . $string . '<a href="' . $url . '" target="_blank">' . $url . '</a>---<a href="' . $downInfos['localurl'] . basename($file) . '" target="_blank">本地</a><br />';
        	}
        	$i++;
        }
        var_dump($data);
    }

    protected function downFile($url, $localFile,  $isForce = false)
    {
    	if (file_exists($localFile) && empty($isForce)) {
    		return true;
    	}
    
    	if (empty($url)) {
    		return false;
    	}
    
    	$fileInfos = pathinfo($url);
    	$remoteContent = file_get_contents($url);
    	file_put_contents($localFile, $remoteContent);
    	return true;
    }        

    /**
     * Create the path
     *
     * @param string $path
     * @return boolean
     */
    protected function _makePath($path)
    {
        static $length = 0;
        if (is_dir($path)) {
            return true;
        }

        $length++;
        if ($length > 10) {
            return false;
        }

        $parentPath = dirname($path);
        if (!is_dir($parentPath)) {
            $this->_makePath($parentPath);
        }
        mkdir($path);
        return true;
    }
    
    /**
     * Cache an php array to a file
     *
     * @param string $cacheFile cache file
     * @param array $array 
     * @return length of the cache file
     */
    protected function cacheWrite($cacheFile, $array)
    {
        $array = (array) $array;
    	
    	$array = "<?php\nreturn " . var_export($array, true) . ";\n?>";
    	$strlen = file_put_contents($cacheFile, $array);
    	return $strlen;
    }
}
