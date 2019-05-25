@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" id="searchForm" role="form" enctype="multipart/form-data" method="POST" action="{{ url('/search') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('search_string') ? ' has-error' : '' }}">
                            <div class="col-md-6">
                                <input id="search_string" type="text" class="form-control" name="search_string" value="{{ old('search_string') }}">

                                @if ($errors->has('search_string'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('search_string') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection