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
            $projectArray['realTime'] = 0;
            $projectArray['originalEstimate'] = 0;
            $bugCounter = 0;
            
            if ($issues != null) {

                foreach ($issues as $issue) {
                    $issueComponenets = $issue['components'];

                    foreach ($issueComponenets as $issueComponenet) {
                        if (!strcasecmp($issueComponenet, $request->search_string)) { // Ignoring case sensitive search
                            if (count($issue['worklogs']) > 0) {
                                $writeAccess = true;
                                $projectArray['project'] = $result['name'];
                                $projectArray['originalEstimate'] += Helper::convertJiraTime($issue['originalEstimate'])->getData()->calculateSecond;

                                foreach ($issue['worklogs'] as $item) {
                                    $processedTime = Helper::convertJiraTime($item['timeSpent'])->getData();
                                    $item['timeSpent'] = $processedTime->convertedTime;
                                    $projectArray['realTime'] += $processedTime->calculateSecond;
                                    
                                    if ($issue['issueType'] != 'Bug') {
                                        array_push($worklogArray, $item);
                                        $projectArray['logs'] = $worklogArray;
                                    } else {
                                        $bugCounter++;
                                        array_push($bugArray, $item);
                                        $projectArray['bugs'] = $bugArray;
                                    }
                                }
                            }
                        }
                    }
                }

                if ($writeAccess) {
                    $projectArray['numberOfBugs'] = $bugCounter;
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
            return stripos($value, $request->input('q')) !== false;
        });

        return $filtered;

    }
}
