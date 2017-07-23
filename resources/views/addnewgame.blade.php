@extends('layouts.app')

@include('navbar')

@section('content')
<div>
    <h1>Add a New Game</h1>
    <hr>
    <form accept-charset="UTF-8" action="/games/new" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group form-group-lg">
            <label for="console_list">Select a console</label>
            <select id="console_id" class="form-control" name="console_id">
                @foreach ($consoles as $console)
                    <option value="{{$console->id}}">{{$console->name}}</option>
                @endforeach    
            </select>
        </div> 
        <div class="form-group form-group-lg">
            <label for="game_name">Name</label>
            <input id="game_name" class="form-control" name="game_name" size=30 type=text>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <button type="submit" class="btn btn-primary">Add Game</button>
    </form>
</div>
@endsection