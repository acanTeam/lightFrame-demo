<?php
namespace Application\Controller;

class IndexController 
{
    public function __construct()
    {
        echo 'init index';
    }

    public function hello()
    {
        echo 'hello';
    }

    public function world()
    {
        echo 'world';
    }
}
