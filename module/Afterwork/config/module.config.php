<?php

return array(
    'templates.path' => array('./module/Afterwork/view'),
    'routes' => array(
        'down' => array('/spider', '\Afterwork\Controller\DownController:index'),
        'stock' => array('/stock', '\Afterwork\Controller\StockController:index'),
        'stock_map' => array('/stock/map', '\Afterwork\Controller\StockController:map'),
        'stock_listinfo' => array('/stock/listinfo/:path(/:title)', '\Afterwork\Controller\StockController:listinfo'),
        'video' => array('/video', '\Afterwork\Controller\VideoController:index'),
    ),
);
