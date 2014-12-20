<?php
namespace Document\Util;

use Document\Util\Page\Error as ErrorPage;
use Document\Util\Page\Markdown as MarkdownPage;

class DocsTool
{
    const STATIC_MODE = 'DOCS_STATIC';
    const LIVE_MODE = 'DOCS_LIVE';

    public static $VALID_MARKDOWN_EXTENSIONS;
    private $localBase;
    private $baseUrl;
    private $host;
    private $currentDocs;
    private $docsPath;
    private $tree;
    private $options;
    private $errorPage;
    private $error = false;
    private $params;
    private $mode;

    public function __construct($basePath, $currentDocs = 'docs')
    {
        $this->localBase = $basePath;
        $this->currentDocs = $currentDocs;
        $this->_initialSetup();
    }

    public function initialize($configFile = 'config.json')
    {
        if ($this->error) {
            return;
        }

        $this->_loadDocsConfig($configFile);
        $this->_generateDirectoryTree();
        if (!$this->error) {
            $this->params = $this->_getParams();
        }
    }

    public function generate_static($output_dir = NULL) {
        if (is_null($output_dir)) $output_dir = $this->localBase . DIRECTORY_SEPARATOR . 'static';
        DocsHelper::clean_copy_assets($output_dir, $this->localBase);
        $this->recursive_generate_static($this->tree, $output_dir, $this->params);
    }

    public function handleRequest($url, $query = array())
    {
        if ($this->error) {
            return $this->errorPage;
        }

        if (!$this->params['clean_urls']) {
            $this->params['base_page'] .= 'index.php/';
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
            return $this->_generateErrorPage('Editing Disabled', 'Editing is currently disabled in config',
                ErrorPage::FATAL_ERROR_TYPE);
        }

        return $this->_getPage($request);
    }

    private function _initialSetup()
    {
        $this->_setupEnvironmentVariables();
        $this->_loadGlobalConfig();
    }

    private function _setupEnvironmentVariables()
    {
        global $argc;
        //$this->localBase = dirname(dirname(__FILE__));
        $this->baseUrl = '';
        if (isset($argc)) {
            $this->mode = self::STATIC_MODE;
            return;
        }
        $this->mode = self::LIVE_MODE;
        $this->host = $_SERVER['HTTP_HOST'];
        $this->baseUrl = $_SERVER['HTTP_HOST'] . str_replace('\\', '/', dirname($_SERVER['PHP_SELF']));
        $t = strrpos($this->baseUrl, '/index.php');
        if ($t != FALSE) {
            $this->baseUrl = substr($this->baseUrl, 0, $t);
        }
        $this->baseUrl .= substr($this->baseUrl, -1) !== '/' ? '/' : '';
    }

    private function _loadGlobalConfig()
    {
        $globalConfigFile = $this->localBase . DIRECTORY_SEPARATOR . 'global.json';
        if (!file_exists($globalConfigFile)) {
            $this->_generateErrorPage('Global Config File Missing',
            'The Global Config file is missing. Requested File : ' . $globalConfigFile, ErrorPage::FATAL_ERROR_TYPE);
            return;
        }

        $globalConfig = json_decode(file_get_contents($globalConfigFile), true);
        if (!isset($globalConfig)) {
            $this->_generateErrorPage('Corrupt Global Config File',
                'The Global Config file is corrupt. Check that the JSON encoding is correct', ErrorPage::FATAL_ERROR_TYPE);
            return;
        }

        $this->docsPath = $this->localBase . DIRECTORY_SEPARATOR . $this->currentDocs;
        if (!is_dir($this->docsPath)) {
            $this->_generateErrorPage('Docs Directory not found',
                'The Docs directory does not exist. Check the path again : ' . $this->docsPath, ErrorPage::FATAL_ERROR_TYPE);
            return;
        }

        static::$VALID_MARKDOWN_EXTENSIONS = isset($globalConfig['valid_markdown_extensions']) ? $globalConfig['valid_markdown_extensions'] : array('md', 'markdown');
    }

    private function _loadDocsConfig($configFile)
    {
        $configFile = $this->docsPath . DIRECTORY_SEPARATOR . $configFile;
        if (!file_exists($configFile)) {
            $this->_generateErrorPage('Config File Missing',
                'The local config file is missing. Check path : ' . $configFile, ErrorPage::FATAL_ERROR_TYPE);
            return;
        }
        $this->options = json_decode(file_get_contents($this->localBase . DIRECTORY_SEPARATOR . 'default.json'), true);
        if (is_file($configFile)) {
            $config = json_decode(file_get_contents($configFile), true);
            if (!isset($config)) {
                $this->_generateErrorPage('Invalid Config File',
                    'There was an error parsing the Config file. Please review', ErrorPage::FATAL_ERROR_TYPE);
                return;
            }
            $this->options = array_merge($this->options, $config);
        }

        if (isset($this->options['timezone'])) {
            date_default_timezone_set($this->options['timezone']);
        } elseif (!ini_get('date.timezone')) {
            date_default_timezone_set('GMT');
        }
    }

