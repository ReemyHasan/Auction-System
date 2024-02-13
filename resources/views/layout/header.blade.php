<header id="header" class="fixed-top">
    <div class="container d-flex align-items-center justify-content-between">

        <h1 class="logo"><a href="{{url('/')}}">Auction System</a></h1>
        <nav id="navbar" class="navbar">
            <ul>
                <li><a class="nav-link scrollto active" href="#hero">Home</a></li>
                <li><a class="nav-link scrollto" href="#about">About</a></li>
                @if (!Auth::check())
                    <li><a class="nav-link scrollto" href="{{ route('login') }}">login</a></li>
                    <li><a class="nav-link scrollto o" href="{{ route('register') }}">Register</a></li>
                @else
                    <li><a class="nav-link scrollto" href="{{ route('logout') }}">logout</a></li>
                    <li><a class="nav-link scrollto" href="{{ route('categories.index') }}">categories</a></li>
                @endif

            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav>

    </div>
</header>
