<?php

return array(
    'templates.path' => array('./module/SmallCode/view'),
    'routes' => array(
        'ciauto' => array('/codelib/ciauto', '\Codelib\Controller\CiautoController:index'),
        'domain' => array('/codelib/domain', '\Codelib\Controller\DomainController:index'),
        'evil' => array('/codelib/evil', '\Codelib\Controller\EvilController:index'),
    ),
);
