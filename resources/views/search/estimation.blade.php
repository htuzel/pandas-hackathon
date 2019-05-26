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

    <div class="card mb-5">
        <div class="card-header"><h3>The Estimation Of Esè : </h3></div>
        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead>
                    <tr class="table-warning">
                        <th scope="row">TOTAL</th>
                        <th scope="row">Solution Architecture</th>
                        <th scope="row">Backend Development</th>
                        <th scope="row">Frontend Development</th>
                        <th scope="row">Project Management</th>
                        <th scope="row">Business Analyse</th>
                        <th scope="row">Quality Assurance</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span id="total"></span></td>
                        <td><span id="sa"></span></td>
                        <td><span id="bd"></span></td>
                        <td><span id="fd"></span></td>
                        <td><span id="pm"></span></td>
                        <td><span id="ba"></span></td>
                        <td><span id="qa"></span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="bar-chart" data-toggle="tab" href="#bar-tab"><i class="far fa-chart-bar"></i> Bar Chart</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pie-chart" data-toggle="tab" href="#pie-tab"><i class="fas fa-chart-pie"></i> Pie Chart</a>
        </li>
    </ul>
    <div class="tab-content mb-5">
        <div class="tab-pane fade show active" id="bar-tab">
            <div class="p-4">
                <canvas id="Chart"></canvas>
            </div>
        </div>
        <div class="tab-pane fade" id="pie-tab">
            <div class="row p-4">
                <div class="col-9"><canvas id="Pie"></canvas></div>
                <div class="col-3">
                    <div class="pt-4">
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
var timeBD = 0;
var timeFD = 0;
var timeSA = 0;
var timeQA = 0;
var timePM = 0;
var timeBA = 0;
var timeTotal = 0;
var percBD = 0;
var percFD = 0;
var percSA = 0;
var percQA = 0;
var percPM = 0;
var percBA = 0;


@foreach($resultJSON as $result)
    @if($searchQueryArray != null)
        @foreach($searchQueryArray as $src)
            @if($src == $result->project)
                labels.push('{{$result->project}}');
                estimatedTimes.push('{{$result->originalEstimate}}');
                loggedTimes.push('{{$result->realTime}}');
                BD.push('{{$result->departmenents->BD}}');
                FD.push('{{$result->departmenents->FD}}');
                SA.push('{{$result->departmenents->SA}}');
                QA.push('{{$result->departmenents->QA}}');
                PM.push('{{$result->departmenents->PM}}');
                BA.push('{{$result->departmenents->BA}}');
            @endif
        @endforeach
    @else
        labels.push('{{$result->project}}');
        estimatedTimes.push('{{$result->originalEstimate}}');
        loggedTimes.push('{{$result->realTime}}');
        BD.push('{{$result->departmenents->BD}}');
        FD.push('{{$result->departmenents->FD}}');
        SA.push('{{$result->departmenents->SA}}');
        QA.push('{{$result->departmenents->QA}}');
        PM.push('{{$result->departmenents->PM}}');
        BA.push('{{$result->departmenents->BA}}');
    @endif
@endforeach


for (var i = 0; i<BD.length; i++ ) {
    timeBD += parseInt(BD[i])
    timeFD += parseInt(FD[i])
    timeSA += parseInt(SA[i])
    timeQA += parseInt(QA[i])
    timePM += parseInt(PM[i])
    timeBA += parseInt(BA[i])
}
timeTotal = timeBD + timeFD + timeSA + timeQA + timePM + timeBA;


percBD = timeBD  * 100 / timeTotal;
percFD = timeFD  * 100 / timeTotal;
percSA = timeSA  * 100 / timeTotal;
percQA = timeQA  * 100 / timeTotal;
percPM = timePM  * 100 / timeTotal;
percBA = timeBA  * 100 / timeTotal;

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
TotalMinute = estimationResult;
Day = Math.floor(TotalMinute / 1440);
Hour = Math.floor((TotalMinute - Day * 1440) / 60);
Minute = Math.ceil(TotalMinute - (Day * 1440) - (Hour * 60));
$('#total').html( Day+"d " + Hour + "h " + Minute + "m");

bdTotalMinute = percBD * estimationResult / 100;
bdDay = Math.floor(bdTotalMinute / 1440);
bdHour = Math.floor((bdTotalMinute - bdDay * 1440) / 60);
bdMinute = Math.ceil(bdTotalMinute - (bdDay * 1440) - (bdHour * 60));
$('#bd').html( bdDay + "d " + bdHour + "h " + bdMinute+"m");

fdTotalMinute = percFD * estimationResult / 100;
fdDay = Math.floor(fdTotalMinute / 1440);
fdHour = Math.floor((fdTotalMinute - fdDay * 1440) / 60);
fdMinute = Math.ceil(fdTotalMinute - (fdDay * 1440) - (fdHour * 60));
$('#fd').html( fdDay + "d " + fdHour + "h " + fdMinute+"m");

saTotalMinute = percSA * estimationResult / 100;
saDay = Math.floor(saTotalMinute / 1440);
saHour = Math.floor((saTotalMinute - saDay * 1440) / 60);
saMinute = Math.ceil(saTotalMinute - (saDay * 1440) - (saHour * 60));
$('#sa').html( saDay + "d " + saHour + "h " + saMinute+"m");

qaTotalMinute = percQA * estimationResult / 100;
qaDay = Math.floor(qaTotalMinute / 1440);
qaHour = Math.floor((qaTotalMinute - qaDay * 1440) / 60);
qaMinute = Math.ceil(qaTotalMinute - (qaDay * 1440) - (qaHour * 60));
$('#qa').html( qaDay + "d " + qaHour + "h " + qaMinute+"m");

baTotalMinute = percBA * estimationResult / 100;
baDay = Math.floor(baTotalMinute / 1440);
baHour = Math.floor((baTotalMinute - baDay * 1440) / 60);
baMinute = Math.ceil(baTotalMinute - (baDay * 1440) - (baHour * 60));
$('#ba').html( baDay + "d " + baHour + "h " + baMinute+"m");

pmTotalMinute = percPM * estimationResult / 100;
pmDay = Math.floor(pmTotalMinute / 1440);
pmHour = Math.floor((pmTotalMinute - pmDay * 1440) / 60);
pmMinute = Math.ceil(pmTotalMinute - (pmDay * 1440) - (pmHour * 60));
$('#pm').html( pmDay + "d " + pmHour + "h " + pmMinute+"m");

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