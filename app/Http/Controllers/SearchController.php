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
        return view('search/index', compact('results'));
    }

    public function search(SearchRequest $request)
    {
        $resultArray = []; // Stores trimmed results
        $client = new Client();

        $response = $client->get('http://localhost:3004/projects', [
            'headers' => [
                'Accept ' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded'
            ]
        ]);

        $results = json_decode($response->getBody(), true);

        foreach ($results as $result){
            $issues = $result['issues'];
            $projectArray = [];
            $worklogArray = [];
            $bugArray = [];
            $writeAccess = false; // Ignore empty worklogs
            $totalSpendTime = 0;
            
            if ($issues != null) {

                foreach ($issues as $issue) {
                    $issueComponenets = $issue['components'];

                    foreach ($issueComponenets as $issueComponenet) {
                        if (!strcasecmp($issueComponenet, $request->search_string)) { // Ignoring case sensitive search
                            if (count($issue['worklogs']) > 0) {
                                $writeAccess = true;
                                $projectArray['project'] = $result['name'];

                                foreach ($issue['worklogs'] as $item) {
                                    if ($issue['issueType'] != 'Bug') {
                                        array_push($worklogArray, $item);
                                        $projectArray['logs'] = $worklogArray;
                                    } else {
                                        array_push($bugArray, $item);
                                        $projectArray['bugs'] = $bugArray;
                                    }
                                }

                                $projectArray['originalEstimate'] = 80;
                                $projectArray['realTime'] = 100;
                            }
                        }
                    }
                }

                if ($writeAccess) {
                    array_push($resultArray, $projectArray);
                }
            }
        }

        return view('home')->with('resultArray');
    }

    public function recommendations(Request $request)
    {
        $componentNames = Helper::componentNames();
        $filtered = $componentNames->filter(function($value, $key) use ($request) {
            return strpos($value, $request->input('q')) !== false;
        });

        return $filtered;

    }
}
