<?php
namespace Document\Util;
//require_once(dirname(__FILE__) . '/../vendor/autoload.php');
//require_once('daux_directory.php');
//require_once('daux_helper.php');
//require_once('daux_page.php');

use Document\Util\Page\Error as ErrorPage;

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
        if (!$this->error) $this->params = $this->get_page_params();
    }

    public function generate_static($output_dir = NULL) {
        if (is_null($output_dir)) $output_dir = $this->localBase . DIRECTORY_SEPARATOR . 'static';
        DocsHelper::clean_copy_assets($output_dir, $this->localBase);
        $this->recursive_generate_static($this->tree, $output_dir, $this->params);
    }

    public function handle_request($url, $query = array()) {
        if ($this->error) return $this->errorPage;
        if (!$this->params['clean_urls']) $this->params['base_page'] .= 'index.php/';
        $request = DocsHelper::get_request();
        $request = urldecode($request);
        $request_type = isset($query['method']) ? $query['method'] : '';
        if($request == 'first_page') {
            $request = $this->tree->firstPage->uri;
        }
        switch ($request_type) {
            case 'DauxEdit':
                if ($this->options['file_editor']) {
                    $content = isset($query['markdown']) ? $query['markdown'] : '';
                    return $this->save_file($request, $content);
                }
                return $this->_generateErrorPage('Editing Disabled', 'Editing is currently disabled in config',
                    ErrorPage::FATAL_ERROR_TYPE);
            default:
                return $this->get_page($request);
        }
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
        $this->baseUrl = $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
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
                file_put_contents($output_dir . DIRECTORY_SEPARATOR . $key, $page->get_page_content());
            }
        }
    }

    private function save_file($request, $content) {
        $file = $this->get_file_from_request($request);
        if ($file === false) return $this->_generateErrorPage('Page Not Found',
            'The Page you requested is yet to be made. Try again later.', ErrorPage::MISSING_PAGE_ERROR_TYPE);
        if ($file->write($content)) return new SimplePage('Success', 'Successfully Edited');
        else return $this->_generateErrorPage('File Not Writable', 'The file you wish to write to is not writable.',
            ErrorPage::FATAL_ERROR_TYPE);
    }

    private function _generateErrorPage($title, $content, $type)
    {
        $this->errorPage = new ErrorPage($title, $content, $this->get_page_params($type));
        $this->error = true;
        return $this->errorPage;
    }

    private function get_page($request) {
        $params = $this->params;
        $file = $this->get_file_from_request($request);
        if ($file === false) return $this->_generateErrorPage('Page Not Found',
            'The Page you requested is yet to be made. Try again later.', ErrorPage::MISSING_PAGE_ERROR_TYPE);
        $params['request'] = $request;
        $params['file_uri'] = $file->value;
        if ($request !== 'index') $params['entry_page'] = $file->firstPage;
        return MarkdownPage::fromFile($file, $params);
    }

    private function get_page_params($mode = '') {
        $params = array();
        $params['local_base'] = $this->localBase;

        if ($mode === '') $mode = $this->mode;
        $params['mode'] = $mode;
        switch ($mode) {
            case ErrorPage::FATAL_ERROR_TYPE:
                $params['error_type'] = ErrorPage::FATAL_ERROR_TYPE;
                break;

            case ErrorPage::NORMAL_ERROR_TYPE:
            case ErrorPage::MISSING_PAGE_ERROR_TYPE:
                $params['error_type'] = $mode;
                $params['index_key'] = 'index';
                $params['docs_path'] = $this->docsPath;
                $protocol = '//';
                $params['base_url'] = $protocol . $this->baseUrl;
                $params['base_page'] = $params['base_url'];
                $params['host'] = $this->host;
                $params['tree'] = $this->tree;
                $params['index'] = ($this->tree->index_page !== false) ? $this->tree->index_page : $this->tree->firstPage;
                $params['clean_urls'] = $this->options['clean_urls'];

                $params['tagline'] = $this->options['tagline'];
                $params['title'] = $this->options['title'];
                $params['author'] = $this->options['author'];
                $params['image'] = $this->options['image'];
                if ($params['image'] !== '') $params['image'] = str_replace('<base_url>', $params['base_url'], $params['image']);
                $params['repo'] = $this->options['repo'];
                $params['links'] = $this->options['links'];
                $params['twitter'] = $this->options['twitter'];
                $params['google_analytics'] = ($g = $this->options['google_analytics']) ?
                    DocsHelper::google_analytics($g, $this->host) : '';
                $params['piwik_analytics'] = ($p = $this->options['piwik_analytics']) ?
                    DocsHelper::piwik_analytics($p, $this->options['piwik_analytics_id']) : '';

                $params['template'] = $this->options['template'];
                $params['theme'] = DocsHelper::configure_theme($this->localBase . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR .
                    $this->options['template'] . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . $this->options['theme'] . '.thm', $params['base_url'],
                    $this->localBase, $params['base_url'] . "templates/" . $params['template'] . "/themes/" . $this->options['theme'] . '/');
                break;

            case DocsTool::LIVE_MODE:
                $params['docs_path'] = $this->docsPath;
                $params['index_key'] = 'index';
                $protocol = '//';
                $params['base_url'] = $protocol . $this->baseUrl;
                $params['base_page'] = $params['base_url'];
                $params['host'] = $this->host;
                $params['tree'] = $this->tree;
                $params['index'] = ($this->tree->index_page !== false) ? $this->tree->index_page : $this->tree->firstPage;
                $params['clean_urls'] = $this->options['clean_urls'];

                $params['tagline'] = $this->options['tagline'];
                $params['title'] = $this->options['title'];
                $params['author'] = $this->options['author'];
                $params['image'] = $this->options['image'];
                if ($params['image'] !== '') $params['image'] = str_replace('<base_url>', $params['base_url'], $params['image']);
                $params['repo'] = $this->options['repo'];
                $params['links'] = $this->options['links'];
                $params['twitter'] = $this->options['twitter'];
                $params['google_analytics'] = ($g = $this->options['google_analytics']) ?
                    DocsHelper::google_analytics($g, $this->host) : '';
                $params['piwik_analytics'] = ($p = $this->options['piwik_analytics']) ?
                    DocsHelper::piwik_analytics($p, $this->options['piwik_analytics_id']) : '';

                $params['template'] = $this->options['template'];
                $params['theme'] = DocsHelper::configure_theme($this->localBase . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR .
                    $this->options['template'] . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . $this->options['theme'] . '.thm', $params['base_url'],
                    $this->localBase, $params['base_url'] . "templates/" . $params['template'] . "/themes/" . $this->options['theme'] . '/', $mode);


                if ($params['breadcrumbs'] = $this->options['breadcrumbs'])
                    $params['breadcrumb_separator'] = $this->options['breadcrumb_separator'];
                $params['multilanguage'] = !empty($this->options['languages']);
                $params['languages'] = $this->options['languages'];
                if (empty($this->options['languages'])) {
                    $params['entry_page'] = $this->tree->firstPage;
                } else {
                    foreach ($this->options['languages'] as $key => $name) {
                        $params['entry_page'][$key] = $this->tree->value[$key]->firstPage;
                    }
                }

                $params['toggle_code'] = $this->options['toggle_code'];
                $params['float'] = $this->options['float'];
                $params['date_modified'] = $this->options['date_modified'];
                $params['file_editor'] = $this->options['file_editor'];
                break;

            case DocsTool::STATIC_MODE:
                $params['docs_path'] = $this->docsPath;
                $params['index_key'] = 'index.html';
                $params['base_url'] = '';
                $params['base_page'] = $params['base_url'];
                $params['tree'] = $this->tree;
                $params['index'] = ($this->tree->index_page !== false) ? $this->tree->index_page : $this->tree->firstPage;

                $params['tagline'] = $this->options['tagline'];
                $params['title'] = $this->options['title'];
                $params['author'] = $this->options['author'];
                $params['image'] = $this->options['image'];
                $params['repo'] = $this->options['repo'];
                $params['links'] = $this->options['links'];
                $params['twitter'] = $this->options['twitter'];
                $params['google_analytics'] = ($g = $this->options['google_analytics']) ?
                    DocsHelper::google_analytics($g, $this->host) : '';
                $params['piwik_analytics'] = ($p = $this->options['piwik_analytics']) ?
                    DocsHelper::piwik_analytics($p, $this->options['piwik_analytics_id']) : '';

                $params['template'] = $this->options['template'];
                $params['theme'] = DocsHelper::configure_theme($this->localBase . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR .
                    $this->options['template'] . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . $this->options['theme'] . '.thm', $params['base_url'],
                    $this->localBase, $params['base_url'] . "templates/" . $params['template'] . "/themes/" . $this->options['theme'] . '/', $mode);

                if ($params['breadcrumbs'] = $this->options['breadcrumbs'])
                    $params['breadcrumb_separator'] = $this->options['breadcrumb_separator'];
                $params['multilanguage'] = !empty($this->options['languages']);
                $params['languages'] = $this->options['languages'];
                if (empty($this->options['languages'])) {
                    $params['entry_page'] = $this->tree->firstPage;
                } else {
                    foreach ($this->options['languages'] as $key => $name) {
                        $params['entry_page'][$key] = $this->tree->value[$key]->firstPage;
                    }
                }

                $params['toggle_code'] = $this->options['toggle_code'];
                $params['float'] = $this->options['float'];
                $params['date_modified'] = $this->options['date_modified'];
                $params['file_editor'] = false;
                break;
        }
        return $params;
    }

    private function get_file_from_request($request) {
        $file = $this->tree->retrieve_file($request);
        return $file;
    }

}
