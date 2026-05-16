<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join | Health Nest</title>
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
        <div class="flex flex-col items-center mb-6">
            <div class="w-12 h-12 bg-primary-600 rounded-2xl flex items-center justify-center shadow-lg shadow-primary-200 mb-3">
                <i class="fas fa-heartbeat text-white text-xl"></i>
            </div>
            <h1 class="text-xl font-bold text-gray-900">Create Patient Account</h1>
            <p class="text-gray-500 text-xs mt-1">Start your digital healthcare journey today</p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 p-8 border border-gray-100">
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="role" value="patient">

                <div>
                    <label for="name" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Full Name</label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                               class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-2 focus:ring-primary-500 focus:bg-white focus:outline-none transition-all"
                               placeholder="John Doe">
                    </div>
                    @error('name') <p class="text-red-500 text-[10px] mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Email Address</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-2 focus:ring-primary-500 focus:bg-white focus:outline-none transition-all"
                               placeholder="john@example.com">
                    </div>
                    @error('email') <p class="text-red-500 text-[10px] mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="phone" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Phone Number</label>
                    <div class="relative">
                        <i class="fas fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                               class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-2 focus:ring-primary-500 focus:bg-white focus:outline-none transition-all"
                               placeholder="+91 98765 43210">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Password</label>
                        <input type="password" name="password" id="password" required
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-2 focus:ring-primary-500 focus:bg-white focus:outline-none transition-all"
                               placeholder="••••••••">
                        @error('password') <p class="text-red-500 text-[10px] mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Confirm</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-2 focus:ring-primary-500 focus:bg-white focus:outline-none transition-all"
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 rounded-2xl shadow-lg shadow-primary-200 transition-all flex items-center justify-center gap-2 group">
                        Register Account
                        <i class="fas fa-user-plus text-xs group-hover:scale-110 transition-transform"></i>
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-5 border-t border-gray-100 text-center">
                <p class="text-[10px] text-gray-500">Already have an account?</p>
                <a href="{{ route('login') }}" class="inline-block mt-1 text-[10px] font-bold text-primary-600 hover:text-primary-700 uppercase tracking-widest">Sign In Here</a>
            </div>
        </div>
    </div>
</body>
</html>
