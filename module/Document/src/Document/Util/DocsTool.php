<?php
namespace Document\Util;

class DocsTool
{
    const STATIC_MODE = 'DOCS_STATIC';
    const LIVE_MODE = 'DOCS_LIVE';

    const NORMAL_ERROR_TYPE = 'NORMAL_ERROR';
    const MISSING_PAGE_ERROR_TYPE = 'MISSING_PAGE_ERROR';
    const FATAL_ERROR_TYPE = 'FATAL_ERROR';

    public static $validExtensions;

    private $mode;
    private $localBase;
    private $baseUrl;
    private $host;
    private $currentDocs;
    private $docsPath;
    private $tree;
    private $options;

    public function __construct($basePath)
    {
        global $argc;
        if (isset($argc)) {
            $this->mode = self::STATIC_MODE;
            return;
        }

        $this->mode = self::LIVE_MODE;
        $this->host = $_SERVER['HTTP_HOST'];
        
        $baseUrl = '';
        $baseUrl = $_SERVER['HTTP_HOST'] . str_replace('\\', '/', dirname($_SERVER['PHP_SELF']));
        $t = strrpos($baseUrl, '/index.php');
        if ($t != FALSE) {
            $baseUrl = substr($baseUrl, 0, $t);
        }
        $baseUrl .= substr($baseUrl, -1) !== '/' ? '/' : '';
        $this->baseUrl = $baseUrl;
        
        $this->localBase = $basePath;
    }

    public function initialize($currentDocs)
    {
        $this->currentDocs = $currentDocs;

        $globalConfigFile = $this->localBase . DIRECTORY_SEPARATOR . 'global.json';
        if (!file_exists($globalConfigFile)) {
            return $this->_getMessage('GLOBAL_CONFIG_MISSING', $globalConfigFile);
        }
        $globalConfigs = json_decode(file_get_contents($globalConfigFile), true);

        $defaultConfigFile = $this->localBase . DIRECTORY_SEPARATOR . 'default.json';
        if (!file_exists($defaultConfigFile)) {
            return $this->_getMessage('DEFAULT_CONFIG_MISSING', $globalConfigFile);
        }
        $defaultConfigs = json_decode(file_get_contents($defaultConfigFile), true);

        $this->docsPath = $this->localBase . DIRECTORY_SEPARATOR . $this->currentDocs;
        if (!is_dir($this->docsPath)) {
            return $this->_getMessage('DOCS_PATH_MISSING', $this->docsPath);
        }

        $configFile = $this->docsPath . DIRECTORY_SEPARATOR . 'config.json';
        if (!file_exists($configFile)) {
            return $this->_getMessage('CONFIG_FILE_MISSING', $configFile);
        }
        $configs = json_decode(file_get_contents($configFile), true);
        $options = array_merge($globalConfigs, $defaultConfigs, $configs);

        static::$validExtensions = isset($options['valid_markdown_extensions']) ? $options['valid_markdown_extensions'] : array('md', 'markdown');
        $timezone = isset($options['timezone']) ? $options['timezone'] : 'GMT';
        date_default_timezone_set($timezone);

        $this->options = $options;

        $this->tree = $this->_generateDirectoryTree();
    }

    public function generate_static($output_dir = NULL) {
        if (is_null($output_dir)) $output_dir = $this->localBase . DIRECTORY_SEPARATOR . 'static';
        DocsHelper::clean_copy_assets($output_dir, $this->localBase);
        $this->recursive_generate_static($this->tree, $output_dir, $this->options);
    }

    public function handleRequest($url, $query = array())
    {
        $this->_addOptions();
        if (!$this->options['clean_urls']) {
            $this->options['base_page'] .= 'index.php/';
        }

        $request = DocsHelper::getRequest();
        $request = urldecode($request);
        $requestType = isset($query['method']) ? $query['method'] : '';
        if($request == 'first_page') {
            $request = $this->tree->firstPage->uri;
        }

        if ($requestType == 'DocsEdit') {
            if ($this->options['file_editor']) {
                $content = isset($query['markdown']) ? $query['markdown'] : '';
                return $this->_saveFile($request, $content);
            }
            return $this->_getMessage('Editing Disabled', 'Editing is currently disabled in config',
                self::FATAL_ERROR_TYPE);
        }

        return $this->_getPage($request);
    }

