<?php
namespace Document\Util;

class DirectoryEntry
{
    const FILE_TYPE = 'FILE_TYPE';
    const DIRECTORY_TYPE = 'DIRECTORY_TYPE';

    public $name;
    public $title;
    public $type;
    public $indexPage;
    public $firstPage;
    public $value;
    public $uri;
    public $localPath;
    public $lastModified;
    public $parents;

    public function __construct($path = '', $parents = array())
    {
        if (!isset($path) || $path == '' || !file_exists($path)) {
            return;
        }

        $this->localPath = $path;
        $this->parents = $parents;
        $this->lastModified = filemtime($path);
        $this->name = DocsHelper::pathinfo($path);
        $this->name = $this->name['filename'];
        $this->title = DocsHelper::getTitleFromFile($this->name);
        $this->uri = DocsHelper::getUrlFromFilename($this->name);
        $this->indexPage = false;

        if (is_dir($path)) {
            $this->type = self::DIRECTORY_TYPE;
            $this->value = array();
        } else {
            $this->type = self::FILE_TYPE;
            $this->value = $this->uri;
        }
    }

    public function sort() {
        if ($this->type == static::DIRECTORY_TYPE) uasort($this->value, array($this, 'compare_directory_entries'));
    }

    public function retrieveFile($request, $getFirstFile = false)
    {
        $tree = $this;
        if ($tree->type !== static::DIRECTORY_TYPE) {
            $return  = $tree->type === static::FILE_TYPE ? $tree : false;
            return $return;
        }

        $request = explode('/', $request);
        foreach ($request as $node) {
            if ($tree->type !== static::DIRECTORY_TYPE) {
                return false;
            }

            if (isset($tree->value[$node])) {
                $tree = $tree->value[$node];
            } else {
                $return = false;
                if ($node === 'index' || $node === 'index.html') {
                    $return = $getFirstFile ? ($tree->indexPage ? $tree->indexPage : $tree->firstPage) : $tree->indexPage;
                }
                return $return;
            }
        }

        $return = $tree->type === static::DIRECTORY_TYPE ? ($tree->indexPage ? $tree->indexPage : ($getFirstFile ? $tree->firstPage : false)) : $tree;
        return $return;
    }

    public function getUrl()
    {
        $url = '';
        foreach ($this->parents as $node) {
            $url .= $node->uri . '/';
        }
        $url .=  $this->uri;

        return $url;
    }

    public function getFirstPage() {
        foreach ($this->value as $node) {
            if ($node->type === static::FILE_TYPE && $node->title != 'index')
                return $node;
        }
        foreach ($this->value as $node) {
            if ($node->type === static::DIRECTORY_TYPE) {
                $page = $node->getFirstPage();
                if ($page) return $page;
            }
        }
        return false;
    }

    public function write($content) {
        if (is_writable($this->localPath)) file_put_contents($this->localPath, $content);
        else return false;
        return true;
    }

    private function compare_directory_entries($a, $b) {
        $name_a = explode('_', $a->name);
        $name_b = explode('_', $b->name);
        if (is_numeric($name_a[0])) {
            $a = intval($name_a[0]);
            if (is_numeric($name_b[0])) {
                $b = intval($name_b[0]);
                if (($a >= 0) == ($b >= 0)) {
                    $a = abs($a);
                    $b = abs($b);
                    if ($a == $b) return (strcasecmp($name_a[1], $name_b[1]));
                    return ($a > $b) ? 1 : -1;
                }
                return ($a >= 0) ? -1 : 1;
            }
            $t = $name_b[0];
            if ($t && $t[0] === '-') return -1;
            return ($a < 0) ? 1 : -1;
        } else {
            if (is_numeric($name_b[0])) {
                $b = intval($name_b[0]);
                if ($b >= 0) return 1;
                $t = $name_a[0];
                if ($t && $t[0] === '-') return 1;
                return ($b >= 0) ? 1 : -1;
            }
            $p = $name_a[0];
            $q = $name_b[0];
            if (($p && $p[0] === '-') == ($q && $q[0] === '-')) return strcasecmp($p, $q);
            else return ($p[0] === '-') ? 1 : -1;
        }
    }
}
