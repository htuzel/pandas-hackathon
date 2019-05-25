<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{

    function test () {

        $response = Helper::client()->request('GET', 'projects');
        return $response;
    }

    function test2 () {

        $res = Helper::db()->from('projects')
                ->find('worklogs')
                ->get();
        dd($res);

    }

}
