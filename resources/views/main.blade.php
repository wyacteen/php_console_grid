@extends('layouts.app')

@include('navbar')

@push('styles')
    <link href="/css/main.css" rel="stylesheet" type="text/css">
@endpush

@section('content')
    <div class='logo'>
    <h1>
        <span>alt-ConsoleGrid</span>
    </h1>
    </div>
    <form class="" action="/games" accept-charset="UTF-8">
        <div class='search-container'>
            <div class='input-group'>
                <input type="text" class="form-control" placeholder="Search for ..." name="query">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="submit">
                            <span class="glyphicon glyphicon-search"></span>
                        </button>
                    </span>
            </div>
        </div>
    </form>
@endsection