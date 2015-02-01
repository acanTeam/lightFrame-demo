<?php
namespace Afterwork\Controller;

use \Light\Mvc\Controller\ControllerAbstract as ControllerAbstract;
use \Light\Filesystem\Directory as Directory;
use \Light\Filesystem\FileInfo as FileInfo;

class VideoController extends ControllerAbstract
{
    public function __construct()
    {
        $this->currentModule = 'Afterwork';
        parent::__construct();

        $this->configPath = $this->modulePath . '/config/';
    }

    public function index()
    {
        $this->_updateVideoData();
    }

    private function _updateVideoData()
    {
        $configPath = $this->modulePath . '/config/';
        $dataPath = $this->modulePath . '/data/';

        $pathFile = $configPath . 'video.php';
        $paths = require $pathFile;
        foreach ($paths as $code => $path) {
            $dataFile = $dataPath . $code . '.php';
            if (file_exists($dataFile) && empty($isForce)) {
                continue ;
            }
            $infos = Directory::read($path, true);
            foreach ($infos as $key => $info) {
                $infos[$key] = $this->_stringEncode($info, 'UTF-8');
            }
            $data = "<?php\nreturn " . var_export($infos, true) . ";\n?>";
            $strlen = file_put_contents($dataFile, $data);
        }

    }
}
