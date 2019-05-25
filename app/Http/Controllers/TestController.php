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

    function test2 () {
        $client = new Client();

        $response = $client->get('http://localhost:3004/projects?name=Squeaky Afterthought', [
            'headers' => [
                'Accept ' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded'
            ]
        ]);

        $results = json_decode($response->getBody(), true);

        return $results;
    }
}
