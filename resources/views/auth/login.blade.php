<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Health Nest</title>
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
        {{-- Logo --}}
        <div class="flex flex-col items-center mb-8">
            <div class="w-14 h-14 bg-primary-600 rounded-2xl flex items-center justify-center shadow-lg shadow-primary-200 mb-4">
                <i class="fas fa-heartbeat text-white text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Welcome Back</h1>
            <p class="text-gray-500 text-sm mt-1">Integrated Health Nest</p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 p-8 border border-gray-100">
            @if(session('status'))
                <div class="mb-4 text-sm font-medium text-green-600 bg-green-50 p-3 rounded-xl border border-green-200">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Email Address</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                               class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-2 focus:ring-primary-500 focus:bg-white focus:outline-none transition-all"
                               placeholder="name@example.com">
                    </div>
                    @error('email') <p class="text-red-500 text-[10px] mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2 ml-1">
                        <label for="password" class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[10px] font-bold text-primary-600 hover:underline uppercase tracking-wider">Forgot?</a>
                        @endif
                    </div>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="password" name="password" id="password" required
                               class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-2 focus:ring-primary-500 focus:bg-white focus:outline-none transition-all"
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center ml-1">
                    <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                    <label for="remember" class="ml-2 text-xs text-gray-500 font-medium select-none">Remember me for 30 days</label>
                </div>

                <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3.5 rounded-2xl shadow-lg shadow-primary-200 transition-all flex items-center justify-center gap-2 group">
                    Sign In
                    <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                <p class="text-xs text-gray-500">Don't have a patient account?</p>
                <a href="{{ route('register') }}" class="inline-block mt-2 text-xs font-bold text-primary-600 hover:text-primary-700 uppercase tracking-widest">Create Account</a>
            </div>
        </div>

        <p class="text-center text-[10px] text-gray-400 mt-8 uppercase tracking-widest">&copy; {{ date('Y') }} Health Nest System</p>
    </div>
</body>
</html>
