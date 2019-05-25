@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row mt-5">
        <div class="offset-4 col-8 mt-5">
            <img src="{{ asset('ese_logo.png') }}" class="logo">  </img>
        </div>
        <div class="offset-3 col-8">
            <form class="form-horizontal mt-5" id="searchForm" role="form" enctype="multipart/form-data" method="POST" action="{{ url('/search') }}">
                {{ csrf_field() }}
                <div class="form-group{{ $errors->has('search_string') ? ' has-error' : '' }}">
                    <div class="col-md-6">
                        <input id="search_string" type="text" class="form-control round" name="search_string" value="{{ old('search_string') }}">
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
@endsection