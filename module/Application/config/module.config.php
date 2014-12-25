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
        'listinfo' => array('/lbs/listinfo', '\Application\Controller\SurveyController:listinfo'),
        'statistic' => array('/lbs/statistic', '\Application\Controller\SurveyController:statistic'),
        'show' => array('/lbs/show', '\Application\Controller\SurveyController:show'),
        'survey' => array('/lbs/survey', '\Application\Controller\SurveyController:index'),
        'answer' => array('/lbs/answer', 'post', '\Application\Controller\SurveyController:answer'),
        'answers' => array('/lbs/answer', '\Application\Controller\SurveyController:answer'),
        'bootstrapdemo' => array('/bootstrap/demo', '\Application\Controller\BootstrapController:demo'),
        'bootstrapdemoshow' => array('/bootstrap/demo/:demo', '\Application\Controller\BootstrapController:demo'),
        'bootstrapexample' => array('/bootstrap/example', '\Application\Controller\BootstrapController:example'),
        'bootstrapexampleshow' => array('/bootstrap/example/:example', '\Application\Controller\BootstrapController:example'),
    ),
);
