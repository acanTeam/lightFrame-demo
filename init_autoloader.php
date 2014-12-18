<?php
// Composer autoloading
if (file_exists('vendor/autoload.php')) {
    $loader = include 'vendor/autoload.php';
} else {
    throw new \RuntimeException('Unable to load Light. Run `php composer.phar install`.');
}

$configs = dealApplications($loader);
$app = new \Light\Mvc\Application($configs['baseConfig']);
$app->routeInfos = $configs['routeInfos'];
$app->configCommon = require './config/local.config.php';

foreach ($configs['routeInfos'] as $module => $routes) {
    if (!is_array($routes) || empty($routes)) {
        continue ;
    }

    foreach ($routes as $name => $route) {
        $pattern = array_shift($route);
        $callable = array_pop($route);
        $app->get($pattern, $callable);
    }
}

// Run app
$app->run();

function dealApplications(& $loader)
{
    $applicationInfos = require './config/application.config.php';
    $modulePaths = $applicationInfos['module_paths'];

    $baseConfig['templates.path'] = $routeInfos = array();
    foreach ($applicationInfos['modules'] as $module) {
        $moduleConfigFile = '';
        foreach ($modulePaths as $modulePath) {
            $modulePathBase = $modulePath . '/' . $module;
            $baseConfig['modulePath'][$module] = $modulePathBase;
            $loader->set($module, $modulePathBase . '/src/');
            $moduleConfigFile = $modulePathBase . '/config/module.config.php';
            if (file_exists($moduleConfigFile)) {
                $moduleConfig = require $moduleConfigFile;
                if (isset($moduleConfig['templates.path'])) {
                    $baseConfig['templates.path'] = array_merge($baseConfig['templates.path'], $moduleConfig['templates.path']);
                }

                if (isset($moduleConfig['routes'])) {
                    $routeInfos[$module] = $moduleConfig['routes'];
                }
                
                break;
            }
        }
    }
    
    $data = array('baseConfig' => $baseConfig, 'routeInfos' => $routeInfos);
    return $data;
}
