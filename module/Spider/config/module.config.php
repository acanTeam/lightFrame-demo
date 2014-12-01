<?php

return array(
    'templates.path' => array('./module/SmallCode/view'),
    'routes' => array(
        'down' => array('/spider/down', '\Spider\Controller\DownController:index'),
        'stock' => array('/spider/stock', '\Spider\Controller\StockController:index'),
    ),
);
