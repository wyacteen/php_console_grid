@extends('layouts.app')

@include('navbar')

@section('content')
<h1>Search Results</h1>
<hr>
<table class='game-table table table-bordered table-striped'>
    @if($games->isNotEmpty())
    <tbody>
        <tr>
            <th>Name</th>
            <th>Console</th>
        </tr>
        @foreach($games as $game)
            <tr>
                <td>
                    <a href="/games/{{$game->id}}">{{$game->name}}</a>
                </td>
                <td>
                    <a href="/console/{{$game->console->id}}">{{$game->console->name}}</a>
                </td>
            </tr>
        @endforeach
    </tbody>
    @else
        <div>No results found...</div>
    @endif
</table>
{{$games->links()}}
@endsection