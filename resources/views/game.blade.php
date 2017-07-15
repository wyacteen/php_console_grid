@extends('layouts.app')

@include('navbar')

@push('styles')
    <link href="/css/game.css" rel="stylesheet" type="text/css">
@endpush

@section('content')
    <div class="page-header">
        <h1>{{$game->name}} - {{$console->shortname}}</h1>
    </div>
    <section>
        <div class="page-header">
            <h2>Top Rated</h2>
        </div>
        @if(isset($topRated))
        <div class="jumbotron">
            <div class="picture-container top-picture">
                <ul class="vote pull-left">
                    <li class="vote-arrow" data-pictureID="{{$topRated['picture']->id}}" data-voteAction="up">
                        @if($topRated['userUpVoted'])
                            <span class="glyphicon glyphicon-arrow-up"></span>
                        @else
                            <a href='/pictures/vote/{{$topRated['picture']->id}}/up'>
                                <span class="glyphicon glyphicon-arrow-up"></span>
                            </a>
                        @endif
                    </li>
                    <li class="spacer"></li>
                    <li class="vote-count">{{$topRated['picture']->netVotes}}</li>
                    <li class="spacer"></li>
                    <li class="vote-arrow not-voted">
                    @if($topRated['userDownVoted'])
                        <span class="glyphicon glyphicon-arrow-down"></span>
                    @else
                        <a href='/pictures/vote/{{$topRated['picture']->id}}/down'>
                            <span class="glyphicon glyphicon-arrow-down"></span>
                        </a>
                    @endif
                    </li>
                </ul>
                <img alt="{{$topRated['picture']->image_name}}" src="{{$imageRoot}}/{{$topRated['picture']->image_name}}">
            </div>
        </div>
        @endif
    </section>
     <section>
        <div class="page-header">
            <h2>Other Pictures</h2>
        </div>
        @if(!empty($pictures))
            @foreach ($pictures as $picture)
                <div class="jumbotron">
                    <div class="picture-container other-picture">
                        <ul class="vote pull-left">
                                <li class="vote-arrow not-voted">
                                @if($picture['userUpVoted'])
                                    <span class="glyphicon glyphicon-arrow-up"></span>
                                @else
                                    <a href='/pictures/vote/{{$picture['picture']->id}}/up'>
                                        <span class="glyphicon glyphicon-arrow-up"></span>
                                    </a>
                                @endif
                                </li>
                            <li class="spacer"></li>
                            <li class="vote-count">{{$picture['picture']->netVotes}}</li>
                            <li class="spacer"></li>
                            <li class="vote-arrow not-voted">
                            @if($picture['userDownVoted'])
                                <span class="glyphicon glyphicon-arrow-down"></span>
                            @else
                                <a href='/pictures/vote/{{$picture['picture']->id}}/down'>
                                    <span class="glyphicon glyphicon-arrow-down"></span>
                                </a>
                            @endif
                            </li>
                        </ul>
                        <img src='{{$imageRoot}}/{{$picture['picture']->image_name}}' alt='{{$picture['picture']->image_name}}'>
                    </div>
                </div>
            @endforeach
        @endif

        @if(!$hasPictures)
        No pictures added yet.
        @endif
    </section> 
@endsection