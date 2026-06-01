<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetCare - Iniciar sesión</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">

    <div class="bg-white rounded-2xl shadow-lg p-10 w-full max-w-md">
        
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-orange-500">🐾 PetCare</h1>
            <p class="text-gray-500 mt-2">Iniciá sesión en tu cuenta</p>
        </div>

        <form method="POST" action="/login">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Email</label>
                <input type="email" name="email" required
                    class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-400"
                    placeholder="tu@email.com">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">Contraseña</label>
                <input type="password" name="password" required
                    class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-400"
                    placeholder="••••••••">
            </div>

            <button type="submit"
                class="w-full bg-orange-500 text-white py-3 rounded-lg font-bold hover:bg-orange-600 transition">
                Iniciar sesión
            </button>
        </form>

        <p class="text-center text-gray-500 mt-6">
            ¿No tenés cuenta? 
            <a href="/register" class="text-orange-500 font-medium hover:underline">Registrate</a>
        </p>

    </div>

</body>
</html>