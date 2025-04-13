<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - CateringHub</title>
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">CateringHub</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    @endguest
                    @auth
                        @if(auth()->user()->role === 'merchant')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('merchant.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('merchant.food-items.index') }}">Food Items</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('merchant.orders.index') }}">Orders</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('merchant.invoices.index') }}">Invoices</a>
                            </li>
                        @elseif(auth()->user()->role === 'customer')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('customer.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('customer.merchants.index') }}">Merchants</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('customer.orders.index') }}">My Orders</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('customer.invoices.index') }}">Invoices</a>
                            </li>
                        @endif
                    @endauth
                </ul>
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                Register
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('register.customer') }}">Customer</a></li>
                                <li><a class="dropdown-item" href="{{ route('register.merchant') }}">Merchant</a></li>
                            </ul>
                        </li>
                    @else
                        @if(auth()->user()->role === 'customer')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('customer.cart.index') }}">
                                    <i class="fas fa-shopping-cart"></i> Cart
                                    <span class="badge bg-danger cart-count">0</span>
                                </a>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if(auth()->user()->role === 'merchant')
                                    <li><a class="dropdown-item" href="{{ route('merchant.profile.show') }}">Profile</a></li>
                                @else
                                    <li><a class="dropdown-item" href="{{ route('customer.profile.show') }}">Profile</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="bg-dark text-white mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>CateringHub</h5>
                    <p>The best catering marketplace platform for your needs.</p>
                </div>
                <div class="col-md-3">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="text-white">Home</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Contact Us</h5>
                    <address class="mb-0">
                        <p><i class="fas fa-envelope me-2"></i> info@cateringhub.com</p>
                        <p><i class="fas fa-phone me-2"></i> +62 (123) 456-7890</p>
                    </address>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p class="mb-0">© {{ date('Y') }} CateringHub. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>