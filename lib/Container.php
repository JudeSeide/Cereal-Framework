<?php

class Container
{

    private $config;
    private static $instance;

    private function __construct () {}

    public static function singleton () {
        
        if (!isset(self::$instance)) {
            self::$instance = new Container();
        }

        return self::$instance;
    }

    public function store ($config) {
        $this->config = $config;
    }

    public function get () {
        return $this->config;
    }
}