<!DOCTYPE html>
<html lang="en" class="{{ auth()->user()?->preference?->dark_mode ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Health Nest - Modern Integrated Healthcare Management System">
    <title>@yield('title', 'Health Nest') | Health Nest</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
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
    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar-item { transition: all 0.2s ease; }
        .sidebar-item:hover { background-color: rgba(255,255,255,0.1); }
        .sidebar-item.active { background-color: rgba(255,255,255,0.15); border-left: 3px solid #60a5fa; }
        .stat-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); }
        .fade-in { animation: fadeIn 0.4s ease-in; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        ::-webkit-scrollbar { width: 6px; } ::-webkit-scrollbar-track { background: #f1f5f9; } ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 3px; }
    </style>
    @yield('styles')
</head>
<body class="bg-slate-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 transition-colors duration-200">

<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside id="sidebar" class="w-64 bg-gradient-to-b from-primary-800 to-primary-900 dark:from-gray-800 dark:to-gray-900 text-white flex flex-col fixed inset-y-0 left-0 z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-300">
        <!-- Logo -->
        <div class="flex items-center gap-3 px-6 py-5 border-b border-primary-700">
            <div class="w-9 h-9 bg-white rounded-lg flex items-center justify-center">
                <i class="fas fa-heartbeat text-primary-600 text-lg"></i>
            </div>
            <div>
                <div class="font-bold text-sm leading-tight">Health</div>
                <div class="text-primary-300 text-xs">Nest</div>
            </div>
        </div>

        <!-- User info -->
        <div class="px-6 py-4 border-b border-primary-700">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-primary-400 flex items-center justify-center text-xs font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium truncate">{{ auth()->user()->name }}</div>
                    <div class="text-primary-300 text-xs capitalize">{{ auth()->user()->role }}</div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-4 overflow-y-auto">
            @if(auth()->user()->role === 'admin')
                <div class="text-primary-400 text-xs font-semibold uppercase tracking-wider px-3 mb-2">Admin Panel</div>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-th-large w-4"></i> Dashboard
                </a>
                <a href="{{ route('admin.doctors.index') }}" class="sidebar-item {{ request()->routeIs('admin.doctors*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-user-md w-4"></i> Doctors
                </a>
                <a href="{{ route('admin.patients.index') }}" class="sidebar-item {{ request()->routeIs('admin.patients*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-users w-4"></i> Patients
                </a>
                <a href="{{ route('admin.appointments.index') }}" class="sidebar-item {{ request()->routeIs('admin.appointments*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-calendar-check w-4"></i> Appointments
                </a>
                <a href="{{ route('admin.departments.index') }}" class="sidebar-item {{ request()->routeIs('admin.departments*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-hospital w-4"></i> Departments
                </a>
                <a href="{{ route('admin.specialties.index') }}" class="sidebar-item {{ request()->routeIs('admin.specialties*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-stethoscope w-4"></i> Specialties
                </a>
                <a href="{{ route('admin.invoices.index') }}" class="sidebar-item {{ request()->routeIs('admin.invoices*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-file-invoice-dollar w-4"></i> Invoices
                </a>
                <a href="{{ route('admin.leaves.index') }}" class="sidebar-item {{ request()->routeIs('admin.leaves*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-calendar-times w-4"></i> Leave Requests
                </a>
                <a href="{{ route('admin.reports.index') }}" class="sidebar-item {{ request()->routeIs('admin.reports*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-chart-bar w-4"></i> Reports
                </a>
                <a href="{{ route('admin.complaints.index') }}" class="sidebar-item {{ request()->routeIs('admin.complaints*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-comment-dots w-4"></i> Complaints
                </a>
                <a href="{{ route('admin.announcements.index') }}" class="sidebar-item {{ request()->routeIs('admin.announcements*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-bullhorn w-4"></i> Announcements
                </a>
                <a href="{{ route('admin.emergency-alerts.index') }}" class="sidebar-item {{ request()->routeIs('admin.emergency-alerts*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-exclamation-triangle w-4"></i> Emergency SOS
                </a>
                <a href="{{ route('admin.queue.index') }}" class="sidebar-item {{ request()->routeIs('admin.queue*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-users-viewfinder w-4"></i> Walk-in Queue
                </a>
                <a href="{{ route('admin.inventory.index') }}" class="sidebar-item {{ request()->routeIs('admin.inventory*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-pills w-4"></i> Pharmacy Inventory
                </a>
                <a href="{{ route('admin.audit-logs.index') }}" class="sidebar-item {{ request()->routeIs('admin.audit-logs*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-history w-4"></i> Audit Log
                </a>
                <a href="{{ route('admin.users.index') }}" class="sidebar-item {{ request()->routeIs('admin.users*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-user-cog w-4"></i> User Management
                </a>
                <a href="{{ route('admin.settings.index') }}" class="sidebar-item {{ request()->routeIs('admin.settings*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-cog w-4"></i> Settings
                </a>
            @elseif(auth()->user()->role === 'doctor')
                <div class="text-primary-400 text-xs font-semibold uppercase tracking-wider px-3 mb-2">Doctor Panel</div>
                <a href="{{ route('doctor.dashboard') }}" class="sidebar-item {{ request()->routeIs('doctor.dashboard') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-th-large w-4"></i> Dashboard
                </a>
                <a href="{{ route('doctor.appointments.index') }}" class="sidebar-item {{ request()->routeIs('doctor.appointments*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-calendar-check w-4"></i> Appointments
                </a>
                <a href="{{ route('doctor.soap-notes.index') }}" class="sidebar-item {{ request()->routeIs('doctor.soap-notes*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-notes-medical w-4"></i> SOAP Notes
                </a>
                <a href="{{ route('doctor.prescriptions.index') }}" class="sidebar-item {{ request()->routeIs('doctor.prescriptions*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-prescription-bottle-alt w-4"></i> Prescriptions
                </a>
                <a href="{{ route('doctor.lab-orders.index') }}" class="sidebar-item {{ request()->routeIs('doctor.lab-orders*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-vials w-4"></i> Lab Orders
                </a>
                <a href="{{ route('doctor.referrals.index') }}" class="sidebar-item {{ request()->routeIs('doctor.referrals*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-share-square w-4"></i> Referrals
                </a>
                <a href="{{ route('doctor.messages.index') }}" class="sidebar-item {{ request()->routeIs('doctor.messages*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-envelope w-4"></i> Messages
                </a>
                <a href="{{ route('doctor.availability.index') }}" class="sidebar-item {{ request()->routeIs('doctor.availability*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-clock w-4"></i> Availability
                </a>
                <a href="{{ route('doctor.leaves.index') }}" class="sidebar-item {{ request()->routeIs('doctor.leaves*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-calendar-times w-4"></i> Leave Requests
                </a>
                <a href="{{ route('doctor.profile') }}" class="sidebar-item {{ request()->routeIs('doctor.profile*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-rupee-sign w-4"></i> My Profile & Fee
                </a>
            @elseif(auth()->user()->role === 'patient')
                <div class="text-primary-400 text-xs font-semibold uppercase tracking-wider px-3 mb-2">Patient Panel</div>
                <a href="{{ route('patient.dashboard') }}" class="sidebar-item {{ request()->routeIs('patient.dashboard') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-th-large w-4"></i> Dashboard
                </a>
                <a href="{{ route('patient.appointments.index') }}" class="sidebar-item {{ request()->routeIs('patient.appointments*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-calendar-check w-4"></i> My Appointments
                </a>
                <a href="{{ route('patient.appointments.create') }}" class="sidebar-item {{ request()->routeIs('patient.appointments.create') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-plus-circle w-4"></i> Book Appointment
                </a>
                <a href="{{ route('patient.queue.index') }}" class="sidebar-item {{ request()->routeIs('patient.queue*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-users-viewfinder w-4"></i> Virtual Queue
                </a>
                <a href="{{ route('patient.medical-records.index') }}" class="sidebar-item {{ request()->routeIs('patient.medical-records*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-file-medical w-4"></i> Medical Records
                </a>
                <a href="{{ route('patient.vitals.index') }}" class="sidebar-item {{ request()->routeIs('patient.vitals*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-heartbeat w-4"></i> Vitals Tracker
                </a>
                <a href="{{ route('patient.vaccinations.index') }}" class="sidebar-item {{ request()->routeIs('patient.vaccinations*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-syringes w-4"></i> Vaccination Tracker
                </a>
                <a href="{{ route('patient.invoices.index') }}" class="sidebar-item {{ request()->routeIs('patient.invoices*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-file-invoice-dollar w-4"></i> Bills & Payments
                </a>
                <a href="{{ route('patient.messages.index') }}" class="sidebar-item {{ request()->routeIs('patient.messages*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-envelope w-4"></i> Messages
                </a>
                <a href="{{ route('patient.reviews.index') }}" class="sidebar-item {{ request()->routeIs('patient.reviews*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-star w-4"></i> My Reviews
                </a>
                <a href="{{ route('patient.profile') }}" class="sidebar-item {{ request()->routeIs('patient.profile*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-user w-4"></i> My Profile
                </a>
                <a href="{{ route('patient.complaints.index') }}" class="sidebar-item {{ request()->routeIs('patient.complaints*') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
                    <i class="fas fa-comment-dots w-4"></i> Requests & Complaints
                </a>
                {{-- Emergency quick-call in sidebar --}}
                <div class="mt-4 mx-3 bg-red-600/20 border border-red-500/30 rounded-xl p-3">
                    <p class="text-xs font-bold text-red-300 mb-2"><i class="fas fa-ambulance mr-1"></i>Emergency</p>
                    <button id="sos-btn" class="w-full bg-red-600 hover:bg-red-700 text-white font-extrabold text-sm py-2 rounded-lg shadow-lg shadow-red-900/50 transition-all">
                        TRIGGER SOS
                    </button>
                    <p class="text-red-300 text-[10px] mt-2 text-center">Sends live location to hospital</p>
                </div>
            @endif
        </nav>

        <!-- Logout -->
        <div class="px-3 py-4 border-t border-primary-700">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-lg w-full text-left text-sm text-primary-200 hover:text-white">
                    <i class="fas fa-sign-out-alt w-4"></i> Sign Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Main content -->
    <div class="flex-1 flex flex-col min-h-screen md:ml-64">
        <!-- Topbar -->
        <header class="bg-white border-b border-gray-200 px-4 md:px-6 py-4 flex items-center justify-between sticky top-0 z-40">
            <div class="flex items-center gap-4 flex-1 min-w-0">
                <button id="sidebarToggle" class="md:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 flex-shrink-0">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <h1 class="font-semibold text-gray-800 dark:text-gray-100 text-base md:text-lg truncate max-w-[180px] sm:max-w-none flex-1">@yield('page-title', 'Dashboard')</h1>
            </div>
            <div class="flex items-center gap-4 flex-shrink-0">
                <button id="darkModeToggle" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors" title="Toggle Dark Mode">
                    <i class="fas {{ auth()->user()?->preference?->dark_mode ? 'fa-sun' : 'fa-moon' }} text-lg"></i>
                </button>
                <div class="hidden sm:block text-sm text-gray-500 dark:text-gray-400">
                    <i class="fas fa-calendar-alt mr-1"></i>
                    {{ now()->format('D, d M Y') }}
                </div>
            </div>
        </header>

        <!-- Page content -->
        <main class="flex-1 p-4 md:p-6 fade-in overflow-y-auto">
            @if(session('success'))
                <div id="flash-success" class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
                    <i class="fas fa-check-circle text-green-500"></i>
                    {{ session('success') }}
                    <button onclick="document.getElementById('flash-success').remove()" class="ml-auto text-green-600 hover:text-green-800"><i class="fas fa-times"></i></button>
                </div>
            @endif
            @if(session('error'))
                <div id="flash-error" class="mb-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                    {{ session('error') }}
                    <button onclick="document.getElementById('flash-error').remove()" class="ml-auto text-red-600 hover:text-red-800"><i class="fas fa-times"></i></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<!-- Mobile sidebar overlay -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden"></div>

<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggle  = document.getElementById('sidebarToggle');
    const darkModeToggle = document.getElementById('darkModeToggle');
    const htmlEl = document.documentElement;

    toggle?.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    });
    overlay?.addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    });

    darkModeToggle?.addEventListener('click', () => {
        const isDark = htmlEl.classList.toggle('dark');
        const icon = darkModeToggle.querySelector('i');
        if (isDark) {
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
        } else {
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
        }

        fetch('{{ route("preferences.dark-mode") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ dark_mode: isDark })
        });
    });

    // SOS Logic
    document.getElementById('sos-btn')?.addEventListener('click', function() {
        if (!confirm('Are you sure you want to trigger an Emergency SOS?')) return;
        
        const btn = this;
        btn.disabled = true;
        btn.innerText = 'LOCATING...';

        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(function(position) {
                fetch('{{ route("patient.sos.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude
                    })
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    btn.innerText = 'SOS SENT';
                    btn.classList.replace('bg-red-600', 'bg-green-600');
                })
                .catch(err => {
                    alert('Failed to send SOS. Please call emergency services.');
                    btn.disabled = false;
                    btn.innerText = 'TRIGGER SOS';
                });
            }, function(error) {
                alert('Geolocation failed: ' + error.message + '. Please check browser permissions.');
                btn.disabled = false;
                btn.innerText = 'TRIGGER SOS';
            });
        } else {
            alert('Geolocation is not supported by your browser.');
            btn.disabled = false;
        }
    });
