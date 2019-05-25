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
   
    <h4 class="mb-4">The Estimation Of Esè :</h4>
    <div class="row">
        <div class="offset-1 col-10 p-5"/>
            <canvas id="Chart"></canvas>
        </div>
    </div>
   
</div>

<script>
var labels = [];
var estimatedTimes = [];
var loggedTimes = [];

@foreach($resultJSON as $result)
    labels.push('{{$result->project}}');
    estimatedTimes.push('{{$result->originalEstimate}}');
    loggedTimes.push('{{$result->realTime}}');
@endforeach

var ctx = document.getElementById('Chart');
var color = Chart.helpers.color;
var mixedChart = new Chart(ctx, {
    type: 'bar',
    data: {
        datasets: [{
            backgroundColor: color('#C52782').alpha(0.5).rgbString(),
			borderColor: '#C52782',
			borderWidth: 1,
            label: 'Estimation Time',
            data: estimatedTimes
        }, {
            backgroundColor: color('blue').alpha(0.7).rgbString(),
			borderColor: color('blue').alpha(0.7).rgbString(),
            fill: false,
            label: 'Logged Time',
            data: loggedTimes,
            type: 'line'
        }],
        labels: labels
    },
    options: {
				responsive: true,
				title: {
					display: true,
					text: 'Estimation vs. Logged Time'
				},
				tooltips: {
					mode: 'index',
					intersect: false,
				},
				hover: {
					mode: 'nearest',
					intersect: true
				},
				scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Project'
						}
					}],
					yAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Minutes'
						}
					}]
				}
			}
});
</script>

@endsection