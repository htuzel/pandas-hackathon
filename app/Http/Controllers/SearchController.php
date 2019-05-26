<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SearchRequest;
use GuzzleHttp\Client;

class SearchController extends Controller
{
    public function index() {
        $results = null;
        return view('search/results', compact('results'));
    }

    public function search(SearchRequest $request) {
        $searchQuery = $request->input('search_string');
        $resultArray = Helper::getSearchResults($searchQuery);

        return view('search/index', compact('searchQuery'))->with('resultArray', json_encode($resultArray));
    }

    public function recommendations(Request $request) {
        /* This function takes component names dynamically. but we used static data for performance.
        (our datas format limited us. if we can access real data, performance is not a big deal)
         $componentNames = Helper::componentNames();
        */
        $componentNames = Components::get();
        $componentCollection = collect($componentNames);
        $filtered = $componentCollection->filter(function($value, $key) use ($request) {
            return stripos($value, $request->input('q')) !== false;
        });

        return $filtered;
    }

    public function estimation (SearchRequest $request) {
        $searchQuery = $request->input('search_string');
        $searchQueryArray = $request->input('search_string_array');

        $resultArray = Helper::getSearchResults($searchQuery);

        return view('search/estimation', compact('searchQuery', 'searchQueryArray'))->with('resultArray', json_encode($resultArray));
    }
}
