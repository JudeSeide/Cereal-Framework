<?php

class Response
{

    private $template;
    private static $instance;

    private function __construct ($path) {
        $this->template = new Template($path);
    }

    /**
     * By default this function will return json
     * Edit to change behavior
     *
     * @param array $data
     * @param string $path
     * @return Template
     */
    public static function send (array $data, $path = '/public/response.tpl.php') {

        self::$instance = new Response($path);
        self::$instance->template->data = json_encode($data);
        return self::$instance->template;
    }
}