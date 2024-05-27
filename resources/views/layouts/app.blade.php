<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.0.0-beta3/css/all.min.css">

    <!-- Scripts -->
    <script src="https://kit.fontawesome.com/9322fd0ab2.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="{{asset('js/custom.js')}}"></script>
    <script src="{{asset('js/fontawesome6-icon-picker.js')}}"></script>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm header">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li><a href="{{ route('index') }}">ホーム</a></li>
                            <li><a href="/fav">お気に入り</a></li>
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('ログイン') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('会員登録') }}</a>
                                </li>
                            @endif
                        @else

                            <li>
                                <a href="{{ url('items/add') }}" class="btn btn-default">新規登録</a>
                            </li>

                            <li>
                                <a href="{{ url('items/drafts') }}" class="btn btn-default">下書き一覧</a>
                            </li>

                            <li>
                                <a href="{{ url('items/editTag') }}" class="btn btn-default">タグ編集</a>
                            </li>

                            <li class="nav-item" data-bs-toggle="modal" data-bs-target="#js-modal-today">きょうのごはん</li>

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <!-- <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown"> -->
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                <!-- </div> -->
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        @auth
        <!-- きょうのごはんアニメーション -->
        <div class="modal fade" id="js-modal-today" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    @if(isset($today_side[0]))
                    <a class="today-side side1" href="/items/show/{{ $today_side[0]->id }}" target="_blank" rel="noopener noreferrer">
                        <img src="{{ $today_side[0]->image }}" alt="">
                        <p class="today-label">{{ $today_side[0]->title }}</p>
                    </a>
                    @endif
                    @if(isset($today_side[1]))
                    <a class="today-side side2" href="/items/show/{{ $today_side[1]->id }}" target="_blank" rel="noopener noreferrer">
                        <img src="{{ $today_side[1]->image }}" alt="">
                        <p class="today-label">{{ $today_side[1]->title }}</p>
                    </a>
                    @endif
                    @if(isset($today_soup))
                    <a class="today-side side3" href="/items/show/{{ $today_soup->id }}" target="_blank" rel="noopener noreferrer">
                        <img src="{{ $today_soup->image }}" alt="">
                        <p class="today-label">{{ $today_soup->title }}</p>
                    </a>
                    @endif
                    @if(isset($today_main))
                    <a class="today-main" href="/items/show/{{ $today_main->id }}" target="_blank" rel="noopener noreferrer">
                        <img src="{{ $today_main->image }}" alt="">
                        <p class="today-label">{{ $today_main->title }}</p>
                    </a>
                    @endif
                    <img class="nabe-close" src="{{ asset('img/nabe-close.png') }}">
                    <img class="nabe-open" src="{{ asset('img/nabe-open.png') }}">
                </div>
            </div>
        </div>
        @endauth
        
        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <script>
        //タグアイコンをhtmlテキストとして表示
        view_icon = document.getElementsByClassName('view_icon');
        for(i=0; i<view_icon.length ; i++){
            icon_text = view_icon[i].innerText;
            view_icon[i].innerHTML = icon_text;
        }
    </script>
</body>
</html>
