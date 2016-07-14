<?php

define('_ROOT_', __DIR__);
define('ROOT_PATH', _ROOT_);
define('_WEB_ROOT_', _ROOT_ . '/public');

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/helper/functions.php';

configure('lib');
configure('app/Controllers');
configure('app/Models');


// FIXME : we should load the header from config
header('Content-type: application/json; charset=utf-8');
