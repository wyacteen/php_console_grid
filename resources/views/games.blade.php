@extends('layouts.app')

@include('navbar')

@section('content')
<h1>{{$console->name}}</h1>
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