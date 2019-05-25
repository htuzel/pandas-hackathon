<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Nahid\JsonQ\Jsonq;
use Illuminate\Support\Collection;


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

    public static function projects() {
        $projects = self::db()->from('projects')
                        ->get();
        return collect($projects);
    }

    public static function users() {
        $users = self::db()->from('users')
                        ->get();
        return collect($users);
    }

    public static function componentNames () {
        $projectsCount = self::db()->from('projects')
                            ->count();

        $components = [];
        for ($i = 0; $i<$projectsCount; $i++) {
            $tempComponents = self::db()->from('projects.' . $i . '.components')
                            ->get();
            $components = array_merge($components,$tempComponents);
        }
        $components = array_unique($components);
        return collect($components);
    }


}
