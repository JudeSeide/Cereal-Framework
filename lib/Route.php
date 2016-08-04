<?php

class Route
{

    private static $routes = [];

    private static function init () {
        $url = strtolower($_SERVER["HTTP_HOST"]);
        $url = preg_replace("#^www\.#", "", $url);
        $url = preg_replace("#:[\d]+$#", "", $url);

        self::block($url);

        return parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    }

    private static function block ($url) {

        $forbidden = env('BLOCKED_URLS');
        $forbidden = explode('|', $forbidden);

        if (in_array($forbidden, $url)) {
            Util::show404('Access denied');
        }
    }

    public static function run () {

        $url = self::init();

        $chain = explode('/', $url);

        $path = $url == '/' ? '/' : $chain[1];

        if (!array_key_exists($path, self::$routes)) {
            Util::show404();
        }

        self::request($path, self::$routes[$path]);
    }

    public static function url ($path, $controller = null) {
        self::$routes[$path] = $controller;
    }

    public static function request ($path, $controller = null) {

        $url = self::init();

        if ($path == '/') {

            $controller = $controller ? $controller : 'Public';

            app('config')->set('controller', config('controller') . '/' . $controller);

        } else {

            $pattern = '#^\/' . $path . '\/?#i';

            if (preg_match($pattern, $url)) {

                $path = ucfirst($path);

                $url = preg_replace($pattern, '', $url);
                $controller = $controller ? $controller : $path;

                app('config')->set('controller', config('controller') . '/' . $controller);

            }

        }
        
        configure(config('controller'));

        echo new DefaultController($url);
    }

}