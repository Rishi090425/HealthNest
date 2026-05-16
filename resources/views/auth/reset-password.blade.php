<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | Health Nest</title>
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
                <div class="w-12 h-12 bg-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-lock-open text-primary-600 text-xl"></i>
                </div>
                <h1 class="text-xl font-bold text-gray-900">Set New Password</h1>
                <p class="text-gray-500 text-xs mt-2 px-6">Choose a secure password for your account.</p>
            </div>

            <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label for="email" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ $email ?? old('email') }}" required readonly
                           class="w-full px-4 py-2.5 bg-gray-100 border border-gray-200 rounded-2xl text-sm text-gray-500 focus:outline-none">
                    @error('email') <p class="text-red-500 text-[10px] mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">New Password</label>
                    <input type="password" name="password" id="password" required autofocus
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-2 focus:ring-primary-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="••••••••">
                    @error('password') <p class="text-red-500 text-[10px] mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 ml-1">Confirm New Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-2 focus:ring-primary-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="••••••••">
                </div>

                <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3.5 rounded-2xl shadow-lg shadow-primary-200 transition-all flex items-center justify-center gap-2">
                    Reset Password
                </button>
            </form>
        </div>
    </div>
</body>
</html>
