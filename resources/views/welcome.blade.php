<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Health Nest - Modern Integrated Healthcare Management System">
    <title>Health Nest — Modern Healthcare Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { theme: { extend: { fontFamily: { sans: ['Inter','sans-serif'] } } } }</script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hero-bg { background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 60%, #3b82f6 100%); }
        .card-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -10px rgba(0,0,0,0.15); }
        .fade-up { animation: fadeUp 0.6s ease both; }
        @keyframes fadeUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        .delay-1 { animation-delay: 0.1s; } .delay-2 { animation-delay: 0.2s; } .delay-3 { animation-delay: 0.3s; }
    </style>
</head>
<body class="bg-white">
    <!-- Navbar -->
    <nav class="fixed top-0 w-full bg-white/80 backdrop-blur-md border-b border-gray-100 z-50">
        <div class="max-w-6xl mx-auto px-4 md:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-heartbeat text-white text-sm"></i>
                </div>
                <span class="font-bold text-gray-800 text-base sm:text-lg">Health Nest</span>
            </div>
            <div class="flex items-center gap-2 sm:gap-3">
                @auth
                    @php
                        $dash = match(auth()->user()->role) {
                            'admin' => route('admin.dashboard'),
                            'doctor' => route('doctor.dashboard'),
                            default => route('patient.dashboard'),
                        };
                    @endphp
                    <a href="{{ $dash }}" class="bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-medium px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg transition-colors">Go to Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-800 text-xs sm:text-sm font-medium px-2 sm:px-4 py-1.5 sm:py-2">Sign In</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-medium px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg transition-colors">Get Started</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero-bg pt-32 pb-24 px-4 sm:px-6">
        <div class="max-w-4xl mx-auto text-center">
            <span class="inline-block bg-white/20 text-white text-[10px] sm:text-xs font-semibold px-3 py-1.5 rounded-full mb-6 fade-up">✦ Modern Healthcare Management</span>
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-6 fade-up delay-1">
                Integrated Health<br>
                <span class="text-blue-200">Nest</span>
            </h1>
            <p class="text-blue-100 text-sm sm:text-xl max-w-2xl mx-auto mb-10 fade-up delay-2">
                A unified platform connecting patients, doctors, and administrators for seamless healthcare management.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-3 sm:gap-4 fade-up delay-3 px-2">
                <a href="{{ route('register') }}" id="get-started-btn"
                    class="bg-white text-blue-700 hover:bg-blue-50 font-bold px-5 py-3 sm:px-8 sm:py-3.5 rounded-xl text-xs sm:text-sm transition-colors shadow-lg">
                    <i class="fas fa-rocket mr-2"></i>Get Started Free
                </a>
                <a href="{{ route('login') }}"
                    class="border-2 border-white/40 text-white hover:bg-white/10 font-semibold px-5 py-3 sm:px-8 sm:py-3.5 rounded-xl text-xs sm:text-sm transition-colors">
                    <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                </a>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="py-20 px-6 bg-slate-50">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-14">
                <h2 class="text-3xl font-bold text-gray-800 mb-3">Everything You Need</h2>
                <p class="text-gray-500">A complete suite of tools for modern healthcare management</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach([
                    ['icon'=>'fas fa-user-md','color'=>'blue','title'=>'Doctor Panel','desc'=>'Manage appointments, availability schedules, and add consultation notes with ease.'],
                    ['icon'=>'fas fa-users','color'=>'purple','title'=>'Patient Management','desc'=>'Full patient profiles with medical history, emergency contacts, and appointment history.'],
                    ['icon'=>'fas fa-calendar-check','color'=>'green','title'=>'Appointment System','desc'=>'Book, approve, and track appointments with real-time status updates.'],
                    ['icon'=>'fas fa-notes-medical','color'=>'red','title'=>'Consultation Module','desc'=>'Record diagnoses, treatment plans, and upload prescriptions securely.'],
                    ['icon'=>'fas fa-tachometer-alt','color'=>'yellow','title'=>'Admin Dashboard','desc'=>'Complete oversight with statistics on doctors, patients, and appointments.'],
                    ['icon'=>'fas fa-shield-alt','color'=>'indigo','title'=>'Role-Based Access','desc'=>'Secure three-tier access control for Admin, Doctor, and Patient roles.'],
                ] as $feature)
                <div class="card-hover bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                    <div class="w-12 h-12 bg-{{ $feature['color'] }}-50 rounded-xl flex items-center justify-center mb-4">
                        <i class="{{ $feature['icon'] }} text-{{ $feature['color'] }}-600 text-lg"></i>
                    </div>
                    <h3 class="text-base font-semibold text-gray-800 mb-2">{{ $feature['title'] }}</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">{{ $feature['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Role Cards -->
    <section class="py-20 px-6">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-14">
                <h2 class="text-3xl font-bold text-gray-800 mb-3">Access For Everyone</h2>
                <p class="text-gray-500">Tailored dashboards for every role in the healthcare ecosystem</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="card-hover bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl p-6 text-white">
                    <i class="fas fa-user-shield text-3xl mb-4 opacity-80"></i>
                    <h3 class="text-xl font-bold mb-2">Admin</h3>
                    <ul class="text-blue-100 text-sm space-y-1.5">
                        <li><i class="fas fa-check mr-2 text-blue-300"></i>Manage all doctors & patients</li>
                        <li><i class="fas fa-check mr-2 text-blue-300"></i>Full appointment oversight</li>
                        <li><i class="fas fa-check mr-2 text-blue-300"></i>Portal-wide statistics</li>
                    </ul>
                </div>
                <div class="card-hover bg-gradient-to-br from-green-600 to-green-800 rounded-2xl p-6 text-white">
                    <i class="fas fa-user-md text-3xl mb-4 opacity-80"></i>
                    <h3 class="text-xl font-bold mb-2">Doctor</h3>
                    <ul class="text-green-100 text-sm space-y-1.5">
                        <li><i class="fas fa-check mr-2 text-green-300"></i>Manage availability schedule</li>
                        <li><i class="fas fa-check mr-2 text-green-300"></i>Approve/reject appointments</li>
                        <li><i class="fas fa-check mr-2 text-green-300"></i>Add consultation notes</li>
                    </ul>
                </div>
                <div class="card-hover bg-gradient-to-br from-purple-600 to-purple-800 rounded-2xl p-6 text-white">
                    <i class="fas fa-user text-3xl mb-4 opacity-80"></i>
                    <h3 class="text-xl font-bold mb-2">Patient</h3>
                    <ul class="text-purple-100 text-sm space-y-1.5">
                        <li><i class="fas fa-check mr-2 text-purple-300"></i>Book appointments online</li>
                        <li><i class="fas fa-check mr-2 text-purple-300"></i>View consultation history</li>
                        <li><i class="fas fa-check mr-2 text-purple-300"></i>Manage health profile</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="hero-bg py-16 px-6 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">Ready to get started?</h2>
        <p class="text-blue-200 mb-8">Join thousands of healthcare professionals using our platform.</p>
        <a href="{{ route('register') }}" class="bg-white text-blue-700 font-bold px-8 py-3.5 rounded-xl text-sm hover:bg-blue-50 transition-colors shadow-lg">
            <i class="fas fa-arrow-right mr-2"></i>Create Free Account
        </a>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-8 px-6 text-center text-sm">
        <div class="flex items-center justify-center gap-2 mb-2">
            <i class="fas fa-heartbeat text-blue-500"></i>
            <span class="text-white font-semibold">Health Nest</span>
        </div>
        <p>&copy; {{ date('Y') }} Health Nest. All rights reserved.</p>
    </footer>
</body>
</html>
