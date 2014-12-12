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
            $application->render('map.html', array('application' => $application));
        }),
        'hello' => array('/hello', '\Application\Controller\IndexController:hello'),
        'world' => array('/world', '\Application\Controller\IndexController:world'),
    ),
);
