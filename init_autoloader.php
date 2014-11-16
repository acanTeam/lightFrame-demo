<?php
// Composer autoloading
if (file_exists('vendor/autoload.php')) {
    $loader = include 'vendor/autoload.php';
} else {
    throw new \RuntimeException('Unable to load Light. Run `php composer.phar install`.');
}

// Prepare app
$app = new \Light\Mvc\Application(array(
    'templates.path' => './module/Application/Views',
));

// Define routes
$app->get('/', function () use ($app) {
    // Sample log message
    $app->log->info("Slim-Skeleton '/' route");
    // Render index view
    $app->render('index.html');
});

// Run app
$app->run();
