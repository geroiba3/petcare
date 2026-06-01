<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetCare - Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">

    <!-- Navbar -->
    <nav class="flex justify-between items-center px-8 py-4 bg-white shadow">
        <h1 class="text-2xl font-bold text-orange-500">🐾 PetCare</h1>
        <div class="flex items-center gap-4">
            <span class="text-gray-600">Hola, {{ auth()->user()->name }}</span>
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600">
                    Cerrar sesión
                </button>
            </form>
        </div>
    </nav>

    <!-- Contenido -->
    <div class="max-w-5xl mx-auto py-12 px-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-8">Panel principal</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="/mascotas" class="bg-white rounded-2xl p-6 shadow text-center hover:shadow-lg transition">
                <div class="text-4xl mb-3">🐶</div>
                <h3 class="text-lg font-bold text-gray-800">Mis mascotas</h3>
            </a>
            <a href="/agenda" class="bg-white rounded-2xl p-6 shadow text-center hover:shadow-lg transition">
                <div class="text-4xl mb-3">📅</div>
                <h3 class="text-lg font-bold text-gray-800">Agenda</h3>
            </a>
            <a href="/consultas" class="bg-white rounded-2xl p-6 shadow text-center hover:shadow-lg transition">
                <div class="text-4xl mb-3">💬</div>
                <h3 class="text-lg font-bold text-gray-800">Consultas</h3>
            </a>
        </div>
    </div>

</body>
</html>