<?php
namespace Codelib\Controller;

use \Light\Mvc\Controller\ControllerAbstract as ControllerAbstract;
use \Light\Filesystem\Directory as Directory;

class DomainController extends ControllerAbstract
{
    public function __construct()
    {
        $this->currentModule = 'Codelib';
        parent::__construct();

        //http://www.acanstudio.biz/codelib/domain/local
        //http://www.acanstudio.com/codelib/domain/remote
        //php /var/htmlwww/acanstudio/commandConfigShell/php/smallcode/createDomain.php
    }

    public function index()
    {
        $params = func_get_args();
        $type = isset($params[0]) ? $params[0] : '';
        if (in_array($type, array('local', 'remote'))) {
            $method = $type . 'Method';
            $this->$method();
        }

        $data = array(
            'domains' => $this->getDomains(),
            'application' => $this->application,
        );

        $this->application->layout('domain', 'common/layout', $data);
    }

    protected function localMethod()
    {
        $baseInfo = array(
            'topDomain' => '.biz',
            'baseDomain' => array('iammumu', 'julymom'),
            'ip' => 'localhost',
            'wwwPath' => 'e:/www/',
            'httpdPath' => 'e:/xampp/apache/',
            'tmpPath' => 'e:/tmp/httpd/',
            'logPath' => 'e:/xampp/apache/logs/',
        );
        $textInfos = getTextInfos(true);

        $this->createDomain($baseInfo, $textInfos);
    }
       
    protected function remoteMethod()
    {
        $baseInfo = array(
            'topDomain' => '.com',
            'baseDomain' => array('iammumu', 'julymom'),
            'ip' => '42.96.170.56',
            'wwwPath' => '/var/htmlwww/',
            'httpdPath' => '/opt/soft/httpd/',
            'tmpPath' => '/tmp/httpd/',
            'logPath' => '/var/slog/httpd/logs/',
        );
        $textInfos = getTextInfos();
        
        $this->createDomain($baseInfo, $textInfos);
    }
    
    protected function createDomain($baseInfo, $textInfos)
    {
        $baseReplace = array(
            '#PLACE_IP#' => $baseInfo['ip'],
            '#PLACE_WWW_PATH#' => $baseInfo['wwwPath'],
            "\r" => '',
        );
        $baseStr = str_replace(array_keys($baseReplace), array_values($baseReplace), $textInfos['baseStr']) . "\n";
        $hosts = "127.0.0.1    localhost\n";
        
        $domains = $this->getDomains();
        foreach ($domains as $domainBase => $infos) {
            $domain = $domainBase . $baseInfo['topDomain'];
            $domainStr = '';
            $isBase = in_array($domainBase, $baseInfo['baseDomain']);
            $domainStr = str_replace(array("\r", '#PLACE_DOMAIN_BASE#'), array('', $domain), $textInfos['baseDomain']) . "\n";
                
            foreach ($infos as $key => $info) {
                $fullDomain = $key . '.' . $domain;
                $hosts .= "127.0.0.1    {$fullDomain}\n";
                $domainStrSub = isset($info['type']) && ($info['type'] == 'node') ? $textInfos['nodeDomain'] : $textInfos['commonDomain'];
                $domainPath = isset($info['path']) && !empty($info['path']) ? $info['path'] : $key;
                $replaceInfos = array(
                    "\r" => '',
                    '#PLACE_DOMAIN#' => $fullDomain,
                    '#PLACE_DOMAIN_PATH#' => $baseInfo['wwwPath'] . $domainPath,
                    '#PLACE_HTTP_PATH#' => $baseInfo['httpdPath'],
                    '#PLACE_LOG_PATH#' => $baseInfo['logPath'],
                    '#PLACE_NODE_PORT#' => isset($info['port']) ? $info['port'] : '',
                    '#PLACE_EXT_INFO#' => isset($info['extInfo']) && isset($textInfos[$info['extInfo']]) ? str_replace(array_keys($baseReplace), array_values($baseReplace), $textInfos[$info['extInfo']]) : '',
                );
                $domainStrSub = str_replace(array_keys($replaceInfos), array_values($replaceInfos), $domainStrSub);
                $domainStrSub = str_replace("\r", '', $domainStrSub);
                $domainStr .= "\n" . $domainStrSub . "\n";
            }
        
            if ($isBase) {
                $baseStr .= "\n" . $domainStr;
            } else {
                $file = $baseInfo['tmpPath'] . 'conf/extra/httpd-vhosts-' . $domain . '.conf';
                Directory::mkdir(dirname($file));
                file_put_contents($file, $domainStr);
            }
            $hosts .= "\n";
        } 
        
        $baseFile = $baseInfo['tmpPath'] . 'conf/extra/httpd-vhosts.conf';
        Directory::mkdir(dirname($baseFile));
        file_put_contents($baseFile, $baseStr);
        $hostFile = $baseInfo['tmpPath'] . 'conf/hosts';
        Directory::mkdir(dirname($hostFile));
        file_put_contents($hostFile, $hosts);
    }

