@extends('layouts.app')

@include('navbar')

@section('content')

<div class="row">

    <div class="col-md-8">
        <h1>{{ $console->name }}</h1>
    </div>

    @if(Auth::user())

    <div class="col-md-4">
        <a role="button" class="btn btn-primary pull-right" href="/games/new">Add New Game</a>
    </div>

    @endif

</div>

<table class='game-table table table-bordered table-striped'>
    <tbody>
        <tr>
            <th>Name</th>
        </tr>
        @foreach($games as $game)
            <tr>
                <td>
                    <a href="/games/{{$game->id}}">{{$game->name}}</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
{{$games->links()}}
@endsection