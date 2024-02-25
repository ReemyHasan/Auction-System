<header id="header" class="fixed-top">
    <div class="container d-flex align-items-center justify-content-between">

        <h1 class="logo"><a href="{{url('/')}}">Auction System</a></h1>
        <nav id="navbar" class="navbar">
            <ul>
                <li><a class="nav-link scrollto {{ Route::is('home') ? 'active' : '' }}" href="{{route('home')}}">Home</a></li>
                @if (!Auth::check())
                    <li><a class="nav-link scrollto " href="{{ route('login') }}">login</a></li>
                    <li><a class="nav-link scrollto " href="{{ route('register') }}">Register</a></li>
                @else
                    <li><a class="nav-link scrollto {{ Route::is('categories.index') ? 'active' : '' }}" href="{{ route('categories.index') }}">categories</a></li>
                    <li><a class="nav-link scrollto {{ Route::is('products.index') ? 'active' : '' }}" href="{{ route('products.index') }}">products</a></li>
                    <li><a class="nav-link scrollto {{ Route::is('auctions.index') ? 'active' : '' }}" href="{{ route('auctions.index') }}">auctions</a></li>
                    @can('create', App\Models\CustomerBid::class)
                    <li>
                        <a class="nav-link scrollto {{ Route::is('customer.auctions') ? 'active' : '' }}" href="{{ route('customer.auctions',Auth::user()) }}">
                            my auctions</a>
                    </li>

                    @endcan
                    <li><a class="nav-link scrollto" href="{{ route('logout') }}">logout</a></li>

                @endif

            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav>

    </div>
</header>
