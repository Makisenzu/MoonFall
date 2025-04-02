<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MoonFall Preparedness</title>
    @yield('styles')
</head>
<body class="d-flex flex-column min-vh-100 bg-light">
    <header class="shadow-sm">
        <nav class="navbar navbar-expand-lg navbar-light bg-white">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <i class="fas fa-moon me-2 text-dark"></i>
                    <span class="fw-bold">MoonFall Preparedness</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#"></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"></a>
                        </li>
                    </ul>
                    
                    <ul class="navbar-nav">
                        @auth
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-house"></i> Dashboard</a></li>
                                    <li><a class="dropdown-item" href="{{ route('userViewZone') }}"><i class="fas fa-shield-alt me-2"></i> Safe Zone</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-hands-helping me-2"></i> Apply for Volunteer</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-newspaper me-2"></i> News</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </a>
                                    </li>
                                    <form id="logout-form" action="" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </ul>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href=""><i class="fas fa-sign-in-alt me-1"></i> Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href=""><i class="fas fa-user-plus me-1"></i> Register</a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    {{-- @auth
    <div class="bg-dark text-white">
        <div class="container">
            <div class="row align-items-center py-2">
                <div class="col-md-6">
                    <h5 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i> User Control Panel</h5>
                </div>
                <div class="col-md-6">
                    <ul class="nav justify-content-md-end justify-content-center user-panel-nav">
                        <li class="nav-item">
                            <a href="#" class="nav-link text-white"><i class="fas fa-shield-alt me-1"></i> Safe Zone</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-white"><i class="fas fa-hands-helping me-1"></i> Apply for Volunteer</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-white"><i class="fas fa-newspaper me-1"></i> News</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endauth --}}
    
    <main class="flex-grow-1 py-5">
        <div class="container">
            @yield('content')
        </div>
    </main>
    
    <footer class="bg-dark text-white py-4 mt-auto">
        <div class="container">
            <hr class="my-4 border-secondary">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0 text-secondary small">&copy; 2025 MoonFall Preparedness. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
    @yield('scripts')
</body>
</html>