    private function _generateDirectoryTree()
    {
        $this->tree = DocsHelper::buildDirectoryTree($this->docsPath, $this->options['ignore'], $this->mode);
        if (!empty($this->options['languages'])) {
            foreach ($this->options['languages'] as $key => $node) {
                $this->tree->value[$key]->title = $node;
            }
        }
    }

    private function recursive_generate_static($tree, $output_dir, $params, $baseUrl = '') {
        $params['baseUrl'] = $params['base_page'] = $baseUrl;
        $new_params = $params;
        //changed this as well in order for the templates to be put in the right place
        $params['theme'] = DocsHelper::rebase_theme($params['theme'], $baseUrl, $params['baseUrl'] . "templates/default/themes/" . $params['theme']['name'] . '/');
        //
        $params['image'] = str_replace('<base_url>', $baseUrl, $params['image']);
        if ($baseUrl !== '') $params['entry_page'] = $tree->firstPage;
        foreach ($tree->value as $key => $node) {
            if ($node->type === Directory_Entry::DIRECTORY_TYPE) {
                $new_output_dir = $output_dir . DIRECTORY_SEPARATOR . $key;
                @mkdir($new_output_dir);
                $this->recursive_generate_static($node, $new_output_dir, $new_params, '../' . $baseUrl);
            } else {
                $params['request'] = $node->get_url();
                $params['file_uri'] = $node->name;

                $page = MarkdownPage::fromFile($node, $params);
                file_put_contents($output_dir . DIRECTORY_SEPARATOR . $key, $page->_getPage_content());
            }
        }
    }

    private function _saveFile($request, $content)
    {
        $file = $this->_getFileFromReqeust($request);
        if ($file === false) return $this->_generateErrorPage('Page Not Found',
            'The Page you requested is yet to be made. Try again later.', ErrorPage::MISSING_PAGE_ERROR_TYPE);
        if ($file->write($content)) return new SimplePage('Success', 'Successfully Edited');
        else return $this->_generateErrorPage('File Not Writable', 'The file you wish to write to is not writable.',
            ErrorPage::FATAL_ERROR_TYPE);
    }

    private function _generateErrorPage($title, $content, $type)
    {
        $this->errorPage = new ErrorPage($title, $content, $this->_getParams($type));
        $this->error = true;
        return $this->errorPage;
    }

    private function _getPage($request)
    {
        $params = $this->params;
        $file = $this->_getFileFromReqeust($request);
        if ($file === false) {
            return $this->_generateErrorPage('Page Not Found',
                'The Page you requested is yet to be made. Try again later.', ErrorPage::MISSING_PAGE_ERROR_TYPE);
        }
        $params['request'] = $request;
        $params['file_uri'] = $file->value;
        if ($request !== 'index') {
            $params['entry_page'] = $file->firstPage;
        }

        return array('file' => $file, 'params' => $params);
        //return MarkdownPage::fromFile($file, $params);
    }

    private function _getParams($mode = '')
    {
        $params = $this->options;
        $params['localBase'] = $this->localBase;
        $mode = $mode === '' ? $this->mode : $mode;
        $params['mode'] = $params['error_type'] = $mode;

        if ($mode == ErrorPage::FATAL_ERROR_TYPE) {
            return $params;
        }

        $baseParams = array(
            'error_type' => $mode,
            'index_key' => 'index',
            'docs_path' => $this->docsPath,
            'base_url' => 'http://' . $this->baseUrl,
            'base_page' => 'http://' . $this->baseUrl,
            'host' => $this->host,
            'tree' => $this->tree,
            'index' => $this->tree->indexPage !== false ? $this->tree->indexPage : $this->tree->firstPage,
        );
        if ($mode == self::STATIC_MODE) {
            $baseParams['index'] = 'index.html';
            $baseParams['file_editor'] = false;
        }
        $params = array_merge($params, $baseParams);

        return $params;
    }

    private function _getFileFromReqeust($request)
    {
        $file = $this->tree->retrieveFile($request);
        return $file;
    }
}