    protected function getDomains()
    {
        //'www' => array('path' => '', 'type' => '', 'port' => '', 'extInfo' => '')
        $domains = array(
            'iammumu' => array(
                'www' => array('path' => 'common', 'extInfo' => 'iammumuExt'),
            ),
            'julymom' => array(
                'www' => array('path' => 'common'),
            ),
            '91zuiai' => array(
                'dev.frame' => array('path' => 'acanstudio/devFrame/example'),
                'dev.node' => array('path' => 'acanstudio/firstNode', 'type' => 'node'),
                'node' => array('path' => 'wangcan/nodeProject', 'type' => 'node'),
                'dev.zf2' => array('path' => 'wangcan/devZf2'),
                'frame' => array('path' => 'wangcan/lightFrame-demo/public'),
                'zf2' => array('path' => 'wangcan/zf2Project/public'),
                'www' => array('path' => 'common'),
            ),
            'alyee' => array(
                //'*.test' => array('path' => ''),
                'dev.statistic' => array('path' => 'wangcan/ciProject/wwwroot/statistic'),
                'dev.pay' => array('path' => 'wangcan/ciProject/wwwroot/pay'),
                'dev.jzmedia' => array('path' => 'wangcan/ciProject/wwwroot/jzmedia'),
                'dev.passport' => array('path' => 'wangcan/ciProject/wwwroot/passport'),
                'dev.luxury' => array('path' => 'wangcan/ciProject/wwwroot/luxury'),
                'dev.www' => array('path' => 'wangcan/ciProject/wwwroot/website'),
            ),
            'acanstudio' => array(
                'front' => array('path' => 'acanstudio/webFront'),
                'ucserver' => array('path' => 'common/ucserver'),
                'static' => array('path' => 'common/static'),
                'asset' => array('path' => 'common/asset'),
                'upload' => array('path' => 'common/upload', 'extInfo' => 'docsUploadExt'),
                'dphp' => array('path' => 'common/dphp'),
                'www' => array('path' => 'wangcan/lightFrame-demo/public'),
                'docs' => array('path' => 'final/docsold', 'extInfo' => 'rewriteDocs'),
                'blog' => array('path' => 'acanstudio/blog/public'),
    
                'test1' => array('path' => 'test/test1/public'),
                'test2' => array('path' => 'test/test2/public'),
                'test3' => array('path' => 'test/test3'),
    
                'demo.luxury' => array('path' => 'final/ciProject/wwwroot/luxury'),
                'demo.statistic' => array('path' => 'final/ciProject/wwwroot/statistic'),
                'demo.ucserver' => array('path' => 'final/ciProject/wwwroot/ucserver'),
                'demo.upload' => array('path' => 'final/ciProject/wwwroot/upload'),
                'demo.static' => array('path' => 'final/ciProject/wwwroot/static'),
                'demo.pay' => array('path' => 'final/ciProject/wwwroot/pay'),
                'demo.jzmedia' => array('path' => 'final/ciProject/wwwroot/jzmedia'),
                'demo.passport' => array('path' => 'final/ciProject/wwwroot/passport'),
            ),
        );
    
        return $domains;
    }
}
    
