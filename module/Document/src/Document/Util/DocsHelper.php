<?php
namespace Document\Util;

class DocsHelper
{

    public static function get_breadcrumb_title_from_request($request, $separator = 'Chevrons', $multilanguage = false) {
        if ($multilanguage) $request = substr($request, strpos($request, '/') + 1);
        $request = str_replace('_', ' ', $request);
        switch ($separator) {
            case 'Chevrons':
                $request = str_replace('/', ' <i class="glyphicon glyphicon-chevron-right"></i> ', $request);
                return $request;
            case 'Colons':
                $request = str_replace('/', ': ', $request);
                return $request;
            case 'Spaces':
                $request = str_replace('/', ' ', $request);
                return $request;
            default:
                $request = str_replace('/', $separator, $request);
                return $request;
        }
        return $request;
    }

    public static function getTitleFromFile($file) {
        $file = static::pathinfo($file);
        return static::getTitleFromFilename($file['filename']);
    }

    public static function getTitleFromFilename($filename) {
        $filename = explode('_', $filename);
        if ($filename[0] == '' || is_numeric($filename[0])) {
            unset($filename[0]);
        } else {
            $t = $filename[0];
            $filename[0] = $t[0] != '-' ? $filename[0] : substr($t, 1);
        }

        $filename = implode(' ', $filename);
        return $filename;
    }

    public static function getUrlFromFile($file) {
        $file = static::pathinfo($file);
        return static::getUrlFromFilename($file['filename']);
    }

    public static function getUrlFromFilename($filename) {
        $filename = explode('_', $filename);
        if ($filename[0] == '' || is_numeric($filename[0])) {
            unset($filename[0]);
        } else {
            $t = $filename[0];
            $filename[0] = $t[0] != '-' ? $filename[0] : substr($t, 1);
        }

        $filename = implode('_', $filename);
        return $filename;
    }

    public static function buildDirectoryTree($dir, $ignore, $mode)
    {
        return static::_directoryTreeBuilder($dir, $ignore, $mode);
    }

    //Depreciated
    public static function get_request_from_url($url, $base_url) {
        $url = substr($url, strlen($base_url));
        if (strpos($url, 'index.php') === 0) {
            $request = (($i = strpos($url, 'request=')) !== false) ? $request = substr($url, $i + 8) : '';
            if ($end = strpos($request, '&')) $request = substr($request, 0, $end);
            $request = ($request === '') ? 'index' : $request;
        } else {
            $request = ($url == '') ? 'index' : $url;
            $request = ($end = strpos($request, '?')) ? substr($request, 0, $end) : $request;
        }
        return $request;
    }

    public static function getRequest()
    {
        if (isset($_SERVER['PATH_INFO'])) {
            $uri = $_SERVER['PATH_INFO'];
        } else if (isset($_SERVER['REQUEST_URI'])) {
            $uri = $_SERVER['REQUEST_URI'];
            if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0) {
                $uri = substr($uri, strlen($_SERVER['SCRIPT_NAME']));
            } else if (strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0) {
                $uri = substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
            }

            if (strncmp($uri, '?/', 2) === 0) {
                $uri = substr($uri, 2);
            }

            $parts = preg_split('#\?#i', $uri, 2);
            $uri = $parts[0];
            if (isset($parts[1])) {
                $_SERVER['QUERY_STRING'] = $parts[1];
                parse_str($_SERVER['QUERY_STRING'], $_GET);
            } else {
                $_SERVER['QUERY_STRING'] = '';
                $_GET = array();
            }
            $uri = parse_url($uri, PHP_URL_PATH);
        } else {
            return false;
        }
        $uri = str_replace('/document/demo', '', $uri);

        $uri = str_replace(array('//', '../'), '/', trim($uri, '/'));
        $uri = empty($uri) ? 'first_page' : $uri;

