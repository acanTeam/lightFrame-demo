<?php
namespace Document\Controller;

use \Light\Mvc\Controller\ControllerAbstract as ControllerAbstract;
use Document\Util\DocsTool as DocsTool;

class DocumentController extends ControllerAbstract
{
    public function __construct()
    {
        $this->currentModule = 'Document';
        parent::__construct();

        $this->localConfig = require $this->modulePath . '/config/local.php';
        $this->docsPath = $this->localConfig['docsPath'];
        $this->docsTool = new DocsTool($this->docsPath);

        $this->globalConfigs = $this->_init();
    }

    public function index()
    {
        $docsInfos = $this->_getDocsInfos();

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
        $pathInfo = trim($_SERVER['REQUEST_URI'], '/');
        $pathInfos = explode('/', $pathInfo);

        $appParam = array_shift($pathInfos);
        if ($appParam != 'document') {
            exit('document app error');
        }
        
        $docsParam = array_shift($pathInfos);
        $docsPath = $this->docsPath . $docsParam;
        if (!is_dir($docsPath)) {
            exit("docs '{$docsParam}' no exist!");
        }

        $configs = $this->globalConfigs;
        $configFile = $docsPath . '/config.json';
        if (file_exists($configFile)) {
            $configs = json_decode(file_get_contents($configFile), true);
            $configs = array_merge($this->globalConfigs, $configs);
        }

        $structureFile = $docsPath . '/structure.json';
        if (!file_exists($structureFile)) {
            exit('structure file no exist');
        }

        $structureInfos = json_decode(file_get_contents($structureFile), true);
        $currentElem = $structureInfos;
        $path = array_shift($pathInfos);
        $pathFull = $docsPath . '/';
        while ($path) {
            if (isset($currentElem[$path])) {
                $currentElem = $currentElem[$path];
            } elseif (isset($currentElem['files']) && isset($currentElem['files'][$path])) {
                $currentElem = $currentElem['files'][$path];
            } else {
                exit($pathFull . ' error');
            }

            $path = array_shift($pathInfos);

            $pathFull .= $path . '/';
        }

        $markdownFile = '';
        if (is_dir($pathFull)) {
        } else {
        foreach ($this->globalConfigs[''] as $ext) {
            $markdownFile = $pathFull . $ext;
            if (file_exists($markdownFile)) {
                break ;
            }
        }
        }

        if (empty($markdownFile) && !is_dir($pathFull)) {
            exit('nofile');
        }


            

        print_r($currentElem);
        
    }

    private function _init()
    {
        $globalConfigFile = $this->docsPath . DIRECTORY_SEPARATOR . 'global.json';
        if (!file_exists($globalConfigFile)) {
            return $this->_getMessage('GLOBAL_CONFIG_MISSING', $globalConfigFile);
        }
        $globalConfigs = json_decode(file_get_contents($globalConfigFile), true);

        $defaultConfigFile = $this->docsPath . DIRECTORY_SEPARATOR . 'default.json';
        if (!file_exists($defaultConfigFile)) {
            return $this->_getMessage('DEFAULT_CONFIG_MISSING', $globalConfigFile);
        }
        $defaultConfigs = json_decode(file_get_contents($defaultConfigFile), true);

        $globalConfigs = array_merge($globalConfigs, $defaultConfigs);
        return $globalConfigs;
    }

    private function _getDocsInfos()
    {
        $docsConfigFile = $this->docsPath . DIRECTORY_SEPARATOR . 'docs.json';
        if (!file_exists($docsConfigFile)) {
            return $this->_getMessage('docs_CONFIG_MISSING', $docsConfigFile);
        }
        $docsInfos = json_decode(file_get_contents($docsConfigFile), true);

        return $docsInfos;
    }
}
