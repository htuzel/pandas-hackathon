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
   
    <h4 class="mb-4">The Estimation Of Esè : <span id="estimation"></span></h4>
    <div class="row">
        <div class="col-12">
            <div class="card">
              <div class="card-body">
                    <div/>
                        <canvas id="Chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card my-5 p-4">
        <div class="row">
            <div class="col-9">
                <div class="card">
                  <div class="card-body">
                    <div>
                        <canvas id="Pie"></canvas>
                    </div>
                  </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                  <div class="card-body">
                    @foreach($resultJSON as $indexkey => $result)
                        <div class="form-check">
                          <label class="form-check-label">
                            <input type="radio" class="form-check-input"  value="{{$indexkey}}" name="project"  id="{{$indexkey}}" @if($indexkey ==0) checked @endif >{{$result->project}}
                          </label>
                        </div>
                    @endforeach
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var color = Chart.helpers.color;

var labels = [];
var estimatedTimes = [];
var loggedTimes = [];
var BD = [];
var FD = [];
var SA = [];
var QA = [];
var PM = [];
var BA = [];


@foreach($resultJSON as $result)
    labels.push('{{$result->project}}');
    estimatedTimes.push('{{$result->originalEstimate}}');
    loggedTimes.push('{{$result->realTime}}');
    BD.push('{{$result->departmenents->BD}}');
    FD.push('{{$result->departmenents->FD}}');
    SA.push('{{$result->departmenents->SA}}');
    QA.push('{{$result->departmenents->QA}}');
    PM.push('{{$result->departmenents->PM}}');
    BA.push('{{$result->departmenents->BA}}');
@endforeach

var project = 0;

//User Role based Graph
var ctpie = document.getElementById('Pie');
myDoughnut = new Chart(ctpie, {
	type: 'doughnut',
	data: {
		datasets: [{
			data: [
				BD[project],
				FD[project],
				SA[project],
				QA[project],
				PM[project],
                BA[project],
			],
			backgroundColor: [
				color('red').alpha(0.5).rgbString(),
				color('orange').alpha(0.5).rgbString(),
				color('green').alpha(0.5).rgbString(),
				color('purple').alpha(0.5).rgbString(),
				color('gray').alpha(0.5).rgbString(),
				color('yellow').alpha(0.5).rgbString()
			],
			label: 'User role efforts'
		}],
		labels: [
			'BD',
			'FD',
			'SA',
			'QA',
			'PM',
            'BA'
		]
	},
	options: {
		responsive: true,
		legend: {
			position: 'top',
		},
		title: {
			display: true,
			text: 'User role efforts'
		},
		animation: {
			animateScale: true,
			animateRotate: true
		}
	}
});

$(document).ready(function(){
    $('input[type=radio]').click(function(){
        project = this.value;
        console.log(project);
        myDoughnut.data.datasets[0].data = [
	    	BD[project],
	    	FD[project],
	    	SA[project],
	    	QA[project],
	    	PM[project],
            BA[project],
	    ];
	    myDoughnut.update();
    });
});

//Project based graph
var ctx = document.getElementById('Chart');
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
        animation: {
			animateScale: true,
			animateRotate: true
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


//KNN algorithm
var outputs = [];

for (var i = 0; i < estimatedTimes.length; i++) {
    outputs.push([estimatedTimes[i], loggedTimes[i]]);
}

var estimationResult = knn(outputs, 2);
$('#estimation').html(estimationResult);

function distiance(pointA, pointB) {
    return _.chain(pointA)
            .zip(pointB)
            .map(([a, b]) => (a - b) ** 2)
            .sum()
            .value() ** 0.5;
}

function knn(data, k) {
    return _.chain(data)
            .map(row => [distiance(outputs[0], outputs[1]), row[1]])
            .sortBy(row => row[0])
            .slice(0, k)
            .countBy(row => row[1])
            .toPairs()
            .sortBy(row => row[1])
            .last()
            .first()
            .parseInt()
            .value();
}
</script>

@endsection