        return $uri;
    }

    private static function _directoryTreeBuilder($dir, $ignore, $mode = null, $parents = null)
    {
        $dirHandler = opendir($dir);
        if (!$dirHandler) {
            return ;
        }

        $mode = $mode === null ? DocsTool::LIVE_MODE : $mode;
        $directoryEntry = new DirectoryEntry($dir, $parents);
        $newParents = $parents;
        if (is_null($newParents)) {
            $newParents = array();
        } else {
            $newParents[] = $directoryEntry;
        }

        while (($entry = readdir($dirHandler)) !== false) {
            if ($entry == '.' || $entry == '..') {
                continue;
            }

            $path = $dir . DIRECTORY_SEPARATOR . $entry;
            if (is_dir($path) && in_array($entry, $ignore['folders'])) {
                continue;
            }
            if (!is_dir($path) && in_array($entry, $ignore['files'])) {
                continue;
            }

            $fileDetails = static::pathinfo($path);
            if (is_dir($path)) {
                $entry = static::_directoryTreeBuilder($path, $ignore, $mode, $newParents);
            } else if (isset($fileDetails['extension']) && in_array($fileDetails['extension'], DocsTool::$validExtensions)) {
                $entry = new DirectoryEntry($path, $newParents);
                if ($mode === DocsTool::STATIC_MODE) {
                    $entry->uri .= '.html';
                }
            }

            if ($entry instanceof DirectoryEntry) {
                $directoryEntry->value[$entry->uri] = $entry;
            }
        }
        $directoryEntry->sort();
        $directoryEntry->firstPage = $directoryEntry->getFirstPage();
        $index_key = ($mode === DocsTool::LIVE_MODE) ? 'index' : 'index.html';
        if (isset($directoryEntry->value[$index_key])) {
            $directoryEntry->value[$index_key]->firstPage = $directoryEntry->firstPage;
            $directoryEntry->indexPage =  $directoryEntry->value[$index_key];
        } else $directoryEntry->indexPage = false;
        return $directoryEntry;
    }

    public static function pathinfo($path) {
        preg_match('%^(.*?)[\\\\/]*(([^/\\\\]*?)(\.([^\.\\\\/]+?)|))[\\\\/\.]*$%im', $path, $m);
        if (isset($m[1])) $ret['dir']=$m[1];
        if (isset($m[2])) $ret['basename']=$m[2];
        if (isset($m[5])) $ret['extension']=$m[5];
        if (isset($m[3])) $ret['filename']=$m[3];
        return $ret;
    }

    public static function clean_copy_assets($path, $local_base){
        @mkdir($path);
        static::clean_directory($path);

        @mkdir($path . DIRECTORY_SEPARATOR . 'img');
        static::copy_recursive($local_base . DIRECTORY_SEPARATOR . 'img', $path . DIRECTORY_SEPARATOR . 'img');
        @mkdir($path . DIRECTORY_SEPARATOR . 'js');
        static::copy_recursive($local_base . DIRECTORY_SEPARATOR . 'js', $path . DIRECTORY_SEPARATOR . 'js');
        //added and changed these in order to fetch the theme files and put them in the right place
        @mkdir($path . DIRECTORY_SEPARATOR . 'templates');
        @mkdir($path . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'default');
        @mkdir($path . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'default' . DIRECTORY_SEPARATOR . 'themes');
        static::copy_recursive($local_base . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR .
            'default' . DIRECTORY_SEPARATOR . 'themes', $path . DIRECTORY_SEPARATOR . 'templates' .
            DIRECTORY_SEPARATOR . 'default' . DIRECTORY_SEPARATOR . 'themes');
        //
    }

    //  Rmdir
    private static function clean_directory($dir) {
        $it = new \RecursiveDirectoryIterator($dir);
        $files = new \RecursiveIteratorIterator($it,
            \RecursiveIteratorIterator::CHILD_FIRST);
        foreach($files as $file) {
            if ($file->getFilename() === '.' || $file->getFilename() === '..') continue;
            if ($file->isDir()) rmdir($file->getRealPath());
            else unlink($file->getRealPath());
        }
    }

    private static function copy_recursive($src,$dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    static::copy_recursive($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

}
