<?php

class Util
{

    public static function isAjax () {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']);
    }

    public static function redirect ($url) {
        if (self::isAjax()) {
            echo '<script type="text/javascript"><!-- window.location = "' . $url . '" //--></script>';
        } else {
            header('Location: ' . $url);
        }
        exit;
    }

    public static function refresh () {
        if (self::isAjax()) {
            echo '<script type="text/javascript"><!-- window.location = "' . $_SERVER['REQUEST_URI'] . '" //--></script>';
        } else {
            header('Location: ' . $_SERVER['REQUEST_URI']);
        }
        exit;
    }

    public static function get_request_domain () {
        return 'http' . (empty($_SERVER['HTTPS']) ? '' : 's') . '://' . $_SERVER['SERVER_NAME'];
    }

    public static function get_full_request_uri ($args = false) {

        $url = 'http' . (empty($_SERVER['HTTPS']) ? '' : 's') . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

        if (!$args) {
            $url = preg_replace("#^([^\?]+)\?.*$#", "$1", $url);
        }

        return $url;
    }

    public static function getRequestProtocol () {
        return $url = 'http' . (empty($_SERVER['HTTPS']) ? '' : 's');
    }

    public static function back ($fallback = null) {

        if (isset($_SERVER) && isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        if ($fallback) {
            header('Location: ' . $fallback);
            exit;
        }

        throw new Exception("Could not find the HTTP_REFERER");
    }

    public static function show404Image ($img = '/../public/img/404.png', $content_type = 'image/png') {
        header("Content-type: " . $content_type);
        readfile(__DIR__ . $img);
        exit;
    }

    public static function show404 ($message = null) {
        Util::sendHeader(404);

        if ($message === null) {
            $message = 'The requested URL <B>' . $_SERVER['REQUEST_URI'] . '</b> was not found on this server.<br />If you entered the URL manually please check your spelling and try again.';
        }
        echo $message;
        exit;
    }

    public static function getStatusCodes() {
        return [
            100 => 'Continue',
            101 => 'Switching Protocols',
            102 => 'Processing',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            207 => 'Multi-Status',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            422 => 'Unprocessable Entity',
            423 => 'Locked',
            424 => 'Failed Dependency',
            426 => 'Upgrade Required',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            506 => 'Variant Also Negotiates',
            507 => 'Insufficient Storage',
            509 => 'Bandwidth Limit Exceeded',
            510 => 'Not Extended'
        ];
    }

    public static function sendHeader ($statusCode) {

        if (!isset($_SERVER) || !isset($_SERVER['SERVER_PROTOCOL']) || headers_sent()) {
            return;
        }

        static $status_codes = null;

        if ($status_codes === null) {
            $status_codes = self::getStatusCodes();
        }

        if ($status_codes[$statusCode] !== null) {

            $status_string = $statusCode . ' ' . $status_codes[$statusCode];
            header($_SERVER['SERVER_PROTOCOL'] . ' ' . $status_string, true, $statusCode);

        }
    }

    public static function getIP () {

        $ip = [];

        if (isset($_SERVER["REMOTE_ADDR"])) {
            $ip[] = $_SERVER["REMOTE_ADDR"];
        }

        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip[] = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }

        if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $ip[] = $_SERVER["HTTP_CLIENT_IP"];
        }

        if (!count($ip)) {
            $ip[] = "unknown";
        }

        return implode(', ', $ip);
    }

    public static function isUrl ($src) {
        
        $regex = "((https?|ftp)\:\/\/)?"; // SCHEME
        $regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass
        $regex .= "([a-z0-9-.]*)\.([a-z]{2,3})"; // Host or IP
        $regex .= "(\:[0-9]{2,5})?"; // Port
        $regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path
        $regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
        $regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?"; // Anchor

        return (preg_match("/^$regex$/", $src));
    }

}