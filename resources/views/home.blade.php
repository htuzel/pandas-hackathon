@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center">
    <div class="mt-5">
        <a href="/"><img src="{{ asset('ese_logo.png') }}" class="logo mb-3 d-block mx-auto" /></a>
        <h1 class="site-name">Estimation Search Engine</h1>
        <form class="form-horizontal" id="searchForm" enctype="multipart/form-data" method="POST" action="{{ url('/search') }}">
            {{ csrf_field() }}
            <div class="input-group mb-3">
                <input id="search" type="text" class="form-control{{ $errors->has('search_string') ? ' is-invalid' : '' }}" placeholder="Search for an estimation" name="search_string" value="{{ old('search_string') }}">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit"><i class="fa fa-search"></i></button>
                </div>
            </div>
            @if ($errors->has('search_string'))
                <div class="invalid-feedback">{{ $errors->first('search_string') }}</div>
            @endif
        </form>
    </div>
</div>

@endsection