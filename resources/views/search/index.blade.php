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
            <a class="btn btn-primary btn-lg" href="{{ route('home') }}" role="button"><i class="fa fa-angle-left"></i> Go Back</a>
        </div>
    @else
    <div class="row">
        <div class="col-6"><h2 class="mb-4">Search Results:</h2></div>
        <!-- 
        <div class="col-6 text-right">
            Filter results by Component: 
            <select class="selectpicker" multiple data-live-search="true">
                @foreach ($resultJSON as $result)
                    <option value="{{ $result->componentName }}">{{ $result->componentName }}</option>
                @endforeach
            </select>
        </div>-->
    </div>
    <form class="form-horizontal" id="estimationForm" enctype="multipart/form-data" method="POST" action="{{ url('/estimation') }}">
        {{ csrf_field() }}
        <table class="table table-hover mb-5">
            <thead>
                <tr class="table-warning">
                    <th scope="col"><input type="checkbox" id="select-all"></th>
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
                    <th scope="row"><input type="checkbox" name="search_string_array[]" value="{{ $result->project }}"></th>
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
        <div class="text-right">
            <input type="hidden" name="search_string" value="{{ $searchQuery }}"/>
            <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-project-diagram"></i> Recommend me an <b>Estimation</b> <i class="fa fa-angle-right"></i></button>
        </div>
    </form>
    @endif
</div>
<script>
function myFunction() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("myTable");
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0];
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

</script>
@endsection