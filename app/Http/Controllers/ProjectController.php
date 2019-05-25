<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function projects() {
        $projects = Helper::projects();
        return $projects;
    }

    public function users() {
        $users = Helper::users();
        return $users;
    }

    public function componentNames () {
        $componentNames = Helper::componentNames();
        return $componentNames;    
    }

    public function issues () {
        $issues = Helper::issues();
        return $issues;    
    }

}
