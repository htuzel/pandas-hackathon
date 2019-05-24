<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{

    function test () {

        $response = GuzzleClient::client()->request('GET', 'projects');

        return $response;
    }
}
