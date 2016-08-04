<?php

require_once __DIR__ . '/../autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__ . '/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework.
|
*/

$app = [];

/*
|--------------------------------------------------------------------------
| Load the configs
|--------------------------------------------------------------------------
|
*/

$config = new Config();
$config->load(_ROOT_ . '/config');

$app['config'] = $config;

/*
|--------------------------------------------------------------------------
| Set our logger
|--------------------------------------------------------------------------
|
*/

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$path = $config->get('app.log');
$level = $config->get('app.log_level');

$log = new Logger('');
$log->pushHandler(new StreamHandler($path, $level));

$app['logger'] = $log;

/*
|--------------------------------------------------------------------------
| Set content type and charset for the application
|--------------------------------------------------------------------------
|
*/

header('Content-type: application/json; charset=utf-8');

/*
|--------------------------------------------------------------------------
| Storing the app so we can get it later
|--------------------------------------------------------------------------
|
*/

$container = Container::singleton();
$container->store($app);