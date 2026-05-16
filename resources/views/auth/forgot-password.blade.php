<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | Health Nest</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        primary: { 50:'#eff6ff', 100:'#dbeafe', 200:'#bfdbfe', 300:'#93c5fd', 400:'#60a5fa', 500:'#3b82f6', 600:'#2563eb', 700:'#1d4ed8', 800:'#1e40af', 900:'#1e3a8a' },
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 font-sans flex items-center justify-center min-h-screen p-4">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100">
            <div class="text-center mb-8">
                <div class="w-12 h-12 bg-orange-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-key text-orange-600 text-xl"></i>
                </div>
                <h1 class="text-xl font-bold text-gray-900">Forgot Password?</h1>
                <p class="text-gray-500 text-xs mt-2 px-6">Enter your email address and we'll send you a link to reset your password.</p>
            </div>

            @if(session('status'))
                <div class="mb-6 text-[11px] font-medium text-green-600 bg-green-50 p-4 rounded-2xl border border-green-200">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Account Email</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                               class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-2 focus:ring-primary-500 focus:bg-white focus:outline-none transition-all"
                               placeholder="your@email.com">
                    </div>
                    @error('email') <p class="text-red-500 text-[10px] mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3.5 rounded-2xl shadow-lg shadow-primary-200 transition-all flex items-center justify-center gap-2 group">
                    Send Reset Link
                    <i class="fas fa-paper-plane text-[10px] group-hover:-translate-y-1 group-hover:translate-x-1 transition-transform"></i>
                </button>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-[10px] font-bold text-gray-400 hover:text-gray-600 uppercase tracking-widest flex items-center justify-center gap-2">
                        <i class="fas fa-arrow-left"></i> Back to Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
