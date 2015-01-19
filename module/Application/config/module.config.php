<?php

return array(
    'templates.path' => array('./module/Application/view'),
    'routes' => array(
        'index' => array('/', '\Application\Controller\IndexController:index'),
        'soft' => array('/soft', '\Application\Controller\IndexController:soft'),
        'map' => array('/map', '\Application\Controller\IndexController:map'),
        'form' => array('/bootstrap/form', '\Application\Controller\IndexController:form'),

        'listinfo' => array('/lbs/listinfo', '\Application\Controller\SurveyController:listinfo'),
        'statistic' => array('/lbs/statistic', '\Application\Controller\SurveyController:statistic'),
        'show' => array('/lbs/show', '\Application\Controller\SurveyController:show'),
        'survey' => array('/lbs/survey', '\Application\Controller\SurveyController:index'),
        'answer' => array('/lbs/answer', 'post', '\Application\Controller\SurveyController:answer'),
        'answers' => array('/lbs/answer', '\Application\Controller\SurveyController:answer'),

        'bootstrapdemo' => array('/bootstrap', '\Application\Controller\BootstrapController:demo'),
        'bootstrapdemoshow' => array('/bootstrap/:demo', '\Application\Controller\BootstrapController:demo'),
        'bootstrapplugin' => array('/bootstrap/plugin', '\Application\Controller\BootstrapController:plugin'),
    ),
);
