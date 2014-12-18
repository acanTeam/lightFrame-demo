<?php

return array(
    'templates.path' => array('./module/Document/view'),
    'routes' => array(
        'index' => array('/document', '\Document\Controller\IndexController:index'),
        'demo' => array('/document/demo', '\Document\Controller\DemoController:index'),
    ),
);
