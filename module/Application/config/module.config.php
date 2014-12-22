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
        'form' => array('/bootstrap/form', '\Application\Controller\IndexController:form'),
        'bootstrapdemo' => array('/bootstrap/demo', '\Application\Controller\BootstrapController:demo'),
        'bootstrapdemoshow' => array('/bootstrap/demo/:demo', '\Application\Controller\BootstrapController:demo'),
        'bootstrapexample' => array('/bootstrap/example', '\Application\Controller\BootstrapController:example'),
        'bootstrapexampleshow' => array('/bootstrap/example/:example', '\Application\Controller\BootstrapController:example'),
    ),
);