function getTextInfos($isLocal = false)
{
$baseStr = <<<BASESTR
ServerName 127.0.0.1:80

<VirtualHost *:80>
    ServerName #PLACE_IP# 
    DocumentRoot "#PLACE_WWW_PATH#"
</VirtualHost>
BASESTR;

$baseDomain = <<<BASEDOMAIN
<VirtualHost *:80>
    ServerName #PLACE_DOMAIN_BASE#
    RedirectMatch permanent ^/(.*) http://www.#PLACE_DOMAIN_BASE#/$1
</VirtualHost>
BASEDOMAIN;

$virtualDomain = <<<VIRTUALDOMAIN
<VirtualHost *:80>
    ServerName #PLACE_DOMAIN_BASE#
    RedirectMatch permanent ^/(.*) http://www.#PLACE_DOMAIN_BASE#/$1
</VirtualHost>
VIRTUALDOMAIN;

$logStr = $isLocal ? '' : <<<LOGSTR
    ErrorLog "|#PLACE_HTTP_PATH#bin/rotatelogs #PLACE_LOG_PATH#%Y-%m-%d_#PLACE_DOMAIN#-error_log 86400"
    CustomLog "|#PLACE_HTTP_PATH#bin/rotatelogs #PLACE_LOG_PATH#%Y-%m-%d_#PLACE_DOMAIN#-access_log 86400" common
LOGSTR;

$commonDomain = <<<COMMONDOMAIN
<VirtualHost *:80>
    ServerAdmin iamwangcan@163.com
    DocumentRoot "#PLACE_DOMAIN_PATH#"
    ServerName #PLACE_DOMAIN#
{$logStr}
#PLACE_EXT_INFO#
</VirtualHost>
COMMONDOMAIN;

$nodeDomain = <<<NODEDOMAIN
<VirtualHost *:80>
    ServerAdmin iamwangcan@163.com
    ServerName #PLACE_DOMAIN#
    ProxyRequests off

    <Proxy *>
        Order deny,allow
        Allow from all 
    </Proxy>

    <Location />
        ProxyPass http://localhost:#PLACE_NODE_PORT#/
        ProxyPassReverse http://localhost:#PLACE_NODE_PORT#/
    </Location>
</VirtualHost>
NODEDOMAIN;

$iammumuExt = <<<IAMMUMUEXT
    Alias /mumupic "#PLACE_WWW_PATH#iammumupic"
    <Directory "#PLACE_WWW_PATH#iammumupic">
        AllowOverride AuthConfig
        AllowOverride All
        Order allow,deny
        Allow from all
        Require all granted
    </Directory>    
IAMMUMUEXT;

$docsUploadExt = <<<DOCSUPLOADEXT
    Alias /document "#PLACE_WWW_PATH#acanstudio/docs/"
    <Directory "#PLACE_WWW_PATH#acanstudio/docs/">
        AllowOverride AuthConfig
        #AllowOverride All
        Order allow,deny
        Allow from all
        #Require all granted
    </Directory>

#    RewriteEngine on
#    RewriteCond $1 !^(document)
#    RewriteRule   ^/([^/]+)/?(.*)    /$1/_build/html/$2
DOCSUPLOADEXT;

$rewriteDocs = <<<REWRITEDOCS
    RewriteEngine on
    RewriteCond $1 !^(zfuml)
    RewriteRule   ^/([^/]+)/?(.*)    /$1/_build/html/$2
REWRITEDOCS;

$textInfos = array(
    'baseStr' => $baseStr, 
    'baseDomain' => $baseDomain, 
    'commonDomain' => $commonDomain, 
    'nodeDomain' => $nodeDomain,
    'iammumuExt' => $iammumuExt, 
    'docsUploadExt' => $docsUploadExt, 
    'rewriteDocs' => $rewriteDocs
);

return $textInfos;
}    
