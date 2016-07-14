<?php

class Response
{

    private $template;
    private static $instance;

    private function __construct ($path) {
        $this->template = new Template($path);
    }

    // FIXME : this function should know when to json encode
    public static function send (array $data, $path = '/public/response.tpl.php') {

        self::$instance = new Response($path);
        self::$instance->template->data = json_encode($data);
        return self::$instance->template;
    }
}