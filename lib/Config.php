<?php

class Config
{
    private $config = [];

    public function __construct (array $config = []) {
        $this->config = $config;
    }

    public function set ($key = null, $value = '') {

        if (is_null($key)) {
            $this->config[] = $value;
        } else {
            $this->config[$key] = $value;
        }
        
    }

    public function get ($key, $default = null) {

        $array = $this->config;

        if (is_null($key)) {
            return $array;
        }

        if (isset($array[$key])) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {

            if ((!is_array($array) || !array_key_exists($segment, $array))
                && (!$array instanceof ArrayAccess || !$array->offsetExists($segment))
            ) {
                return $default;
            }

            $array = $array[$segment];
        }

        return $array;
    }

    public function load ($config_path, $ext = '.php') {

        $configs = array_slice(scandir($config_path), 2);

        foreach ($configs as $config) {
            $key = basename($config, $ext);

            $this->config[$key] = include $config_path . '/' . $config;
        }
    }

}