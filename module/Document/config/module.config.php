<?php

return array(
    'templates.path' => array('./module/Document/view'),
    'routes' => array(
        //'index' => array('/document', '\Document\Controller\IndexController:index'),
        'document' => array('/document', '\Document\Controller\DocumentController:index'),
        'documentshow' => array('/document/:docs+', '\Document\Controller\DocumentController:docs'),
        'demo1' => array('/document/demo/:aaa/:bbb', '\Document\Controller\DemoController:index'),
        'phuml' => array('/document/phuml', '\Document\Controller\FilesysController:phuml'),
        'movebom' => array('/document/movbom', '\Document\Controller\FilesysController:movebom'),
    ),
);
