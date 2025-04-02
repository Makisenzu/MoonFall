<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="">
    <title>Beastipedia Admin</title>
</head>
<body>
    <header class="bg-dark py-3">
        <div class="logo-small">
            <i class="fa-solid fa-bars" style="color:white" id="toggleSidebar"></i>
        </div>
        <div class="container d-flex justify-content-center align-items-center">
            <a href="" class="d-flex align-items-center text-white text-decoration-none">
                <img src="" alt="Beastipedia Logo" height="45" class="me-2">
                <h1 class="fs-4 fw-bold mb-0">Admin</h1>
            </a>
        </div>
    </header>
    
    <aside id="sidebar" class="bg-dark py-3">
        <nav>
            <strong>CONTROL PANEL</strong>
            <ul style="margin-top: 30px">
                <li>
                    <a class="btn sidebar-btn active" href="">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a class="btn sidebar-btn" href="">
                        <i class="fas fa-briefcase"></i> News
                    </a>
                </li>
                <li>
                    <a class="btn sidebar-btn" href="">
                        <i class="fa-solid fa-shield-halved"></i> Safe Zone
                    </a>
                </li>
                <li>
                    <a class="btn sidebar-btn" href="">
                        <i class="fas fa-dragon"></i> Volunteers
                    </a>
                </li>
                <li>
                    <a class="btn sidebar-btn" href="">
                        <i class="fas fa-chart-bar"></i> Info Com
                    </a>
                </li>
                <li>
                    <a class="btn sidebar-btn text-danger" href="">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </nav>
    </aside>
    
    <main>
        @yield('content')
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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src=""></script>
    
</body>
</html>