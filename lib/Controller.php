<?php


abstract class Controller
{

    public static $template = null;

    protected $initialized = false;

    public static $callChain = array();

    public function __construct ($segments) {

        if (!is_array($segments)) {

            $urlPath = preg_replace("#[\\\/]+#", '/', parse_url($segments, PHP_URL_PATH));
            $segments = array_values(array_filter(array_map('ucwords', explode("/", $urlPath))));
        }

        if (!count($segments)) {
            $segments[] = 'Index';
        }

        $class = get_class($this);

        if (!$this->initialized) {
            $this->init();
            $this->initialized = true;
        }

        if (self::actionExists($class, $segments)) {

            self::$callChain[] = 'action-' . strtolower($segments[0]);
            call_user_func_array(array($this, $segments[0] . 'Action'), array_slice($segments, 1));

        } else {

            if (self::actionExists($class, array_merge(array('Index'), $segments))) {

                self::$callChain[] = 'action-' . strtolower('Index');
                call_user_func_array(array($this, 'IndexAction'), $segments);

            } else {

                self::$callChain[] = 'controller-' . strtolower($segments[0]);
                self::callNextController($segments);

            }

        }
    }

    protected static function actionExists ($class, array $segments) {

        if (!in_array($segments[0] . 'Action', get_class_methods($class))) {
            return false;
        }

        $count = count($segments) - 1;
        $rf = new ReflectionMethod($class, $segments[0] . 'Action');

        return ($rf->getNumberOfRequiredParameters() == $count) || ($rf->getNumberOfParameters() == $count);
    }

    protected static function callNextController ($segments) {

        $controller_name = $segments[0] . 'Controller';
        $controller_path = ROOT_PATH . '/' . config('controller') . '/' . $controller_name . '.php';

        if (!(file_exists($controller_path) && class_exists($controller_name))) {
            Util::show404("The requested page doesn't exist");
        }

        app('config')->set('controller', config('controller') . '/' . $segments[0]);

        new $controller_name(array_slice($segments, 1));
    }

    public function __toString () {
        return (string)self::$template;
    }

    public abstract function init ();

    public abstract function IndexAction ();

}

