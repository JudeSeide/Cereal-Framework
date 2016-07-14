<?php

class Template
{

    private $data = [];

    private $file = '';

    public function __construct ($file, array $data = []) {

        $file = preg_replace("#\.tpl\.php$#", "", $file) . ".tpl.php";

        $this->file = ROOT_PATH . '/' . config('view') . $file;
        $this->data = $data;
    }

    public function set ($key, $value) {
        $this->data [$key] = $value;
    }

    public function __set ($key, $value) {
        $this->set($key, $value);
    }

    public function get ($key) {

        if (isset($this->data [$key])) {
            return $this->data [$key];
        }

        return null;
    }

    public function __get ($key) {
        return $this->get($key);
    }

    public function __toString () {

        try {

            extract($this->data);

            ob_start();

            include($this->file);

            $contents = ob_get_contents();
            ob_end_clean();

            return $contents;

        } catch (Exception $e) {

            app('logger')->error($e->getMessage() . "\n" . $e->getTraceAsString());
            return '';
        }
    }
}

