@extends('layouts.app')

@include('navbar')

@section('content')
<h1>{{$console->name}}</h1>
<div class="list-group">
    @foreach ($games as $game)
        <a href="/games/{{$game->id}}" class="list-group-item"> {{$game->name}} </a>
    @endforeach
</div>
{{$games->links()}}
@endsection