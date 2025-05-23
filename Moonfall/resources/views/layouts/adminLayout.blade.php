<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.css' rel='stylesheet' />
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="icon" type="image/png" href="">
    <title>Crescent Admin</title>
</head>
<body class="d-flex flex-column min-vh-100">
    <header class="navbar navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container-fluid px-4">
            <button class="navbar-toggler border-0 p-0 me-3"type="button">
                <i class="fa-solid fa-moon"></i>
            </button>
            <a class="navbar-brand d-flex align-items-center" href="#">
                <span class="fw-bold">Crescent Admin</span>
            </a>
            <div class="ms-auto d-flex">
                <div class="dropdown ms-3">
                    <button class="btn btn-dark d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                        <div class="rounded-circle bg-light text-dark d-flex justify-content-center align-items-center me-2" style="width: 24px; height: 24px;">
                            <i class="fas fa-user-alt" style="font-size: 12px;"></i>
                        </div>
                        <span class="d-none d-md-inline me-1">Admin</span>
                        <i class="fas fa-chevron-down" style="font-size: 12px;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="{{ route('userLogout') }}"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
    
    <div class="container-fluid">
        <div class="row">
            <aside id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar">
                <div class="sidebar-sticky pt-3">
                    <div class="px-3 mb-4">
                        <h6 class="text-uppercase text-light-emphasis fw-bold small"></h6>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('adminIndex') }}">
                                <i class="fas fa-home"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('adminNewsCreate') }}">
                                @php use App\Models\Information; @endphp
                                <i class="fas fa-newspaper"></i>
                                <span>News</span>
                                <span class="badge bg-primary rounded-pill ms-auto">{{ Information::count()}}</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('adminZoneIndex') }}">
                                <i class="fa-solid fa-shield-halved"></i>
                                <span>Safe Zone</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('adminVolunteerIndex') }}">
                                <i class="fas fa-user-friends"></i>
                                <span>Volunteers</span>
                            </a>
                        </li>
                        
                        <li class="border-top my-3"></li>
                    </ul>
                </div>
            </aside>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 bg-light">
                @yield('content')
            </main>
        </div>
    </div>
    
    <footer class="bg-dark text-white py-3 mt-auto">
        <div class="container-fluid">
            <div class="row">
            </div>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.js'></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    {{-- <script src="{{ asset('js/admin.js') }}"></script> --}}
    <script src="{{ asset('js/zone.js') }}"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>