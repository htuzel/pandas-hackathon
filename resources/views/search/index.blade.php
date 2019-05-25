@extends('layouts.app')

@section('content')

<div class="border-bottom mb-5">
    <div class="container">
        <div class="row justify-content-center align-items-center my-3">
            <div class="col-md-1">
                <img src="{{ asset('ese_logo.png') }}" class="logo-mini d-block mx-auto" />
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
    <h2 class="mb-4">Search Results:</h2>
    <table class="table table-hover mb-5">
        <thead>
            <tr class="table-warning">
                <th scope="col">#</th>
                <th scope="col">Project Name</th>
                <th scope="col">Estimated</th>
                <th scope="col">Logged</th>
                <th scope="col">Bug Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach (json_decode($resultArray) as $result)
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
                <td>{{ $estimateDay."d ".$estimateHour."h ".$estimateMinute."m" }}</td>
                <td>{{ $loggedDay."d ".$loggedHour."h ".$loggedMinute."m" }}</td>
                <td>{{ $result->numberOfBugs }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-center">
        <a href="#" class="btn btn-success btn-lg"><i class="fa fa-check"></i> Recommend me an <b>Estimation</b></a>
    </div>
</div>

@endsection