<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetCare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-white shadow-sm px-4">
        <a class="navbar-brand fw-bold text-warning" href="/">🐾 PetCare</a>
        <div class="ms-auto d-flex align-items-center gap-3">
            @auth
             <a href="/perfil" class="text-muted text-decoration-none">Hola, {{ auth()->user()->name }}</a>
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="btn btn-warning text-white">Cerrar sesión</button>
                </form>
            @else
                <a href="/login" class="btn btn-warning text-white">Iniciar sesión</a>
            @endauth
        </div>
    </nav>

    <!-- Contenido -->
    <main class="container py-5">
        @yield('content')
    </main>

   <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5 style="color: var(--color-primary);">🐾 PetCare</h5>
                    <p class="text-white-50">El cuidado que tu mascota merece.</p>
                </div>
                <div class="col-md-4">
                    <h6 class="fw-bold text-white">Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="/" class="text-white-50 text-decoration-none">Inicio</a></li>
                        <li><a href="/membresias" class="text-white-50 text-decoration-none">Membresías</a></li>
                        <li><a href="/servicios" class="text-white-50 text-decoration-none">Servicios</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6 class="fw-bold text-white">Contacto</h6>
                    <ul class="list-unstyled text-white-50">
                        <li>📧 petcaree2026@gmail.com</li>
                        <li>📱 Disponible en Android</li>
                    </ul>
                </div>
            </div>
            <div class="text-center text-white-50 mt-4">
                © 2026 PetCare. Todos los derechos reservados.
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>