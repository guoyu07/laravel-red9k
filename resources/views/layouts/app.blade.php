<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>red9k</title>

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

    <!-- Styles -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>
</head>
<body id="app-layout">
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/posts') }}">
                    red9k
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
					<li><a href="{{ url('/posts') }}">All</a></li>
                    <li><a href="{{ route('category', ['category' => 'art']) }}"><i class="fa fa-picture-o"></i> Art</a></li>
                    <li><a href="{{ route('category', ['category' => 'music']) }}"><i class="fa fa-music"></i> Music</a></li>
                    <li><a href="{{ route('category', ['category' => 'writing']) }}"><i class="fa fa-pencil-square-o"></i> Writing</a></li>
                    <li><a href="{{ route('category', ['category' => 'video']) }}"><i class="fa fa-video-camera"></i> Video</a></li>
					<li><a href="{{ url('/post/create') }}">Create A Post</a></li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Sign In</a></li>
                        <li><a href="{{ url('/register') }}">Sign Up</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
								<li><a href="{{ route('user', ['user' => Auth::user()->id]) }}">My Posts</a></li>
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Sign Out</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
            <div class="row">
                <div class="pull-right">
                    <button class="btn btn-sm" type="button" id="search"><i class="fa fa-search"></i></button>
                </div>
                <div class="pull-right">
                    <input type="text" class="form-control input-sm" id="term" placeholder="Search">
                </div>
            </div>
        </div>
    </nav>
	
	<!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}

    <script>
        $("#search").click(function() {
            window.location.assign("/red9k/posts/search?q=" + $("#term").val());
        });
    </script>
    @yield('content')
</body>
</html>
