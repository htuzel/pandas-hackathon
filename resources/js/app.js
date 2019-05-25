/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');


$(document).ready(function() {
    var bloodhound = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: '/recommendations?q=%QUERY%',
            wildcard: '%QUERY%'
        },
    });
    
    $('#searchForm #search').typeahead({
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
            return '<div class="list-group-item">' + data + '</div></div>'
            }
        }
    });
});
