<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SearchRequest;
use GuzzleHttp\Client;

class SearchController extends Controller
{
    public function index()
    {
        $results = null;
        return view('search/results', compact('results'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'search_string' => 'required'
        ]);

        $searchQuery = $request->input('search_string');
        $resultArray = Helper::getSearchResults($searchQuery);

        return view('search/index', compact('searchQuery'))->with('resultArray', json_encode($resultArray));
    }

    public function recommendations(Request $request)
    {

        $componentNames = Helper::componentNames();
        $filtered = $componentNames->filter(function($value, $key) use ($request) {
            return stripos($value, $request->input('q')) !== false;
        });

        return $filtered;
    }

    public function results (Request $request) {
        $request->validate([
            'search_string' => 'required'
        ]);

        $searchQuery = $request->input('search_string');
        $resultArray = Helper::getSearchResults($searchQuery);

        return view('search/results', compact('searchQuery'))->with('resultArray', json_encode($resultArray));
    }
}
