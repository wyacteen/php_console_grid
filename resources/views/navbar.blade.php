@section('navbar')
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/">alt-ConsoleGrid</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <form class="navbar-form navbar-left" action="/games" accept-charset="UTF-8">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search" name="query">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
      </form>
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a
            href="#"
            class="dropdown-toggle"
            data-toggle="dropdown"
            role="button"
            aria-haspopup="true"
            aria-expanded="false">
                Consoles
                <span class="caret"></span>
          </a>
          <ul class="dropdown-menu">
            @foreach ($consoles as $console)
                <li><a href="/console/{{$console->id}}">{{$console->name}} - {{$console->shortname}}</a></li>
            @endforeach
          </ul>
        </li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        @if(Auth::user())
          <li><p class="nav navbar-text">{{Auth::user()->email}}</p></li>
          <li><a href="{{route('logout')}}">Logout</a></li>
        @else
          <li><a href="/login">Login</a></li>
          <li><a href="/register">Register</a></li>
        @endif
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
@endsection