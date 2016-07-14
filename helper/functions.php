<?php

if (!function_exists('env')) {

    function env ($var_name, $default = '') {
        return getenv($var_name) ? getenv($var_name) : $default;
    }
}

if (!function_exists('config')) {

    function config ($key = null, $default = null) {

        if (is_null($key)) {
            return app('config');
        }

        if (is_array($key)) {
            return app('config')->set($key);
        }

        return app('config')->get($key, $default);
    }
}

if (!function_exists('app')) {

    function app ($key = null) {

        $app = Container::singleton()->get();

        if ($key && isset($app[$key])) {
            return $app[$key];
        }

        return null;
    }
}

if (!function_exists('configure')) {

    function configure ($path) {
        $GLOBALS['__autoload:list'][] = _ROOT_ . '/' . $path;
    }
}

function _autoload($class) {
    foreach ($GLOBALS['__autoload:list'] as $lib) {
        $file = $lib . '/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;

            return 1;
        }
    }
}

spl_autoload_register('_autoload');