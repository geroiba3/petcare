<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white">

    <!-- Navbar -->
    <nav class="flex justify-between items-center px-8 py-4 shadow">
        <h1 class="text-2xl font-bold text-orange-500">🐾 PetCare</h1>
        <div class="flex gap-4">
            <a href="/membresias" class="text-gray-600 hover:text-orange-500">Membresías</a>
            <a href="/servicios" class="text-gray-600 hover:text-orange-500">Servicios</a>
            <a href="/login" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600">Iniciar sesión</a>
        </div>
    </nav>

    <!-- Hero -->
    <section class="flex flex-col items-center justify-center text-center py-24 px-8">
        <h2 class="text-5xl font-bold text-gray-800 mb-4">El cuidado que tu mascota merece</h2>
        <p class="text-gray-500 text-xl mb-8">Gestioná turnos, historial médico y mucho más en un solo lugar.</p>
        <a href="/register" class="bg-orange-500 text-white px-8 py-4 rounded-full text-lg hover:bg-orange-600">
            Empezar ahora
        </a>
    </section>
<!-- Características -->
    <section class="py-20 px-8 bg-gray-50">
        <h3 class="text-3xl font-bold text-center text-gray-800 mb-12">¿Qué podés hacer con PetCare?</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            
            <div class="bg-white rounded-2xl p-8 shadow text-center">
                <div class="text-5xl mb-4">📅</div>
                <h4 class="text-xl font-bold text-gray-800 mb-2">Turnos online</h4>
                <p class="text-gray-500">Sacá turnos con tu veterinaria favorita sin llamar por teléfono.</p>
            </div>

            <div class="bg-white rounded-2xl p-8 shadow text-center">
                <div class="text-5xl mb-4">📋</div>
                <h4 class="text-xl font-bold text-gray-800 mb-2">Historial médico</h4>
                <p class="text-gray-500">Accedé al historial completo de tu mascota en cualquier momento.</p>
            </div>

            <div class="bg-white rounded-2xl p-8 shadow text-center">
                <div class="text-5xl mb-4">🏥</div>
                <h4 class="text-xl font-bold text-gray-800 mb-2">Veterinarias cerca</h4>
                <p class="text-gray-500">Encontrá veterinarias cercanas y sus horarios de atención.</p>
            </div>

        </div>
    </section>
    <!-- Membresías -->
    <section class="py-20 px-8 bg-white">
        <h3 class="text-3xl font-bold text-center text-gray-800 mb-12">Nuestros planes</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">

            <div class="border rounded-2xl p-8 text-center hover:shadow-lg transition">
                <h4 class="text-xl font-bold text-gray-800 mb-2">Básico</h4>
                <p class="text-4xl font-bold text-orange-500 mb-4">Gratis</p>
                <ul class="text-gray-500 space-y-2 mb-8">
                    <li>✅ 1 mascota</li>
                    <li>✅ Turnos online</li>
                    <li>❌ Historial médico</li>
                    <li>❌ Consultas ilimitadas</li>
                </ul>
                <a href="/register" class="bg-orange-500 text-white px-6 py-3 rounded-full hover:bg-orange-600">Empezar</a>
            </div>

            <div class="border-2 border-orange-500 rounded-2xl p-8 text-center shadow-lg">
                <h4 class="text-xl font-bold text-gray-800 mb-2">Premium</h4>
                <p class="text-4xl font-bold text-orange-500 mb-4">$5.999<span class="text-lg text-gray-400">/mes</span></p>
                <ul class="text-gray-500 space-y-2 mb-8">
                    <li>✅ 3 mascotas</li>
                    <li>✅ Turnos online</li>
                    <li>✅ Historial médico</li>
                    <li>❌ Consultas ilimitadas</li>
                </ul>
                <a href="/register" class="bg-orange-500 text-white px-6 py-3 rounded-full hover:bg-orange-600">Empezar</a>
            </div>

            <div class="border rounded-2xl p-8 text-center hover:shadow-lg transition">
                <h4 class="text-xl font-bold text-gray-800 mb-2">Pro</h4>
                <p class="text-4xl font-bold text-orange-500 mb-4">$9.999<span class="text-lg text-gray-400">/mes</span></p>
                <ul class="text-gray-500 space-y-2 mb-8">
                    <li>✅ Mascotas ilimitadas</li>
                    <li>✅ Turnos online</li>
                    <li>✅ Historial médico</li>
                    <li>✅ Consultas ilimitadas</li>
                </ul>
                <a href="/register" class="bg-orange-500 text-white px-6 py-3 rounded-full hover:bg-orange-600">Empezar</a>
            </div>

        </div>
    </section>
    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12 px-8">
        <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <div>
                <h4 class="text-xl font-bold text-orange-500 mb-4">🐾 PetCare</h4>
                <p class="text-gray-400">El cuidado que tu mascota merece, siempre a mano.</p>
            </div>

            <div>
                <h4 class="text-lg font-bold mb-4">Links</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="/" class="hover:text-orange-500">Inicio</a></li>
                    <li><a href="/membresias" class="hover:text-orange-500">Membresías</a></li>
                    <li><a href="/servicios" class="hover:text-orange-500">Servicios</a></li>
                    <li><a href="/login" class="hover:text-orange-500">Iniciar sesión</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-lg font-bold mb-4">Contacto</h4>
                <ul class="space-y-2 text-gray-400">
                    <li>📧 petcaree2026@gmail.com</li>
                    <li>📱 Disponible en Android</li>
                </ul>
            </div>

        </div>
        <div class="text-center text-gray-500 mt-8 text-sm">
            © 2026 PetCare. Todos los derechos reservados.
        </div>
    </footer>
</body>
</html>