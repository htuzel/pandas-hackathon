@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center">
    <div class="mt-5">
        <img src="{{ asset('ese_logo.png') }}" class="logo mb-3 d-block mx-auto" />
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

<script src="https://twitter.github.io/typeahead.js/releases/latest/typeahead.bundle.js"></script>


<script>
        $(document).ready(function() {
            var bloodhound = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.whitespace,
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: {
                    url: '/recommendations?q=%QUERY%',
                    wildcard: '%QUERY%'
                },
            });
            
            $('#search').typeahead({
                hint: true,
                highlight: true,
                minLength: 1
            }, {
                name: 'users',
                source: bloodhound,
                display: function(data) {
                    return data  //Input value to be set when you select a suggestion. 
                },
                templates: {
                    empty: [
                        '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
                    ],
                    header: [
                        '<div class="list-group search-results-dropdown">'
                    ],
                    suggestion: function(data) {
                    return '<div style="font-weight:normal; margin-top:-10px ! important;" class="list-group-item">' + data + '</div></div>'
                    }
                }
            });
        });
    </script>

@endsection