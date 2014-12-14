<?php

return array(
    'templates.path' => array('./module/Application/view'),
    'routes' => array(
        'index' => array('/', function() {
            $application = \Light\Mvc\Application::getInstance();
            $application->render('index.html');
        }),
        'map' => array('/map', function() {
            $application = \Light\Mvc\Application::getInstance();
            $application->layout('map', 'common/layout', array('application' => $application));
        }),
        'bootstrap' => array('/bootstrap/:template', '\Application\Controller\IndexController:bootstrap'),
        'hello' => array('/hello', '\Application\Controller\IndexController:hello'),
        'world' => array('/world', '\Application\Controller\IndexController:world'),
    ),
);
