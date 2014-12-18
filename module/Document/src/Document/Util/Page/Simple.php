<?php
namespace Document\Util\Page;

class Simple implements PageInterface
{
    protected $title;
    protected $content;
    protected $html = null;

    public function __construct($title, $content)
    {
        $this->initializePage($title, $content);
    }

    public function initializePage($title, $content) {
        $this->title = $title;
        $this->content = $content;
    }

    public function  display()
    {
        header('Content-type: text/html; charset=utf-8');
        echo $this->getPageContent();
    }

    public function getPageContent()
    {
        if (is_null($this->html)) {
            $this->html = $this->_generatePage();
        }

        return $this->html;
    }

    private function _generatePage()
    {
        return $this->content;
    }
}
