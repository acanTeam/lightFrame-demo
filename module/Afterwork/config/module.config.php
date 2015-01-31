<?php

return array(
    'templates.path' => array('./module/Afterwork/view'),
    'routes' => array(
        'down' => array('/spider', '\Afterwork\Controller\DownController:index'),
        'stock' => array('/stock', '\Afterwork\Controller\StockController:index'),
    ),
);
