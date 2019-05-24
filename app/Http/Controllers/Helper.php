<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Nahid\JsonQ\Jsonq;


class Helper {
    public static function client() {
        return new Client([
            // Base URI is used with relative requests
            'base_uri' => 'localhost:3004',
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);
    }

    public static function db() {
        $dir = dirname(__FILE__);
        $q = new Jsonq($dir . '/db.json');
        return $q;
    }
}