</script>
    @auth
    @if(auth()->user()->role === 'patient')
    <div x-data="{ 
        open: false, 
        messages: [{ role: 'bot', text: 'Hello! I am your Health Nest Assistant. How can I help you today?' }], 
        input: '',
        state: 'symptoms',
        lastSpecialist: '',
        scrollToBottom() {
            this.$nextTick(() => {
                const box = document.getElementById('chat-messages');
                if (box) box.scrollTop = box.scrollHeight;
            });
        },
        sendMessage() {
            if (!this.input.trim()) return;
            const text = this.input.trim();
            this.messages.push({ role: 'user', text: text });
            this.input = '';
            this.scrollToBottom();

            if (this.state === 'confirm_booking') {
                const userMsg = text.toLowerCase();
                if (userMsg.includes('yes') || userMsg.includes('yep') || userMsg.includes('sure') || userMsg.includes('book') || userMsg.includes('ok') || userMsg.includes('ha')) {
                    this.messages.push({ 
                        role: 'bot', 
                        text: 'Wonderful! 📅 <a href=\'{{ route(\'patient.appointments.create\') }}\' class=\'underline text-blue-600 font-bold hover:text-blue-800\'>Click here to book your appointment</a> with our ' + this.lastSpecialist + ' now!' 
                    });
                    this.state = 'symptoms';
                } else if (userMsg.includes('no') || userMsg.includes('nope') || userMsg.includes('not') || userMsg.includes('na')) {
                    this.messages.push({ 
                        role: 'bot', 
                        text: 'No problem at all! Let me know if you want to check any other symptoms.' 
                    });
                    this.state = 'symptoms';
                } else {
                    this.messages.push({ 
                        role: 'bot', 
                        text: 'Please reply with **Yes** to book an appointment, or **No** to search other symptoms.' 
                    });
                }
                this.scrollToBottom();
                return;
            }

            setTimeout(() => {
                fetch('{{ route('symptom-checker.check') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ message: text })
                })
                .then(res => res.json())
                .then(data => {
                    this.messages.push({ role: 'bot', text: data.reply });
                    if (data.specialist) {
                        this.lastSpecialist = data.specialist;
                        this.state = 'confirm_booking';
                        this.messages.push({ 
                            role: 'bot', 
                            text: 'Would you like to schedule an appointment with our **' + data.specialist + '**? (Please reply with **Yes** or **No**)' 
                        });
                    }
                    this.scrollToBottom();
                });
            }, 300);
        }
    }" class="fixed bottom-6 right-6 z-[60]">
        <!-- Chat Bubble Button -->
        <button @click="open = !open" class="w-14 h-14 bg-primary-600 hover:bg-primary-700 text-white rounded-full shadow-2xl flex items-center justify-center transition-all hover:scale-110 active:scale-95">
            <i class="fas fa-comment-medical text-2xl" x-show="!open"></i>
            <i class="fas fa-times text-2xl" x-show="open"></i>
        </button>

        <!-- Chat Window -->
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-10 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-10 scale-95"
             class="absolute bottom-20 right-0 w-[calc(100vw-2.5rem)] sm:w-[350px] bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col">
            
            <!-- Chat Header -->
            <div class="bg-primary-600 p-4 text-white flex items-center gap-3">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-robot"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold">Health Assistant</h3>
                    <p class="text-[10px] text-primary-200">AI Symptom Checker</p>
                </div>
            </div>

            <!-- Messages Area -->
            <div class="h-[350px] overflow-y-auto p-4 space-y-4 bg-gray-50 dark:bg-gray-900/20" id="chat-messages">
                <template x-for="msg in messages">
                    <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                        <div :class="msg.role === 'user' ? 'bg-primary-600 text-white' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200'"
                             class="max-w-[80%] px-4 py-2 rounded-2xl text-xs shadow-sm"
                             x-html="msg.text">
                        </div>
                    </div>
                </template>
            </div>

            <!-- Input Area -->
            <div class="p-4 border-t dark:border-gray-700 flex gap-2">
                <input type="text" x-model="input" @keyup.enter="sendMessage()" placeholder="Ask about symptoms..." class="flex-1 bg-gray-100 dark:bg-gray-700 border-none rounded-xl px-4 py-2 text-xs focus:ring-2 focus:ring-primary-500 focus:outline-none">
                <button @click="sendMessage()" class="w-10 h-10 bg-primary-50 dark:bg-primary-900/30 text-primary-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>
    @endif
    @endauth

    @yield('scripts')
</body>
</html>
