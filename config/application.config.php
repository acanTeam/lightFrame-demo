<?php
require dirname(__FILE__) . '/systemFunction.php';

return array(

    'module_paths' => array(
        dirname(__DIR__) . '/module',
        dirname(__DIR__) . '/vendor',
    ),
    'modules' => array(
        'Application',
        'Spider',
        'Document',
        'Codelib',
    ),
);
