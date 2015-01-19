<?php
namespace Application\Controller;

use \Light\Mvc\Controller\ControllerAbstract as ControllerAbstract;

class IndexController extends ControllerAbstract
{
    public function __construct()
    {
        $this->currentModule = 'Application';
        parent::__construct();
    }

    public function index()
    {
        $navbarInfos = $this->_getNavbar();
        $data = array(
            'navbarInfos' => $navbarInfos,
            'application' => $this->application,
            'infos' => $this->_getInfos(),
        );
        
        $this->application->layout('index', 'common/google_layout', $data);
    }

    public function soft()
    {
        $navbarInfos = $this->_getNavbar();
        $data = array(
            'navbarInfos' => $navbarInfos,
            'application' => $this->application,
            'infos' => $this->_getSoftInfos(),
        );
        
        $this->application->layout('soft', 'common/google_layout', $data);
    }

    public function form()
    {
        $string = '';
        $result = $this->_authcode($string, 'DECODE', '');
        print_r(explode('+', $result));exit();
        $this->application->layout('form', 'common/layout', array('application' => $this->application));
    }

    public function map()
    {
        $this->application->render('map');
    }

    private function _getInfos()
    {
        $docsElems = array(
            'structure' => array(
                'name' => '数据结构和算法',
                'url' => '',
                'thumb' => 'http://www.bootcss.com/p/google-bootstrap/assets/img/example-sites/kippt.png',
            ),
            'structure1' => array(
                'name' => '数据结构和算法',
                'url' => '',
                'thumb' => 'acanstudio/kippt.png',
            ),
            'structure2' => array(
                'name' => '数据结构和算法',
                'url' => '',
                'thumb' => 'acanstudio/kippt.png',
            ),
            'structure3' => array(
                'name' => '数据结构和算法',
                'url' => '',
                'thumb' => 'acanstudio/kippt.png',
            ),
        );
        $bootstrapElems = array(
            'officialExample' => array(
                'name' => '官方示例',
                'url' => '',
                'thumb' => 'acanstudio/bootstrap.png',
            ),
            'demo' => array(
                'name' => 'demo',
                'url' => $this->application->domain . 'bootstrap/theme',
                'thumb' => 'acanstudio/bootstrap-demo.png',
            ),
            'structure2' => array(
                'name' => '数据结构和算法',
                'url' => '',
                'thumb' => 'acanstudio/kippt.png',
            ),
            'structure3' => array(
                'name' => '数据结构和算法',
                'url' => '',
                'thumb' => 'acanstudio/kippt.png',
            ),
        );
        $lightFrameElems = array(
            'softlist' => array(
                'name' => '软件汇总',
                'url' => $this->application->domain . 'soft',
                'thumb' => 'acanstudio/softlist.png',
            ),
            'phuml' => array(
                'name' => '常见UML图',
                'url' => $this->application->domain . 'codelib/phumlshow',
                'thumb' => 'acanstudio/phuml.png',
            ),
            'structure2' => array(
                'name' => '数据结构和算法',
                'url' => '',
                'thumb' => 'acanstudio/kippt.png',
            ),
            'structure3' => array(
                'name' => '数据结构和算法',
                'url' => '',
                'thumb' => 'acanstudio/kippt.png',
            ),
        );

        $infos = array(
            'docs' => array(
                'title' => '文档系统',
                'url' => 'docs',
                'description' => '基于markdown轻量级标记语言，把工作和学习过程中的知识体系结构以文档的形式表现出来。',
                'elems' => $docsElems
            ),
            'bootstrap' => array(
                'title' => 'bootstrap',
                'url' => 'bootstrap',
                'description' => '基于boostrap和bootstrap中文网，搜集整理bootstrap能为我所用的相关资源',
                'elems' => $bootstrapElems
            ),
            'lightFrame' => array(
                'title' => 'lightFrame框架',
                'url' => 'lightFrame',
                'description' => '自主研发的基于PHP5.3以上版本的PHP轻量级框架',
                'elems' => $lightFrameElems
            ),
        );

        return $infos;
    }

    private function _getSoftInfos()
    {
        $infos = array(
            'softlist' => array(
                'name' => '软件列表',
            ),
            'systeminstall' => array(
                'name' => '安装操作系统',
            ),
            'questionlist' => array(
                'name' => '问题汇总',
            ),
        );

        return $infos;
    }

    private function _authcode($string, $operation = 'DECODE', $key = '', $expiry = 0)
    {
        $ckey_length = 4;    // 随机密钥长度 取值 0-32;
        // 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
        // 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
        // 当此值为 0 时，则不产生随机密钥

        $key = md5($key ? $key : UC_KEY);
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = array();
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if($operation == 'DECODE') {
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc.str_replace('=', '', base64_encode($result));
        }

    }
}
