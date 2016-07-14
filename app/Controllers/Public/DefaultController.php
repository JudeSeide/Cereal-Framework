<?php

class DefaultController extends Controller
{

    public function init () {}

    public function IndexAction () {

        self::$template = Response::send([
            'status' => 200,
            'data' => [],
            'message' => 'success'
        ]);

    }

}

