<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;


class TestController extends Controller
{

    function test () {

        $response = Helper::client()->request('GET', 'projects');
        return $response;
    }

}
