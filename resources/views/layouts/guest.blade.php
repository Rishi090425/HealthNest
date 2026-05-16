<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'HealthCare Portal')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] } } }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .auth-bg { background: linear-gradient(135deg, #1e40af 0%, #2563eb 50%, #3b82f6 100%); }
    </style>
</head>
<body class="min-h-screen auth-bg flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-2xl shadow-lg mb-4">
                <i class="fas fa-heartbeat text-blue-600 text-2xl"></i>
            </div>
            <h1 class="text-white font-bold text-2xl">HealthCare Portal</h1>
            <p class="text-blue-200 text-sm mt-1">Integrated Healthcare Management System</p>
        </div>
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            @yield('content')
        </div>
    </div>
</body>
</html>
