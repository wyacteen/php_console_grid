@extends('layouts.app')

@include('navbar')

@section('content')

<div class="row">

    <div class="col-md-8">
        <h1>Search Results</h1>
    </div>

    @if(Auth::user())

    <div class="col-md-4">
        <a role="button" class="btn btn-primary pull-right" href="/games/new">Add New Game</a>
    </div>

    @endif

</div>
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