<?php
namespace Document\Util\Page;

class Error extends Simple
{
    const NORMAL_ERROR_TYPE = 'NORMAL_ERROR';
    const MISSING_PAGE_ERROR_TYPE = 'MISSING_PAGE_ERROR';
    const FATAL_ERROR_TYPE = 'FATAL_ERROR';

    private $params;
    private $type;
    private static $template;

    public function __construct($title, $content, $params)
    {
        parent::__construct($title, $content);
        $this->params = $params;
        $this->type = $params['error_type'];
    }

    public function display()
    {
        $code = $this->type === static::MISSING_PAGE_ERROR_TYPE ? 404 : 500;
        http_response_code($code);
        parent::display();
    }

    public function getPageContent()
    {
        if ($this->type !== static::FATAL_ERROR_TYPE && is_null(static::$template)) {
            include_once($this->params['theme']['error-template']);
            static::$template = new Template();
        }

        if (is_null($this->html)) {
            $this->html = $this->generatePage();
        }

        return $this->html;
    }

    public function generatePage()
    {
        if ($this->type === static::FATAL_ERROR_TYPE) {
            return $this->content;
        }

        $params = $this->params;
        $page['title'] = $this->title;
        $page['theme'] = $params['theme'];
        $page['content'] = $this->content;
        $page['google_analytics'] = $params['google_analytics'];
        $page['piwik_analytics'] = $params['piwik_analytics'];

        return static::$template->get_content($page, $params);
    }
}
