@extends('layouts.app')
@section('title', 'Patient Dashboard')
@section('page-title', 'Patient Health Summary')
@section('content')
<div class="space-y-6">
    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Total Visits -->
        <a href="{{ route('patient.appointments.index') }}" class="bg-gradient-to-br from-primary-600 to-primary-800 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden block hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
            <i class="fas fa-calendar-check absolute right-[-10px] bottom-[-10px] text-8xl text-white/10"></i>
            <p class="text-primary-100 text-xs font-medium mb-1">Total Visits</p>
            <p class="text-3xl font-bold">{{ $stats['total_appointments'] }}</p>
            <span class="mt-4 inline-block text-xs font-bold bg-white/20 hover:bg-white/30 px-3 py-1.5 rounded-lg transition-colors">History</span>
        </a>

        <!-- Upcoming -->
        <a href="{{ route('patient.appointments.create') }}" class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden block hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
            <i class="fas fa-clock absolute right-[-10px] bottom-[-10px] text-8xl text-white/10"></i>
            <p class="text-blue-100 text-xs font-medium mb-1">Upcoming</p>
            <p class="text-3xl font-bold">{{ $stats['upcoming_appointments'] }}</p>
            <span class="mt-4 inline-block text-xs font-bold bg-white/20 hover:bg-white/30 px-3 py-1.5 rounded-lg transition-colors">Book New</span>
        </a>

        <!-- Vitals Logged -->
        <a href="{{ route('patient.vitals.index') }}" class="bg-gradient-to-br from-green-500 to-green-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden block hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
            <i class="fas fa-heartbeat absolute right-[-10px] bottom-[-10px] text-8xl text-white/10"></i>
            <p class="text-green-100 text-xs font-medium mb-1">Vitals Logged</p>
            <p class="text-3xl font-bold">{{ auth()->user()->patient->vitals()->count() }}</p>
            <span class="mt-4 inline-block text-xs font-bold bg-white/20 hover:bg-white/30 px-3 py-1.5 rounded-lg transition-colors">Vitals Chart</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Appointments Section --}}
        <div class="space-y-6">
            {{-- Upcoming --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b dark:border-gray-700 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 dark:text-gray-100">Confirmed & Pending</h3>
                    <a href="{{ route('patient.appointments.index') }}" class="text-xs text-primary-600 hover:underline font-medium">View All</a>
                </div>
                <div class="p-6 space-y-4">
                    @forelse($upcoming as $appt)
                    <a href="{{ route('patient.appointments.index') }}" class="flex items-center gap-4 p-4 border dark:border-gray-700 rounded-2xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                        <div class="shrink-0 text-center bg-primary-100 dark:bg-primary-900/40 p-2.5 rounded-xl min-w-[55px] group-hover:bg-primary-200">
                            <p class="text-[10px] text-primary-600 font-bold uppercase">{{ $appt->appointment_date->format('M') }}</p>
                            <p class="text-lg font-black text-primary-700 dark:text-primary-300">{{ $appt->appointment_date->format('d') }}</p>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-gray-900 dark:text-white truncate group-hover:text-blue-600 group-hover:underline">{{ $appt->doctor->user->display_name }}</p>
                            <p class="text-xs text-gray-500">{{ $appt->appointment_time }} · {{ $appt->doctor->specialization }}</p>
                        </div>
                        <div class="shrink-0">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase
                                {{ $appt->status === 'approved' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ $appt->status }}
                            </span>
                        </div>
                    </a>
                    @empty
                    <p class="text-sm text-gray-400 text-center py-4">No upcoming appointments.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Recent Medical History --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b dark:border-gray-700 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 dark:text-gray-100">Recent Completed Visits</h3>
                <a href="{{ route('patient.medical-records.index') }}" class="text-xs text-primary-600 hover:underline font-medium">View All</a>
            </div>
            <div class="p-6 space-y-6">
                @forelse($recent as $appt)
                <div class="relative pl-6 border-l-2 border-gray-100 dark:border-gray-700">
                    <div class="absolute left-[-9px] top-0 w-4 h-4 rounded-full bg-primary-600 border-4 border-white dark:border-gray-800"></div>
                    <p class="text-xs text-gray-400 font-medium mb-1">{{ $appt->appointment_date->format('d M Y') }}</p>
                    <a href="{{ route('patient.appointments.index') }}" class="block group">
                        <p class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-blue-600 group-hover:underline">{{ $appt->doctor->user->display_name }}</p>
                    </a>
                    @if($appt->consultation)
                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">{{ Str::limit($appt->consultation->notes, 100) }}</p>
                    @endif
                    <div class="flex gap-2 mt-3">
                        <a href="{{ route('patient.medical-records.index') }}" class="text-[10px] font-bold text-primary-600 hover:underline uppercase tracking-wider">View Notes</a>
                        <a href="{{ route('patient.reviews.create') }}" class="text-[10px] font-bold text-yellow-600 hover:underline uppercase tracking-wider">Rate Doctor</a>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-4">No recent medical history.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
