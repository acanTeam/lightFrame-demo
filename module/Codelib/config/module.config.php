<?php

return array(
    'templates.path' => array('./module/Codelib/view'),
    'routes' => array(
        'ciauto' => array('/codelib/ciauto', '\Codelib\Controller\CiautoController:index'),
        'domain' => array('/codelib/domain/:type', '\Codelib\Controller\DomainController:index'),
        'evil' => array('/codelib/evil', '\Codelib\Controller\EvilController:index'),
    ),
);
