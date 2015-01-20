<?php
namespace Document\Util;

class DocumentTool
{
    private $validExtensions = array('.md', '.markdown');

    private $docsPath;

    public function __construct($docsPath)
    {
        if (!is_dir($docsPath)) {
            throw new \Exception("Need a valid path, '{$basePath}' isn't a path");
        }

        $globalConfigFile = $docsPath . DIRECTORY_SEPARATOR . 'global.json';
        if (!file_exists($globalConfigFile)) {
            throw new \Exception("Need a global config file, '{$globalConfigFile}' isn't a file");
        }
        $globalConfigs = json_decode(file_get_contents($globalConfigFile), true);

        if (isset($globalConfigs['valid_extensions'])) {
            $this->validExtensions = $globalConfigs['valid_extensions'];
        }

        $this->docsPath = $docsPath;
        $this->globalConfigs = $globalConfigs;
    }

    public function getDocsInfos()
    {
        $docsFile = $this->docsPath . DIRECTORY_SEPARATOR . 'docs.json';
        if (!file_exists($docsFile)) {
            throw new \Exception("Need a docs infos file, '{$docsFile}' isn't a file");
        }
        $docsInfos = json_decode(file_get_contents($docsFile), true);

        return $docsInfos;
    }    

    public function getDocsInfo($pathInfos)
    {
        $docs = array_shift($pathInfos);
        $docsPath = $this->docsPath . $docs;
        if (!is_dir($docsPath)) {
            throw new \Exception("Need a docs path, '{$docsPath}' isn't a path");
        }

        $configs = $this->_getDocsConfigs($docsPath);
        $structureInfos = $this->_getStructureInfos($docsPath);
        if (!empty($pathInfos)) {
            $this->_formatStructureInfos($structureInfos, $pathInfos);
        }

        $markdownPath = $docsPath . implode('/', $pathInfos); 
        $markdownFile = $this->_getMarkdownFile($structureInfos, $pathInfos, $docsPath);

        $info = array(
            'docs' => $docs,
            'configs' => $configs,
            'structureInfos' => $structureInfos,
            'markdownFile' => $markdownFile
        );

        return $info;
    }

    private function _formatStructureInfos(& $structureInfos, $pathInfos)
    {
        $validPath = false;
        $path = array_shift($pathInfos);
        $path = strpos($path, '#') !== false ? substr(0, strpos($path, '#'), $path) : $path;
        foreach ($structureInfos as $key => $structureInfo) {
            if ($key != $path) {
                continue ;
            }

            $structureInfos[$key]['isCurrent'] = true;
            $validPath = true;

            if (empty($pathInfos)) {
                continue ;
            }
            
            if (!isset($structureInfo['subElems'])) {
                $path = array_shift($pathInfos);
                throw new \Exception("'{$path}' no resource");
            }

            $structureInfos[$key]['subElems'] = $this->_formatStructureInfos($structureInfo['subElems'], $pathInfos);
        }

        if (empty($validPath)) {
            throw new \Exception("'{$path}' no resource");
        }
        return $structureInfos;
    }

    private function _getMarkdownFile(& $structureInfos, $pathInfos, $docsPath)
    {
        $markdownFile = '';
        $path = $docsPath . '/' . implode('/', (array) $pathInfos);
        $path = str_replace('//', '/', $path);
        foreach ($this->validExtensions as $extension) {
            $markdownFile = $path . $extension;
            if (file_exists($markdownFile)) {
                return $markdownFile;
            }
        }

        if (!is_dir($path)) {
            throw new \Exception("'{$path}' not a docs path!");
        }

        $currentElem = $structureInfos;
        $path = array_shift($pathInfos);
        while ($path) {
            $currentElem = $currentElem[$path];
            $path = array_shift($pathInfos);
        }
    }

    private function _getDocsConfigs($docsPath)
    {
        $configs = $this->globalConfigs;

        $configFile = $docsPath . '/config.json';
        if (file_exists($configFile)) {
            $configs = json_decode(file_get_contents($configFile), true);
            $configs = array_merge($this->globalConfigs, $configs);
        }

        return $configs;
    }

    private function _getStructureInfos($docsPath)
    {
        $structureFile = $docsPath . '/structure.json';
        if (!file_exists($structureFile)) {
            exit('structure file no exist');
        }

        $structureInfos = json_decode(file_get_contents($structureFile), true);
        return $structureInfos;
    }

    
}
