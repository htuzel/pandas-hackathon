<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index()
    {
        $results = null;
        return view('search/index', compact('results'));
    }

    public function recommendations()
    {
        $recommendations = null;
        return view('search/recommendations', compact('recommendations'));
    }
}