    private function _generateDirectoryTree()
    {
        $docsPath = $this->docsPath;
        $ignore = isset($this->options['ignore']) ? $this->options['ignore'] : array();
        $mode = $this->mode;
        $tree = DocsHelper::buildDirectoryTree($docsPath, $ignore, $mode);

        $languages = isset($this->options['languages']) ? $this->options['languages'] : false;
        if (!empty($languages)) {
            foreach ($this->options['languages'] as $key => $node) {
                $tree->value[$key]->title = $node;
            }
        }
        return $tree;
    }

    private function recursive_generate_static($tree, $output_dir, $options, $baseUrl = '') {
        $options['baseUrl'] = $options['base_page'] = $baseUrl;
        $new_options = $options;
        //changed this as well in order for the templates to be put in the right place
        $options['theme'] = DocsHelper::rebase_theme($options['theme'], $baseUrl, $options['baseUrl'] . "templates/default/themes/" . $options['theme']['name'] . '/');
        //
        $options['image'] = str_replace('<base_url>', $baseUrl, $options['image']);
        if ($baseUrl !== '') $options['entry_page'] = $tree->firstPage;
        foreach ($tree->value as $key => $node) {
            if ($node->type === Directory_Entry::DIRECTORY_TYPE) {
                $new_output_dir = $output_dir . DIRECTORY_SEPARATOR . $key;
                @mkdir($new_output_dir);
                $this->recursive_generate_static($node, $new_output_dir, $new_options, '../' . $baseUrl);
            } else {
                $options['request'] = $node->get_url();
                $options['file_uri'] = $node->name;

                $page = MarkdownPage::fromFile($node, $options);
                file_put_contents($output_dir . DIRECTORY_SEPARATOR . $key, $page->_getPage_content());
            }
        }
    }

    private function _saveFile($request, $content)
    {
        $file = $this->_getFileFromReqeust($request);
        if ($file === false) return $this->_getMessage('Page Not Found',
            'The Page you requested is yet to be made. Try again later.', self::MISSING_PAGE_ERROR_TYPE);
        if ($file->write($content)) return new SimplePage('Success', 'Successfully Edited');
        else return $this->_getMessage('File Not Writable', 'The file you wish to write to is not writable.',
            self::FATAL_ERROR_TYPE);
    }

    private function _getPage($request)
    {
        $options = $this->options;
        $file = $this->_getFileFromReqeust($request);
        print_r($file);exit();
        if ($file === false) {
            return $this->_getMessage('Page Not Found',
                'The Page you requested is yet to be made. Try again later.', self::MISSING_PAGE_ERROR_TYPE);
        }
        $options['request'] = $request;
        $options['file_uri'] = $file->value;
        if ($request !== 'index') {
            $options['entry_page'] = $file->firstPage;
        }

        return array('file' => $file, 'options' => $options, 'tree' => $this->tree);
        //return MarkdownPage::fromFile($file, $options);
    }

    private function _addOptions($mode = '')
    {
        $options['localBase'] = $this->localBase;
        $mode = $mode === '' ? $this->mode : $mode;
        $options['mode'] = $options['error_type'] = $mode;

        if ($mode == self::FATAL_ERROR_TYPE) {
            $this->options = array_merge($this->options, $options);
            return ;
        }

        $baseOptions = array(
            'error_type' => $mode,
            'index_key' => 'index',
            'docs_path' => $this->docsPath,
            'base_url' => 'http://' . $this->baseUrl,
            'base_page' => 'http://' . $this->baseUrl,
            'host' => $this->host,
            'index' => $this->tree->indexPage !== false ? $this->tree->indexPage : $this->tree->firstPage,
        );
        if ($mode == self::STATIC_MODE) {
            $baseOptions['index'] = 'index.html';
            $baseOptions['file_editor'] = false;
        }
        $options = array_merge($this->options, $options, $baseOptions);

        $this->options = $options;
    }

    private function _getFileFromReqeust($request)
    {
        $file = $this->tree->retrieveFile($request);
        return $file;
    }

    private function _getMessage($code, $data)//$title, $content, $type)
    {
        $messages = array(
            'GLOBAL_CONFIG_MISSING' => 'The Global Config file is missing. Requested File : ' . $data,
            'DOCS_PATH_MISSING' => 'The Docs directory does not exist. Check the path again : ' . $data,
            'CONFIG_FILE_MISSING' => 'The local config file is missing. Check path : ' . $data,
        );

        return $messages[$code];
        //$this->errorPage = new ErrorPage($title, $content, $this->_getoptions($type));
        //$this->error = true;
        //return $this->errorPage;
    }
}
