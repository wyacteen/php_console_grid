@extends('layouts.app')

@include('navbar')

@section('content')
    <div class='logo'> this is where the logo goes</div>
    <div class='search-container'>
        <div class='input-group'>
            <input type="text" class="form-control" placeholder="Search for...">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                </span>
        </div>
    </div>
@endsection