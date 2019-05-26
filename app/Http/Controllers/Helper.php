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

    public static function convertJiraTime($jiraTime) {
        $arrayTime = str_split($jiraTime);
        $convertedTime = null;
        $calculateSecond = 0;
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
        if (count($calculateSecondArray) == 2) {
            $calculateSecond = (int)$calculateSecondArray[0] * 60 + $calculateSecondArray[1];
        }

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

    public static function fixSearchString($string) {
        $components = Helper::componentNames();
        $result = [];

        foreach ($components as $component) {
            if (!is_array($string)) {
                if (Helper::countString($string)) {
                    if (preg_match("/(?i)({$string})/", $component)) {
                        array_push($result, $component);
                    }
                }
            } else {
                foreach ($string as $item) {
                    if (Helper::countString($item)) {
                        if (preg_match("/(?i)({$item})/", $component)) {
                            array_push($result, $component);
                        }
                    }
                }
            }
        }

        return $result;
    }

    public static function countString($string)
    {
        if (strlen($string) > 2) {
            return true;
        }
        return false;
    }

    public static function getSearchResults($searchQuery) {
        $searchEngines = Helper::fixSearchString($searchQuery);
        $results = self::db()->from('projects')->get();
        $resultArray = []; // Stores trimmed results

        if (count($searchEngines) == 0) {
            return $resultArray;
        }

        foreach ($searchEngines as $searchEngine) {
            foreach ($results as $result){
                $writeAccess = false; // Ignore empty worklogs
                $issues = $result['issues'];
                $projectArray = [];
                $worklogArray = [];
                $bugArray = [];
                $projectArray['realTime'] = 0;
                $projectArray['originalEstimate'] = 0;
                $totalSpendTime = 0;
                $bugCounter = 0;

                if ($issues != null) {

                    foreach ($issues as $issue) {
                        $issueComponenets = $issue['components'];

                        foreach ($issueComponenets as $issueComponenet) {
                            if (!strcasecmp($issueComponenet, $searchEngine)) { // Ignoring case sensitive search
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
                        $projectArray['componentName'] = $searchEngine;
                        $projectArray['departmenents'] = Helper::calculateDepartment($projectArray['logs']);
                        array_push($resultArray, $projectArray);
                    }
                }
            }
        }

        return $resultArray;
    }

    public static function calculateDepartment($workLogs) {
        $workLogArray['BD'] = 0;
        $workLogArray['FD'] = 0;
        $workLogArray['SA'] = 0;
        $workLogArray['QA'] = 0;
        $workLogArray['PM'] = 0;
        $workLogArray['BA'] = 0;

        foreach ($workLogs as $workLog) {
            $userDepartman = Helper::userRole($workLog['author']);
            $spendTime = 0;

            switch ($userDepartman) {
                case 'BD':
                    $workLogArray['BD'] += Helper::convertJiraTime($workLog['timeSpent'])->getData()->calculateSecond;
                    break;
                case 'FD':
                    $workLogArray['FD'] += Helper::convertJiraTime($workLog['timeSpent'])->getData()->calculateSecond;
                    break;
                case 'SA':
                    $workLogArray['SA'] += Helper::convertJiraTime($workLog['timeSpent'])->getData()->calculateSecond;;
                    break;
                case 'QA':
                    $workLogArray['QA'] += Helper::convertJiraTime($workLog['timeSpent'])->getData()->calculateSecond;
                    break;
                case 'PM':
                    $workLogArray['PM'] += Helper::convertJiraTime($workLog['timeSpent'])->getData()->calculateSecond;
                    break;
                case 'BA':
                    $workLogArray['BA'] += Helper::convertJiraTime($workLog['timeSpent'])->getData()->calculateSecond;
                    break;
            }
        }

        return $workLogArray;
    }

}
