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

    public static function convertJiraTime($jiraTime)
    {
        $arrayTime = str_split($jiraTime);
        $convertedTime = null;
        $temp1 = str_replace('P', '', $arrayTime);
        $temp2 = str_replace('T', '', $temp1);
        $temp3 = str_replace('H', ':', $temp2);
        $convertedTime = implode('', $temp3);

        if (last($temp3) == ':') {
            $convertedTime = $convertedTime.'00';
        }

        if (last($temp3) == 'M' && !preg_match("/:/", $convertedTime)) {
            $convertedTime = '00:'.$convertedTime;
        }

        $convertedTime = str_replace('M', '', $convertedTime);

        $calculateSecondArray = explode(':', $convertedTime);
        $calculateSecond = $calculateSecondArray[0] * 60 + $calculateSecondArray[1];

        return response()->json([
            'convertedTime' => $convertedTime,
            'calculateSecond' => $calculateSecond
        ]);
    }

    public static function userRole($username) {
        $length = strlen($username);
        if (($length % 6) == 1) {
            return 'BD';
        } elseif (($length % 6) == 2) {
            return 'FD';
        } elseif (($length % 6) == 3) {
            return 'SA';
        } elseif (($length % 6) == 4) {
            return 'QA';
        } elseif (($length % 6) == 5) {
            return 'PM';
        } else {
            return 'BA';
        }
    }

}
