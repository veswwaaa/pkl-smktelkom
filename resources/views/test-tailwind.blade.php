<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Tailwind CSS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-4 text-center">
            🎉 Tailwind CSS Berhasil!
        </h1>
        <p class="text-gray-600 text-center mb-6">
            Jika Anda melihat styling yang bagus, berarti Tailwind CSS sudah berfungsi dengan sempurna!
        </p>

        <div class="space-y-4">
            <button class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-4 rounded-lg transition duration-200">
                Button Primary
            </button>

            <button class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-4 rounded-lg transition duration-200">
                Button Success
            </button>

            <div class="grid grid-cols-3 gap-2">
                <div class="h-16 bg-red-500 rounded"></div>
                <div class="h-16 bg-yellow-500 rounded"></div>
                <div class="h-16 bg-purple-500 rounded"></div>
            </div>
        </div>

        <div class="mt-6 text-center">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                ✅ Tailwind CSS Active
            </span>
        </div>
    </div>
</body>
</html>
