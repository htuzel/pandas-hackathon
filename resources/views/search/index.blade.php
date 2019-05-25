@extends('layouts.app')

@section('content')

<div class="border-bottom mb-5">
    <div class="container">
        <div class="row justify-content-center align-items-center my-3">
            <div class="col-md-1">
                <a href="/"><img src="{{ asset('ese_logo.png') }}" class="logo-mini d-block mx-auto" /></a>
            </div>
            <div class="col-md-5">
                <form class="form-horizontal" id="searchForm" enctype="multipart/form-data" method="POST" action="{{ url('/search') }}">
                    {{ csrf_field() }}
                    <div class="input-group mb-3">
                        <input id="search" type="text" class="form-control{{ $errors->has('search_string') ? ' is-invalid' : '' }}" placeholder="Search for an estimation" name="search_string" value="{{ $searchQuery }}">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="container">
    @php
        $resultJSON = json_decode($resultArray);
    @endphp
    @if (count($resultJSON) == 0)
        <div class="jumbotron">
            <h1 class="display-4">Sorry, No result found <i class="far fa-sad-cry"></i></h1>
            <p class="lead">Please try again with a new keyword.</p>
            <hr class="my-4">
            <a class="btn btn-primary btn-lg" href="#" role="button"><i class="fa fa-angle-left"></i> Go Back</a>
        </div>
    @else
    <h2 class="mb-4">Search Results:</h2>
    <table class="table table-hover mb-5">
        <thead>
            <tr class="table-warning">
                <th scope="col">#</th>
                <th scope="col">Project Name</th>
                <th scope="col">Component Name</th>
                <th scope="col">Estimated</th>
                <th scope="col">Logged</th>
                <th scope="col">Bug Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($resultJSON as $result)
            <tr>
                @php
                    $estimateDay = floor($result->originalEstimate / 1440);
                    $estimateHour = floor(($result->originalEstimate - $estimateDay * 1440) / 60);
                    $estimateMinute = $result->originalEstimate - ($estimateDay * 1440) - ($estimateHour * 60);
                    $loggedDay = floor($result->realTime / 1440);
                    $loggedHour = floor(($result->realTime - $loggedDay * 1440) / 60);
                    $loggedMinute = $result->realTime - ($loggedDay * 1440) - ($loggedHour * 60);
                @endphp
                <th scope="row">{{ $loop->index+1 }}</th>
                <td>{{ $result->project }}</td>
                <td>{{ $result->componentName }}</td>
                <td>{{ $estimateDay."d ".$estimateHour."h ".$estimateMinute."m" }}</td>
                <td>{{ $loggedDay."d ".$loggedHour."h ".$loggedMinute."m" }}</td>
                <td>{{ $result->numberOfBugs }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        <form class="form-horizontal" id="searchForm" enctype="multipart/form-data" method="POST" action="{{ url('/estimation') }}">
            {{ csrf_field() }}
            <input type="hidden" name="search_string" value="{{ $searchQuery }}"/>
            <button type="submit" class="btn btn-success btn-lg"><i class="fa fa-check"></i> Recommend me an <b>Estimation</b></button>
        </form>
    </div>
    @endif
</div>

@endsection