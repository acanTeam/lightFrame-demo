<?php
namespace Document\Util\Page;

class Markdown extends Simple
{
    private $filename;
    private  $params;
    private $language;
    private $mtime;
    private $homepage;
    private $breadcrumbTrail;
    private static $template;

    public function __construct()
    {}

    public static function fromFile($file, $params)
    {
        $instance = new self();
        $instance->_initializeFromFile($file, $params);
        return $instance;
    }

    private function _initializeFromFile($file, $params)
    {
        $this->title = $file->title;
        $this->filename = $file->name;
        $this->path = $file->localPath;
        $this->mtime = $file->lastModified;
        $this->params = $params;

        if ($this->title === 'index') {
            $this->homepage = ($this->filename === '_index');
            $minimum_parent_dir_size = ($params['multilanguage']) ? 2 : 1;
            if (count($file->parents) >= $minimum_parent_dir_size) {
                $parent = end($file->parents);
                $this->title = $parent->title;
            } else $this->title = $params['title'];
        } else {
            $this->homepage = false;
        }
        if ($params['breadcrumbs'])
            $this->breadcrumbTrail = $this->_buildBreadcrumbTrail($file->parents, $params['multilanguage']);
        $this->language = '';
        if ($params['multilanguage'] && !empty($file->parents)) {
            reset($file->parents);
            $language_dir = current($file->parents);
            $this->language = $language_dir->name;
        }
        if (is_null(static::$template)) {
            include_once($params['theme']['template']);
            static::$template = new \Template();
        }
    }

    private function _buildBreadcrumbTrail($parents, $multilanguage) {
        if ($multilanguage && !empty($parents)) $parents = array_splice($parents, 1);
        $breadcrumbTrail = array();
        if (!empty($parents)) {
            foreach ($parents as $node) {
                $breadcrumbTrail[$node->title] = $node->get_url();
            }
        }
        return $breadcrumbTrail;
    }

    public function getPageContent()
    {
        if (is_null($this->html)) {
            $this->content = file_get_contents($this->path);
            $this->html = $this->_generatePage();
        }

        return $this->html;
    }

    private function _generatePage() {
        $params = $this->params;
        echo 'bbbbbbbbb';
        $Parsedown = new \Document\Util\Parsedown();
        echo 'sssssssss';
        if ($params['request'] === $params['index_key']) {
            if ($params['multilanguage']) {
                foreach ($params['languages'] as $key => $name) {
                    $entry_page[utf8_encode($name)] = utf8_encode($params['base_page'] . $params['entry_page'][$key]->get_url());
                }
            } else $entry_page['View Documentation'] = utf8_encode($params['base_page'] . $params['entry_page']->uri);
        } else if ($params['file_uri'] === 'index')
            $entry_page[utf8_encode($params['entry_page']->title)] = utf8_encode($params['base_page'].
                $params['entry_page']->get_url());
        $page['entry_page'] = (isset($entry_page)) ? $entry_page : null;

        $page['homepage'] = $this->homepage;
        $page['title'] = $this->title;
        $page['tagline'] = $params['tagline'];
        $page['author'] = $params['author'];
        $page['filename'] = $this->filename;
        if ($page['breadcrumbs'] = $params['breadcrumbs']) {
            $page['breadcrumb_trail'] = $this->breadcrumbTrail;
            $page['breadcrumb_separator'] = $params['breadcrumb_separator'];
        }
        $page['language'] = $this->language;
        $page['path'] = $this->path;
        $page['request'] = utf8_encode($params['request']);
        $page['theme'] = $params['theme'];
        $page['modified_time'] = filemtime($this->path);
        $page['markdown'] = $this->content;
        $page['content'] = $Parsedown->text($this->content);
        $page['file_editor'] = $params['file_editor'];
        $page['google_analytics'] = $params['google_analytics'];
        $page['piwik_analytics'] = $params['piwik_analytics'];

        return static::$template->get_content($page, $params);
    }
}

?>
